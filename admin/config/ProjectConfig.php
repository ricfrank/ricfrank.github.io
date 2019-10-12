<?php

class MotoConfig
{
    const INI_FILE_NAME = 'ProjectConfig.ini';
    static protected $__data = array();
    static protected $__instance = null;

    protected function __construct()
    {
        $fileName = dirname(__FILE__) . '/' . self::INI_FILE_NAME;
        if (file_exists($fileName)) $this->loadIniFile($fileName);
    }

    static public function init()
    {
        if (self::$__instance == null) {
            self::$__instance = new self();
        }
    }

    static public function getInstance()
    {
        if (self::$__instance == null) {
            self::$__instance = new self();
        }
        return self::$__instance;
    }

    static public function get($key, $default = null)
    {
        $key = strtolower($key);
        if (isset(self::$__data[$key])) return self::$__data[$key];
        return $default;
    }

    static public function set($key, $value)
    {
        $key = strtolower($key);
        self::$__data[$key] = $value;
    }

    public function loadIniFile($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }
        $data = file($filename);
        for ($i = 0; $i < count($data); $i++) {
            if (preg_match("/^([a-z_]+) = ([^;]+);?/i", trim($data[$i]), $match)) {
                $key = strtolower($match[1]);
                if (!isset(self::$__data[$key])) self::$__data[$key] = $match[2];
            }
        }
    }

    static function _get()
    {
        return self::$__data;
    }
}

MotoConfig::init();
if (function_exists('ini_get') && function_exists('ini_set')) {
    if (ini_get('date.timezone') == '') @ini_set('date.timezone', 'UTC');
    @ini_set('session.use_trans_sid', 0);
}
if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set')) {
    $defaultTimezone = date_default_timezone_get();
    if ($defaultTimezone == '') $defaultTimezone = 'UTC';
    date_default_timezone_set($defaultTimezone);
}
$MOTO_ROOT_DIR = trim(str_replace('\\', '/', realpath(dirname(__FILE__) . '/../..')));
$MOTO_ADMIN_DIR = trim(str_replace('\\', '/', realpath(dirname(__FILE__) . '/..')));
if ($MOTO_ROOT_DIR == '') $MOTO_ADMIN_DIR = dirname(dirname(__FILE__));
if ($MOTO_ROOT_DIR == '') $MOTO_ROOT_DIR = dirname(dirname(dirname(__FILE__)));
$MOTO_ROOT_DIR = MotoConfig::get('MOTO_ROOT_DIR', $MOTO_ROOT_DIR);
$MOTO_ADMIN_DIR = MotoConfig::get('MOTO_ADMIN_DIR', $MOTO_ADMIN_DIR);
if (!defined('MOTO_ROOT_DIR')) define('MOTO_ROOT_DIR', $MOTO_ROOT_DIR);
if (!defined('MOTO_ADMIN_DIR')) define('MOTO_ADMIN_DIR', $MOTO_ADMIN_DIR);
$relative_path = !empty($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME');
$absolute_path = str_replace('\\', '/', realpath(basename($relative_path)));
$doc_root = substr($absolute_path, 0, strpos($absolute_path, $relative_path));
$doc_root = MotoConfig::get('MOTO_DOCUMENT_ROOT', $doc_root);
define('MOTO_DOCUMENT_ROOT', $doc_root);
$MOTO_ADMIN_URL = $MOTO_ROOT_URL = '';
if ($doc_root != "") {
    $MOTO_ROOT_URL = '/' . substr(MOTO_ROOT_DIR, strpos(MOTO_ROOT_DIR, $doc_root) + strlen($doc_root) + 1);
    $MOTO_ADMIN_URL = '/' . substr(MOTO_ADMIN_DIR, strpos(MOTO_ADMIN_DIR, $doc_root) + strlen($doc_root) + 1);
} else {
    if (preg_match("/^(.*)\/admin\/?(.*)/i", $relative_path, $match)) {
        $MOTO_ROOT_URL = str_replace("//", "/", '/' . $match[1]);
    } elseif (preg_match("/^(.*)\/modules\/?(.*)/i", $relative_path, $match)) {
        $MOTO_ROOT_URL = str_replace("//", "/", '/' . $match[1]);
    } else {
        $info = pathinfo($relative_path);
        $MOTO_ROOT_URL = str_replace("//", "/", str_replace("\\", "/", '/' . $info["dirname"]));
    }
    $MOTO_ADMIN_URL = str_replace("//", "/", $MOTO_ROOT_URL . "/admin/");
}
$MOTO_ROOT_URL = MotoConfig::get('MOTO_ROOT_URL', $MOTO_ROOT_URL);
$MOTO_ADMIN_URL = MotoConfig::get('MOTO_ADMIN_URL', $MOTO_ADMIN_URL);
if (strlen($MOTO_ROOT_URL) && $MOTO_ROOT_URL[0] !== '/') {
    $MOTO_ROOT_URL = '/' . $MOTO_ROOT_URL;
}
$MOTO_ROOT_URL = rtrim($MOTO_ROOT_URL, '/') . '/';
MotoConfig::set('websiteBasePath', $MOTO_ROOT_URL);
MotoConfig::set('websiteRootPath', $MOTO_ROOT_URL);
MotoConfig::set('websiteContentFolder', '');
MotoConfig::set('websiteBaseDir', $MOTO_ROOT_DIR);
MotoConfig::set('websiteRootDir', $MOTO_ROOT_DIR);
if (isset($_GET['folder']) && preg_match('/^[a-z0-9]+$/i', $_GET['folder'])) {
    MotoConfig::set('websiteContentFolder', $_GET['folder']);
    MotoConfig::set('websiteRootPath', MotoConfig::get('websiteRootPath') . $_GET['folder'] . '/');
    MotoConfig::set('websiteRootDir', MotoConfig::get('websiteRootDir') . '/' . $_GET['folder'] . '/');
}
if (!defined('MOTO_ROOT_URL')) define('MOTO_ROOT_URL', $MOTO_ROOT_URL);
if (!defined('MOTO_ADMIN_URL')) define('MOTO_ADMIN_URL', $MOTO_ADMIN_URL);
if (isset($_GET['folder']) && preg_match('/^[a-z0-9]+$/i', $_GET['folder'])) {
    if (!defined('CONTROL_PANEL_SYSTEM')) {
        define('CONTROL_PANEL_SYSTEM', MotoConfig::get('websiteRootDir') . '/xml/system.xml');
    }
}
$MOTO_PHP_VERSION = substr(PHP_VERSION, 0, 3);
$MOTO_MULTI_ENABLED = false;
$MOTO_MULTI_LIBS_PREFIX = '';
$MOTO_LIBS_DIR = $MOTO_ADMIN_DIR . '/libs/Moto/';

if (!defined('MOTO_MULTI_ENABLED')) define('MOTO_MULTI_ENABLED', $MOTO_MULTI_ENABLED);
if (!defined('MOTO_PHP_VERSION')) define('MOTO_PHP_VERSION', $MOTO_PHP_VERSION);
if (!defined('MOTO_MULTI_LIBS_PREFIX')) define('MOTO_MULTI_LIBS_PREFIX', $MOTO_MULTI_LIBS_PREFIX);
set_include_path('.' . PATH_SEPARATOR . $MOTO_ADMIN_DIR . '/libs/' . (defined('MOTO_MULTI_LIBS_PREFIX') ? MOTO_MULTI_LIBS_PREFIX . '/' : '') . PATH_SEPARATOR . $MOTO_ADMIN_DIR . '/libs/' . (defined('MOTO_MULTI_LIBS_PREFIX') ? MOTO_MULTI_LIBS_PREFIX . '/' : '') . 'Moto/' . PATH_SEPARATOR . $MOTO_ADMIN_DIR . '/libs/' . (defined('MOTO_MULTI_LIBS_PREFIX') ? MOTO_MULTI_LIBS_PREFIX . '/' : '') . 'Moto/services/' . PATH_SEPARATOR . $MOTO_ADMIN_DIR . '/libs/' . (defined('MOTO_MULTI_LIBS_PREFIX') ? MOTO_MULTI_LIBS_PREFIX . '/' : '') . 'Moto/services/vo/' . PATH_SEPARATOR . $MOTO_ADMIN_DIR . '/libs/' . PATH_SEPARATOR . $MOTO_ADMIN_DIR . '/libs/sfTemplating/' . PATH_SEPARATOR . get_include_path());
$inp = (isset($_POST["subAction"]) ? $_POST : (isset($_GET["subAction"]) ? $_GET : $_REQUEST));
if (isset($inp["subAction"]) && $inp["subAction"] == "showInfoAboutFile") {
    define("MotoRegistryFileRunnerEnabled", true);
}

class ProjectConfig
{
    const COOKIE_NAME = "moto_cms";
    private static $loaded = array();

    public static function showInfoAboutFile($param = array())
    {
        if (!defined("MotoRegistryFileRunnerEnabled")) define("MotoRegistryFileRunnerEnabled", true);
        global $MotoRegistryFileRunner;
        if (!isset($MotoRegistryFileRunner)) $MotoRegistryFileRunner = array();
        if (count($param) == 0) $param = $_REQUEST;
        self::registerAutoload();
        self::loadConstants();
        $list = array("index.php", "admin/index.php", "contact.php", "admin/gateway.php", "website.swf", "moto.swf",);
        $list = MotoUtil::scanDir(MOTO_ROOT_DIR, "admin/", array("extension.include" => array("swf"), "folderIn" => false,), $list);
        $list = MotoUtil::scanDir(MOTO_ROOT_DIR, "modules/", array("extension.include" => array("swf", "php"),), $list);
        $list = MotoUtil::scanDir(MOTO_ROOT_DIR, "admin/actions/", array("extension.include" => array("php")), $list);
        $list = MotoUtil::scanDir(MOTO_ROOT_DIR, "admin/libs/Moto/", array("extension.include" => array("php")), $list);
        $list = MotoUtil::scanDir(MOTO_ROOT_DIR, "admin/modules/", array("extension.include" => array("swf", "php")), $list);
        foreach ($list as $i => &$filename) if (!is_dir(MOTO_ROOT_DIR . "/" . $filename)) {
            $in = @fopen(MOTO_ROOT_DIR . "/" . $filename, "r");
            if (!$in) continue;
            $info = pathinfo($filename);
            $found = false;
            if ($info['extension'] == 'php') {
                $str = fgets($in);
                if (preg_match("/<\?php \@Zend;/i", $str)) {
                    fclose($in);
                    include_once(MOTO_ROOT_DIR . "/" . $filename);
                    $found = true;
                } else {
                    for ($j = 0; $j < 50; $j++) {
                        $str .= fgets($in);
                        if (feof($in)) break;
                    }
                    fclose($in);
                }
            }
            if (!$found) {
                $MotoRegistryFileRunner[md5(strtolower($filename))] = array('path' => $filename, 'md5file' => md5_file(MOTO_ROOT_DIR . "/" . $filename), 'sha1file' => sha1_file(MOTO_ROOT_DIR . "/" . $filename), 'filesize' => filesize(MOTO_ROOT_DIR . "/" . $filename), 'version' => '', 'filemodifed' => '',);
            }
        }
        foreach ($MotoRegistryFileRunner as $code => &$info) if (!is_array($info)) $info = unserialize(base64_decode($info));
        if (!isset($param["output"]) || $param["output"] == "array") return $MotoRegistryFileRunner;
        if ($param["output"] == "xml") {
            $xml = '';
            $xml .= '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<curentData>' . "\n";
            $xml .= '<files>' . "\n";
            foreach ($MotoRegistryFileRunner as $code => $info) {
                $xml .= '<file';
                $xml .= ' md5="' . $info['md5file'] . '"';
                $xml .= ' sha1="' . (isset($info['sha1file']) ? $info['sha1file'] : '') . '"';
                $xml .= ' version="' . $info["version"] . '"';
                $xml .= ' filemodifed="' . $info["filemodifed"] . '"';
                $xml .= ' filesize="' . $info["filesize"] . '"';
                $xml .= '>';
                $xml .= '<![CDATA[' . $info["path"] . ']]>';
                $xml .= '</file>' . "\n";
            }
            $xml .= '</files>' . "\n";
            $xml .= '</curentData>' . "\n";
            header("Content-Type: application/xml");
            echo $xml;
            exit;
        }
        echo "<pre>";
        print_r($MotoRegistryFileRunner);
        exit;
    }

    public static function setup()
    {
        self::registerAutoload();
        self::loadConstants();
        self::startSession();
        self::initLog();
        self::initErrorHandlers();
    }

    public static function initLog()
    {
        if (isset(self::$loaded[__FUNCTION__])) return;
        self::$loaded[__FUNCTION__] = true;
        MotoLog::getInstance()->addWriter(new Zend_Log_Writer_Stream(MOTO_ADMIN_DIR . '/logs/moto.log'));
        if (class_exists('MotoLogFilter')) {
            MotoLog::getInstance()->addFilter(new MotoLogFilter());
        }
    }

    public static function registerAutoload()
    {
        if (isset(self::$loaded[__FUNCTION__])) return;
        self::$loaded[__FUNCTION__] = true;
        require_once 'Zend/Loader.php';
        Zend_Loader::registerAutoload();
    }

    public static function initErrorHandlers()
    {
        if (isset(self::$loaded[__FUNCTION__])) return;
        self::$loaded[__FUNCTION__] = true;
        error_reporting(E_ALL);
        @ini_set('display_errors', 'off');
        @ini_set('log_errors', 'on');
        @ini_set('error_log', MOTO_ADMIN_DIR . '/logs/php_error.log');
        MotoError::registerHandlers();
    }

    public static function startSession()
    {
        if (isset(self::$loaded[__FUNCTION__])) return;
        self::$loaded[__FUNCTION__] = true;
        $COOKIE_NAME = MotoConfig::get('COOKIE_NAME', self::COOKIE_NAME);
        if (!is_string($COOKIE_NAME) || $COOKIE_NAME == '') $COOKIE_NAME = self::COOKIE_NAME;
        if (defined("CONFIG_COOKIE_MODE") && (strtolower(CONFIG_COOKIE_MODE) == "first" || strtolower(CONFIG_COOKIE_MODE) == "full") && isset($_COOKIE) && isset($_COOKIE[$COOKIE_NAME])) session_id($_COOKIE[$COOKIE_NAME]);
        session_name($COOKIE_NAME);
        $isSetPath = MotoConfig::get('COOKIE_IS_SET_PATH', true);
        $cookiePath = str_replace('//', '/', MOTO_ROOT_URL . '/');
        $cookiePath = MotoConfig::get('COOKIE_SET_PATH', $cookiePath);
        if ($isSetPath) @ini_set('session.cookie_path', $cookiePath); else $cookiePath = '/';
        $startSession = MotoConfig::get('COOKIE_IS_START_SESSION', !isset($_SESSION));
        if ($startSession) session_start();
        if (defined("CONFIG_COOKIE_MODE") && (strtolower(CONFIG_COOKIE_MODE) == "second" || strtolower(CONFIG_COOKIE_MODE) == "full")) setcookie(session_name(), session_id(), time() + 3600 * 24 * 7, $cookiePath);
    }

    public static function loadConstants()
    {
        $customFolder = (isset($_GET['folder']) && preg_match('/^[a-z0-9]+$/i', $_GET['folder']) ? $_GET['folder'] . '/' : '');
        if (isset(self::$loaded[__FUNCTION__])) return;
        self::$loaded[__FUNCTION__] = true;
        $configFiles = array(array('file_path' => MOTO_ROOT_DIR . '/config.xml', 'value_prefix' => MOTO_ADMIN_DIR . '/../'), array('file_path' => MOTO_ADMIN_DIR . '/config.xml', 'value_prefix' => ''));
        $mobileHooks = array("CONFIG_RESOURSE_PATH", "CONTENT_RESOURSE_PATH");
        foreach ($configFiles as $config) {
            $dom = new DomDocument();
            $dom->load($config['file_path']);
            $items = $dom->getElementsByTagName('item');
            foreach ($items as $item) {
                $itemName = $item->getAttribute('name');
                if (strlen($itemName) > 0) {
                    $value = $item->nodeValue;
                    if (substr($itemName, -5) == '_PATH') {
                        if (in_array($itemName, $mobileHooks)) {
                            $value = $config['value_prefix'] . $customFolder . $item->nodeValue;
                        } else {
                            $value = $config['value_prefix'] . $item->nodeValue;
                        }
                    } elseif (substr($itemName, -11) == '_PERMISSION') {
                        $value = octdec($value);
                    }
                    if ($itemName == 'CONFIG_RESOURSE_PATH' && strpos($value, '?') > 0) $value = substr($value, 0, strpos($value, '?'));
                    if (!defined($itemName)) define($itemName, $value);
                }
            }
        }
        if (!defined("CONTROL_PANEL_COMPANY_EMAIL")) define("CONTROL_PANEL_COMPANY_EMAIL", 'support@cms-guide.com');
        if (!defined("CONTROL_PANEL_COMPANY_EMAIL_NOREPLY")) define("CONTROL_PANEL_COMPANY_EMAIL_NOREPLY", 'noreply@cms-guide.com');
        if (!defined("CONTROL_PANEL_COMPANY_NAME")) define("CONTROL_PANEL_COMPANY_NAME", "Moto CMS");
        if (!defined("CONTROL_PANEL_COMPANY_URL")) define("CONTROL_PANEL_COMPANY_URL", "http://www.motocms.com/");
        if (!defined('DEMO_MODE')) define('DEMO_MODE', 'false');
        if (!defined('MOTO_DIR_PERMISSION')) define('MOTO_DIR_PERMISSION', 0775);
        if (!defined('MOTO_FILE_PERMISSION')) define('MOTO_FILE_PERMISSION', 0664);
    }
}

unset($relative_path);
unset($absolute_path);
unset($doc_root);