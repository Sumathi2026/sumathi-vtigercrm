<?php
/* Smarty version 4.5.5, created on 2025-09-25 05:38:06
  from 'C:\wamp64\www\vtigercrm\layouts\v7\modules\Potentials\DetailViewHeaderFieldsView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.5',
  'unifunc' => 'content_68d4d53edf22a7_52469989',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fbd2b8c66d47bf726791ce04e238a28c2f271fb5' => 
    array (
      0 => 'C:\\wamp64\\www\\vtigercrm\\layouts\\v7\\modules\\Potentials\\DetailViewHeaderFieldsView.tpl',
      1 => 1758778682,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68d4d53edf22a7_52469989 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['RECORD']->value->get('approval_status') == 'Pending') {?>
    <div class="btn-group">
        <button class="btn btn-success"
            onclick="Potentials_Detail_Js.prototype.approveOrReject('index.php?module=Potentials&action=ApproveOrReject&record=<?php echo $_smarty_tpl->tpl_vars['RECORD']->value->getId();?>
', 'Approved')">
            Approve
        </button>
        <button class="btn btn-danger"
            onclick="Potentials_Detail_Js.prototype.approveOrReject('index.php?module=Potentials&action=ApproveOrReject&record=<?php echo $_smarty_tpl->tpl_vars['RECORD']->value->getId();?>
', 'Rejected')">
            Reject
        </button>
    </div>
<?php }
}
}
