<?php
/*+***********************************************************************************
 * SaveApprovalStatus Action for Guard Module
 *************************************************************************************/

class Guard_SaveApprovalStatus_Action extends Vtiger_Action_Controller {

    public function checkPermission(Vtiger_Request $request) {
        return true; // allow all for now
    }

    public function process(Vtiger_Request $request) {
        $recordId = $request->get('record');
        $status   = $request->get('status');
        $rejectionReason = $request->get('rejectionReason');

        $response = new Vtiger_Response();

        if (empty($recordId) || $status === null) {
            $response->setError(400, 'Missing record id or status');
            $response->emit();
            return;
        }

        try {
            // Load record
            $recordModel = Vtiger_Record_Model::getInstanceById($recordId, 'Guard');
            if (!$recordModel) {
                throw new Exception('Record not found: ' . $recordId);
            }

            // ✅ Update with new field name: cf_915
            $recordModel->set('cf_915', $status);

            // Handle rejection reason - update this field name too if needed
            if (!empty($rejectionReason)) {
                $recordModel->set('cf_917', $rejectionReason);
            } else {
                if (strtolower($status) !== 'rejected') {
                    $recordModel->set('cf_917', null);
                }
            }

            // Save record
            $recordModel->save();

            // Return success
            $response->setResult([
                'success' => true,
                'status'  => $status,
            ]);
            $response->emit();
            return;

        } catch (Exception $e) {
            $response->setError($e->getCode() ?: 500, $e->getMessage());
            $response->emit();
            return;
        }
    }
}