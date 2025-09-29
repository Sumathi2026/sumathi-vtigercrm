<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Guard Module Installation for vtiger 8.4.0<br>";
echo "=" . str_repeat("=", 50) . "<br><br>";

try {
    echo "Step 1: Setting up environment...<br>";
    set_time_limit(0);
    ini_set('memory_limit', '512M');
    chdir(dirname(__FILE__));
    echo "✓ Environment setup complete<br>";

    echo "Step 2: Checking and fixing Monolog dependency...<br>";
    // Create mock Monolog classes BEFORE loading any vtiger files
    if (!class_exists('Monolog\\Logger')) {
        echo "Creating mock Monolog classes...<br>";
       
        // Create a temporary file with the mock classes
        $mockCode = '<?php
namespace Monolog {
    class Logger {
        const EMERGENCY = 600;
        const ALERT = 550;
        const CRITICAL = 500;
        const ERROR = 400;
        const WARNING = 300;
        const NOTICE = 250;
        const INFO = 200;
        const DEBUG = 100;
       
        protected $name;
        protected $handlers = array();
       
        public function __construct($name, array $handlers = array()) {
            $this->name = $name;
            $this->handlers = $handlers;
        }
       
        public function emergency($message, array $context = array()) { return true; }
        public function alert($message, array $context = array()) { return true; }
        public function critical($message, array $context = array()) { return true; }
        public function error($message, array $context = array()) { return true; }
        public function warning($message, array $context = array()) { return true; }
        public function notice($message, array $context = array()) { return true; }
        public function info($message, array $context = array()) { return true; }
        public function debug($message, array $context = array()) { return true; }
        public function log($level, $message, array $context = array()) { return true; }
        public function addRecord($level, $message, array $context = array()) { return true; }
        public function pushHandler($handler) { return $this; }
        public function popHandler() { return null; }
        public function setHandlers(array $handlers) { return $this; }
        public function getHandlers() { return $this->handlers; }
        public function getName() { return $this->name; }
    }
}

namespace Monolog\\Handler {
    class AbstractHandler {
        public function __construct($level = 100, $bubble = true) {}
        public function handle(array $record) { return false; }
        public function handleBatch(array $records) {}
        public function isHandling(array $record) { return false; }
        public function pushProcessor($callback) { return $this; }
        public function popProcessor() { return null; }
        public function setFormatter($formatter) { return $this; }
        public function getFormatter() { return null; }
    }
   
    class StreamHandler extends AbstractHandler {
        public function __construct($stream, $level = 100, $bubble = true) {
            parent::__construct($level, $bubble);
        }
    }
}
?>';
       
        $tempFile = 'temp_monolog_mock.php';
        file_put_contents($tempFile, $mockCode);
        require_once($tempFile);
        unlink($tempFile); // Clean up
       
        echo "✓ Mock Monolog classes created<br>";
    }

    echo "Step 3: Loading config.inc.php...<br>";
    if (file_exists('config.inc.php')) {
        require_once('config.inc.php');
        echo "✓ Config loaded<br>";
    } else {
        throw new Exception("config.inc.php not found");
    }

    echo "Step 4: Loading Composer autoloader (if available)...<br>";
    if (file_exists('vendor/autoload.php')) {
        require_once('vendor/autoload.php');
        echo "✓ Composer autoloader loaded<br>";
    } else {
        echo "⚠ Composer autoloader not found - using mock classes<br>";
    }

    echo "Step 5: Loading minimal vtiger core...<br>";
    // Load database first
    if (file_exists('include/database/PearDatabase.php')) {
        require_once('include/database/PearDatabase.php');
        echo "✓ Database class loaded<br>";
    }

    echo "Step 6: Loading Vtiger_Module class...<br>";
    if (file_exists('vtlib/Vtiger/Module.php')) {
        require_once('vtlib/Vtiger/Module.php');
        echo "✓ Vtiger_Module class loaded<br>";
    } else {
        throw new Exception("vtlib/Vtiger/Module.php not found");
    }

    echo "Step 7: Creating module instance...<br>";
    $module = new Vtiger_Module();
    echo "✓ Module instance created<br>";

    echo "Step 8: Setting module name and saving...<br>";
    $module->name = 'Guard';
    $module->save();
    echo "✓ Module saved with ID: " . $module->id . "<br>";

    echo "Step 9: Initializing tables...<br>";
    $module->initTables();
    echo "✓ Tables initialized<br>";

    echo "Step 10: Initializing webservice...<br>";
    $module->initWebservice();
    echo "✓ Webservice initialized<br>";

    echo "Step 11: Loading Menu class...<br>";
    require_once('vtlib/Vtiger/Menu.php');
    echo "✓ Menu class loaded<br>";

    echo "Step 12: Adding to menu...<br>";
    try {
        $menu = Vtiger_Menu::getInstance('MARKETING');
        if ($menu) {
            $menu->addModule($module);
            echo "✓ Added to Marketing menu<br>";
        } else {
            echo "⚠ Marketing menu not found, trying other menus...<br>";
            $menus = ['INVENTORY', 'SALES', 'SUPPORT', 'TOOLS'];
            $added = false;
            foreach ($menus as $menuName) {
                try {
                    $menu = Vtiger_Menu::getInstance($menuName);
                    if ($menu) {
                        $menu->addModule($module);
                        echo "✓ Added to $menuName menu<br>";
                        $added = true;
                        break;
                    }
                } catch (Exception $e) {
                    echo "  ⚠ $menuName menu not available<br>";
                }
            }
            if (!$added) {
                echo "⚠ Could not add to any menu - module still created<br>";
            }
        }
    } catch (Exception $e) {
        echo "⚠ Menu assignment failed: " . $e->getMessage() . "<br>";
    }

    echo "Step 13: Loading Block class...<br>";
    require_once('vtlib/Vtiger/Block.php');
    echo "✓ Block class loaded<br>";

    echo "Step 14: Creating block...<br>";
    $block1 = new Vtiger_Block();
    $block1->label = 'Guard Information';
    $module->addBlock($block1);
    echo "✓ Block created<br>";

    echo "Step 15: Loading Field class...<br>";
    require_once('vtlib/Vtiger/Field.php');
    echo "✓ Field class loaded<br>";

    echo "Step 16: Adding fields...<br>";
   
    // Guard Name (Entity Identifier)
    $field0 = new Vtiger_Field();
    $field0->name = 'guard_name';
    $field0->label = 'Guard Name';
    $field0->table = $module->basetable;
    $field0->column = 'guard_name';
    $field0->columntype = 'VARCHAR(100)';
    $field0->uitype = 2;
    $field0->typeofdata = 'V~M';
    $module->setEntityIdentifier($field0);
    $block1->addField($field0);
    echo "  ✓ Guard Name (Entity Identifier)<br>";

    // Guard Number
    $field1 = new Vtiger_Field();
    $field1->name = 'guard_no';
    $field1->label = 'Guard No';
    $field1->table = $module->basetable;
    $field1->column = 'guard_no';
    $field1->columntype = 'VARCHAR(100)';
    $field1->uitype = 4;
    $field1->typeofdata = 'V~O';
    $block1->addField($field1);
    echo "  ✓ Guard No<br>";

    // Phone
    $field8 = new Vtiger_Field();
    $field8->name = 'guard_mobile';
    $field8->label = 'Phone No';
    $field8->table = $module->basetable;
    $field8->column = 'guard_mobile';
    $field8->columntype = 'VARCHAR(100)';
    $field8->uitype = 11;
    $field8->typeofdata = 'V~O';
    $block1->addField($field8);
    echo "  ✓ Phone<br>";

    // Image
    $field9 = new Vtiger_Field();
    $field9->name = 'guard_img';
    $field9->label = 'Image';
    $field9->table = $module->basetable;
    $field9->column = 'guard_img';
    $field9->columntype = 'VARCHAR(255)';
    $field9->uitype = 69;
    $field9->typeofdata = 'V~O';
    $block1->addField($field9);
    echo "  ✓ Image<br>";

    // System fields
    $mfield2 = new Vtiger_Field();
    $mfield2->name = 'createdtime';
    $mfield2->label = 'Created Time';
    $mfield2->table = 'vtiger_crmentity';
    $mfield2->column = 'createdtime';
    $mfield2->uitype = 70;
    $mfield2->typeofdata = 'DT~O';
    $mfield2->displaytype = 2;
    $block1->addField($mfield2);
    echo "  ✓ Created Time<br>";

    $mfield3 = new Vtiger_Field();
    $mfield3->name = 'modifiedtime';
    $mfield3->label = 'Modified Time';
    $mfield3->table = 'vtiger_crmentity';
    $mfield3->column = 'modifiedtime';
    $mfield3->uitype = 70;
    $mfield3->typeofdata = 'DT~O';
    $mfield3->displaytype = 2;
    $block1->addField($mfield3);
    echo "  ✓ Modified Time<br>";

    $field2 = new Vtiger_Field();
    $field2->name = 'assigned_user_id';
    $field2->label = 'Assigned To';
    $field2->table = 'vtiger_crmentity';
    $field2->column = 'smownerid';
    $field2->columntype = 'int(19)';
    $field2->uitype = 53;
    $field2->typeofdata = 'V~M';
    $block1->addField($field2);
    echo "  ✓ Assigned To<br>";

    echo "Step 17: Creating filter...<br>";
    require_once('vtlib/Vtiger/Filter.php');
    $filter1 = new Vtiger_Filter();
    $filter1->name = 'All';
    $filter1->isdefault = true;
    $module->addFilter($filter1);
    $filter1->addField($field0, 1);
    $filter1->addField($field1, 2);
    $filter1->addField($field2, 3);
    echo "✓ Filter created<br>";

    echo "Step 18: Setting permissions...<br>";
    $module->setDefaultSharing('Private');
    $module->enableTools(Array('Import', 'Export'));
    $module->disableTools('Merge');
    echo "✓ Permissions set<br>";

    echo "<br>" . str_repeat("=", 50) . "<br>";
    echo "<h3 style='color: green;'>🎉 SUCCESS!</h3>";
    echo "<strong>Guard module has been created successfully!</strong><br><br>";
   
    echo "<strong>Module Details:</strong><br>";
    echo "• Name: Guard<br>";
    echo "• ID: " . $module->id . "<br>";
    echo "• Base Table: " . $module->basetable . "<br>";
    echo "• Fields: Guard Name, Guard No, Phone No, Image, System Fields<br><br>";
   
    echo "<strong>Next Steps:</strong><br>";
    echo "<a href='index.php?module=Guard&view=List' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>📋 Open Guard Module</a>";
    echo "<a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🏠 Back to Home</a><br>";

} catch (Exception $e) {
    echo "<br>" . str_repeat("=", 50) . "<br>";
    echo "<h3 style='color: red;'>❌ ERROR</h3>";
    echo "<strong>Error Message:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>File:</strong> " . $e->getFile() . "<br>";
    echo "<strong>Line:</strong> " . $e->getLine() . "<br><br>";
    echo "<details><summary>Click to view stack trace</summary>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px;'>" . $e->getTraceAsString() . "</pre>";
    echo "</details>";
} catch (Error $e) {
    echo "<br>" . str_repeat("=", 50) . "<br>";
    echo "<h3 style='color: red;'>❌ FATAL ERROR</h3>";
    echo "<strong>Error Message:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>File:</strong> " . $e->getFile() . "<br>";
    echo "<strong>Line:</strong> " . $e->getLine() . "<br>";
}

echo "<br><br><div style='background: #e9ecef; padding: 15px; border-radius: 5px;'>";
echo "<strong>💡 Note:</strong> If you encounter Monolog errors in the future, install Composer dependencies:<br>";
echo "<code style='background: #343a40; color: #f8f9fa; padding: 5px 10px; border-radius: 3px;'>composer install</code>";
echo "</div>";
?>
