<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Potentials_ApproveOrReject_Action extends Vtiger_Action_Controller {

    public function checkPermission(Vtiger_Request $request) {
        $moduleName = $request->getModule();
        $recordId = $request->get('record');
        if (!$recordId) {
            throw new AppException('Record ID missing');
        }

        $recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleName);
        if (!$recordModel->isEditable()) {
            throw new AppException('LBL_PERMISSION_DENIED');
        }
        return true;
    }

    public function process(Vtiger_Request $request) {
        $recordId   = $request->get('record');
        $moduleName = $request->getModule();
        $status     = $request->get('status');
        $reason     = $request->get('reason'); // only used if rejected

        $response = new Vtiger_Response();

        try {
            $recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleName);
            $recordModel->set('mode', 'edit');
            $recordModel->set('id', $recordId);

            if ($status === 'Approved') {
                $recordModel->set('approval_status', 'Approved');
                $recordModel->save();
            } elseif ($status === 'Rejected') {
                $recordModel->set('approval_status', 'Rejected');
                if (!empty($reason)) {
                    // save rejection reason into custom field cf_905
                    $recordModel->set('cf_905', $reason);
                }
                $recordModel->save();
            }

            $response->setResult([
                'success' => true,
                'message' => "Record has been $status successfully."
            ]);

        } catch (Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }

        $response->emit();
    }
}
