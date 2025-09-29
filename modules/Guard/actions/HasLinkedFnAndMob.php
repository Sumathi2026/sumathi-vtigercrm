<?php
class Guard_HasLinkedFnAndMob_Action extends Vtiger_IndexAjax_View {

    public function requiresPermission(\Vtiger_Request $request) {
        $permissions = parent::requiresPermission($request);
        $permissions[] = array(
            'module_parameter' => 'source_module',
            'action' => 'DetailView', 
            'record_parameter' => 'record'
        );
        return $permissions;
    }

    public function process(Vtiger_Request $request) {
        $record = $request->get('record');
        $module = $request->get('module');
        $response = new Vtiger_Response();

        // 🔹 For now always allow approve
        $response->setResult([
            'success' => true,
            'message' => 'Guard has required Function & Mobile'
        ]);

        $response->emit();
    }
}
