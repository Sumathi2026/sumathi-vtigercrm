<?php
error_reporting(0);

class Guard_ApproveOrReject_Action extends Vtiger_Action_Controller {

    private const MIDDLEWARE_ENDPOINT = 'http://216.48.190.195:5001';
    private const CONNECTION_TIMEOUT = 30;
    private const REQUEST_TIMEOUT = 60;

    public function checkPermission(Vtiger_Request $request) {
        return true; // allow all for now
    }

    public function process(Vtiger_Request $request) {
        $record = $request->get('record');
        $sourceModule = $request->get('module'); // use module not source_module
        $statusValue = $request->get('apStatus');
        $response = new Vtiger_Response();

        $recordModel = Vtiger_Record_Model::getInstanceById($record, $sourceModule);
        if ($recordModel) {
            $recordModel->set('mode', 'edit');
            $recordModel->set('cf_915', $statusValue);

            if ($statusValue === 'Rejected') {
                $rejectionReason = $request->get('RejectionReason');
                if (empty($rejectionReason)) {
                    $response->setResult([
                        'success' => false,
                        'message' => 'Rejection Reason is Empty'
                    ]);
                    $response->emit();
                    return;
                }
                $recordModel->set('cf_917', $rejectionReason);
            }

            $recordModel->save();

            $employeeId = $recordModel->get('badge_no');

            if (!empty($employeeId)) {
                try {
                    $middlewareResult = $this->updateEmployeeStatusInMiddleware($employeeId, $statusValue);

                    if ($middlewareResult['success']) {
                        $msg = ($statusValue === 'Rejected')
                            ? 'Successfully Rejected and updated in middleware'
                            : 'Successfully Accepted and updated in middleware';
                        $response->setResult(['success' => true, 'message' => $msg]);
                    } else {
                        $msg = ($statusValue === 'Rejected')
                            ? 'Rejected in CRM but middleware update failed: '
                            : 'Accepted in CRM but middleware update failed: ';
                        $response->setResult(['success' => true, 'message' => $msg . $middlewareResult['error']]);
                    }
                } catch (Exception $e) {
                    $msg = ($statusValue === 'Rejected')
                        ? 'Successfully Rejected in CRM, but middleware update failed: '
                        : 'Successfully Accepted in CRM, but middleware update failed: ';
                    $response->setResult(['success' => true, 'message' => $msg . $e->getMessage()]);
                }
            } else {
                $msg = ($statusValue === 'Rejected')
                    ? 'Successfully Rejected (Employee ID not found for middleware update)'
                    : 'Successfully Accepted (Employee ID not found for middleware update)';
                $response->setResult(['success' => true, 'message' => $msg]);
            }
        } else {
            $response->setResult(['success' => false, 'message' => 'Not Able To Approve Or Reject']);
        }

        $response->emit();
    }

    private function updateEmployeeStatusInMiddleware($employeeId, $status) {
        $url = sprintf('%s/status?emp_id=%s&status=%s',
            self::MIDDLEWARE_ENDPOINT,
            urlencode($employeeId),
            urlencode($status)
        );

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => self::REQUEST_TIMEOUT,
            CURLOPT_CONNECTTIMEOUT => self::CONNECTION_TIMEOUT,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'Accept: */*',
                'User-Agent: curl',
                'Connection: keep-alive',
                'Content-Type: application/x-www-form-urlencoded'
            ]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_errno($curl);
        $curlErrorMessage = curl_error($curl);

        curl_close($curl);

        if ($curlError) {
            return [
                'success' => false,
                'error' => sprintf("Connection failed (Error #%d): %s", $curlError, $curlErrorMessage)
            ];
        }

        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => sprintf("HTTP Error: %d. Response: %s", $httpCode, $response)
            ];
        }

        if (stripos($response, 'success') !== false || stripos($response, 'updated') !== false || $httpCode == 200) {
            return ['success' => true, 'response' => $response];
        }

        return [
            'success' => false,
            'error' => 'Middleware update failed: ' . $response
        ];
    }
}
