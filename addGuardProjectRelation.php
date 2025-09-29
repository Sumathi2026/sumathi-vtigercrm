<?php
// Guard ↔ Project Relation Setup (vtlib)
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>Guard ↔ Project Relation Setup (vtlib)</h2>";

require_once('includes/main/WebUI.php');

global $adb;

// 1. Load Guard module
$guardModule = Vtiger_Module::getInstance('Guard');
if (!$guardModule) {
    echo "<p style='color:red'>❌ Guard module not found</p>";
    exit;
}
echo "<p>✓ Guard module loaded</p>";

// 2. Load Project module
$projectModule = Vtiger_Module::getInstance('Project');
if (!$projectModule) {
    echo "<p style='color:red'>❌ Project module not found</p>";
    exit;
}
echo "<p>✓ Project module loaded</p>";

// 3. Load Guard Information block
$blockInstance = Vtiger_Block::getInstance('Guard Information', $guardModule);
if (!$blockInstance) {
    echo "<p style='color:red'>❌ Guard Information block not found</p>";
    exit;
}
echo "<p>✓ Guard Information block loaded</p>";

// 4. Add field projectid in Guard (if not exists)
$fieldInstance = Vtiger_Field::getInstance('projectid', $guardModule);
if (!$fieldInstance) {
    $field = new Vtiger_Field();
    $field->name = 'projectid';
    $field->label = 'Project';
    $field->table = $guardModule->basetable;
    $field->column = 'projectid';
    $field->uitype = 10; // Reference field
    $field->typeofdata = 'I~O';
    $field->columntype = 'INT(11)';
    $field->summaryfield = 1;
    $blockInstance->addField($field);

    // Link field with Project module
    $field->setRelatedModules(['Project']);
    echo "<p>✓ projectid field created in Guard</p>";
} else {
    echo "<p style='color:orange'>⚠ projectid field already exists in Guard</p>";
}

// 5. Add reverse relation Project → Guard
$projectModule->setRelatedList(
    $guardModule,
    'Guard',
    ['ADD'],
    'get_dependents_list'
);
echo "<p>✓ Reverse relation Project → Guard created</p>";

echo "<hr><p style='color:green'><strong>✅ DONE — Guard ↔ Project relation setup complete</strong></p>";
