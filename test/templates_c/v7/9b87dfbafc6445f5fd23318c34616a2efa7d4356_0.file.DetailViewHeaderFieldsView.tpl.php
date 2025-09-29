<?php
/* Smarty version 4.5.5, created on 2025-09-26 10:06:19
  from 'C:\wamp64\www\vtigercrm\layouts\v7\modules\Guard\DetailViewHeaderFieldsView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.5',
  'unifunc' => 'content_68d6659b0f3b52_59339762',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9b87dfbafc6445f5fd23318c34616a2efa7d4356' => 
    array (
      0 => 'C:\\wamp64\\www\\vtigercrm\\layouts\\v7\\modules\\Guard\\DetailViewHeaderFieldsView.tpl',
      1 => 1758881031,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68d6659b0f3b52_59339762 (Smarty_Internal_Template $_smarty_tpl) {
?>
<form id="headerForm" method="POST"><?php $_smarty_tpl->_assignInScope('FIELDS_MODELS_LIST', $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getFields());
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['FIELDS_MODELS_LIST']->value, 'FIELD_MODEL');
$_smarty_tpl->tpl_vars['FIELD_MODEL']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['FIELD_MODEL']->value) {
$_smarty_tpl->tpl_vars['FIELD_MODEL']->do_else = false;
$_smarty_tpl->_assignInScope('FIELD_DATA_TYPE', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType());
$_smarty_tpl->_assignInScope('FIELD_NAME', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getName());
if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->isHeaderField() && $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->isActiveField() && $_smarty_tpl->tpl_vars['RECORD']->value->get($_smarty_tpl->tpl_vars['FIELD_NAME']->value) && $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->isViewable()) {
ob_start();
echo $_smarty_tpl->tpl_vars['FIELD_NAME']->value;
$_prefixVariable1 = ob_get_clean();
$_smarty_tpl->_assignInScope('FIELD_MODEL', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->set('fieldvalue',$_smarty_tpl->tpl_vars['RECORD']->value->get($_prefixVariable1)));?><div class="info-row row headerAjaxEdit td"><div class="col-lg-7 fieldLabel"><?php $_smarty_tpl->_assignInScope('DISPLAY_VALUE', ((string)$_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['RECORD']->value->get($_smarty_tpl->tpl_vars['FIELD_NAME']->value))));?><span class="<?php echo $_smarty_tpl->tpl_vars['FIELD_NAME']->value;?>
 value" title="<?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('label'),$_smarty_tpl->tpl_vars['MODULE']->value);?>
 : <?php echo strip_tags($_smarty_tpl->tpl_vars['DISPLAY_VALUE']->value);?>
"><?php $_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getUITypeModel()->getDetailViewTemplateName(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('FIELD_MODEL'=>$_smarty_tpl->tpl_vars['FIELD_MODEL']->value,'MODULE'=>$_smarty_tpl->tpl_vars['MODULE_NAME']->value,'RECORD'=>$_smarty_tpl->tpl_vars['RECORD']->value), 0, true);
?></span><?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->isEditable() == 'true' && $_smarty_tpl->tpl_vars['LIST_PREVIEW']->value != 'true' && $_smarty_tpl->tpl_vars['IS_AJAX_ENABLED']->value == 'true') {?><span class="hide edit"><?php if ($_smarty_tpl->tpl_vars['FIELD_DATA_TYPE']->value == 'multipicklist') {?><input type="hidden" class="fieldBasicData" data-name='<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
[]' data-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" data-displayvalue='<?php echo Vtiger_Util_Helper::toSafeHTML($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue')));?>
' data-value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue');?>
" /><?php } else { ?><input type="hidden" class="fieldBasicData" data-name='<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
' data-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" data-displayvalue='<?php echo Vtiger_Util_Helper::toSafeHTML($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue')));?>
' data-value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue');?>
" /><?php }?></span><span class="action"><a href="index.php?module=<?php echo $_smarty_tpl->tpl_vars['MODULE_NAME']->value;?>
&view=Edit&record=<?php echo $_smarty_tpl->tpl_vars['RECORD']->value->getId();?>
" class="fa fa-pencil" title="Edit"></a></span><?php }?></div></div><?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
$_smarty_tpl->_assignInScope('APPROVAL_STATUS', $_smarty_tpl->tpl_vars['RECORD']->value->get('cf_915'));?><div class="info-row row headerAjaxEdit td"><div class="col-lg-7 fieldLabel"><span class="value"><b>Approval Status : </b> <?php echo (($tmp = $_smarty_tpl->tpl_vars['APPROVAL_STATUS']->value ?? null)===null||$tmp==='' ? "Pending" ?? null : $tmp);?>
</span></div></div></form><?php }
}
