<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('vtlib/Vtiger/Module.php');

$module = Vtiger_Module::getInstance('Guard');

if(!$module) {
    die("Guard module not found!");
}

// ===== 1. Approval Status Picklist =====
$field1 = new Vtiger_Field();
$field1->name = 'approval_status';
$field1->label= 'Approval Status';
$field1->uitype= 16; // picklist
$field1->column = 'approval_status';
$field1->columntype = 'VARCHAR(50)';
$field1->typeofdata = 'V~O'; // Optional
$module->addField($field1);

// Add picklist values
$picklist = new Vtiger_Picklist();
$picklist->addValues(['Pending','Accepted','Rejected'], 'approval_status', $module);

// ===== 2. Rejection Reason Textarea =====
$field2 = new Vtiger_Field();
$field2->name = 'rejection_reason';
$field2->label= 'Rejection Reason';
$field2->uitype= 19; // Textarea
$field2->column = 'rejection_reason';
$field2->columntype = 'TEXT';
$field2->typeofdata = 'V~O'; // Optional
$module->addField($field2);

// ===== 3. Badge Number Text =====
$field3 = new Vtiger_Field();
$field3->name = 'badge_no';
$field3->label= 'Badge Number1';
$field3->uitype= 1; // Text
$field3->column = 'badge_no';
$field3->columntype = 'VARCHAR(50)';
$field3->typeofdata = 'V~O'; // Optional
$module->addField($field3);

echo "Fields added successfully!";
