<?php
class BackupCore { protected $doms = array(); public $logs = array(); public $errors = array(); protected $ok = true; protected $data = array(); protected $clientVersion; protected $adminVersion; protected $finished = true; protected $cfg = array( ); protected $util = null; protected $echo = ''; protected $messages = array(); protected $base_dir = ''; protected $base_url = ''; protected $protocol = ''; protected $errorMessages = array(); protected $_log = array(); const MODE_FULL = 'full'; const MODE_MEDIUM = 'site+admin+php'; const MODE_CUSTOM = 'custom'; const MODE_UPDATE = 'update'; const MODE_SITE = 'site'; const MODE_ADMIN = 'admin'; function loadMessages() { } function __construct($updating_from = '') { $self = ( !empty($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME') ); if (empty($self)) $self = (!empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF')); if (empty($self)) { $self = str_replace("\\", "/", __FILE__); $self = explode($_SERVER["DOCUMENT_ROOT"], $self); $self = "/" . $self[1]; } $dir = preg_replace("/[\/\\\]+/i", "/", dirname($self) . "/"); if (substr($dir, 0, 1) == ".") $dir = substr($dir , 1); if ($updating_from != "") { if (preg_match('/^(.*)\/(' . $updating_from . ')\/$/', $dir, $regs)) { $dir = $regs[1]."/"; } } if (isset($_SERVER["SERVER_PROTOCOL"])) { if (preg_match("/^([^\/]*)\//i", $_SERVER["SERVER_PROTOCOL"], $regs)) { $this->protocol = strtolower($regs[1]); } } $this->base_url = $this->protocol . "://" . ( !empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME') ) . $dir; $this->base_dir = str_replace('\\', '/', realpath(dirname(__FILE__) . '/../../' ))."/"; } function setData($key, $value = "") { $this->data[$key] = $value; } function getData($key) { return (isset($this->data[$key]) ? $this->data[$key] : ""); } function log($func, $data = null) { if (!isset($this->logs[$func])) $this->logs[$func] = array(); if ($data !== null) $this->logs[$func][] = $data; } function _log($function, $message, $type = 'error', $add = true) { $key = $function . '_' . $type; if ($function == '_header' || $function == '_footer') { $place = $function; } else $place = 'log'; if (isset($this->_log[$place][$key])) { $log = $this->_log[$place][$key]; if ($add) $log['message'] .= $message; else $log['message'] = $message; } else $log = array('type' => $type, 'message' => $message); $this->_log[$place][$key] = $log; } function checkExistsClass($list) { $result = true; $error = array(); foreach($list as $class => $methods) { if (!class_exists($class)) { $result = false; $error[] = $class; } elseif ( is_array($methods) && count($methods)>0 ) { $isMethod = true; foreach($methods as $method) { if ($method != '' && !method_exists($class, $method)) { $isMethod = $result = false; } } if (!$isMethod) { $error[] = $class; } } } if (count($error) > 0) $this->_log(__FUNCTION__, Messages::get('E_CHECK_EXISTS_CLASS') . implode(',', $error)); $this->ok = $this->ok && $result; return $result; } function checkExistsFiles($list) { $result = true; $error = array(); foreach($list as $path) { if (!file_exists(MOTO_ROOT_DIR . "/" . $path) || !is_writable(MOTO_ROOT_DIR . "/" . $path)) { $result = false; $error[] = $path; } } if (count($error) > 0) $this->_log(__FUNCTION__, Messages::get('E_CHECK_EXISTS_FILES') . implode(',', $error)); $this->ok = $this->ok && $result; return $result; } function openDOM($file = "", $filename = "") { if (trim($file) == "" && trim($filename) == "") { die("error on " . __FUNCTION__); } if (trim($filename) == "") $filename = $file; if (isset($this->doms[md5(MOTO_ROOT_DIR . "/" . $filename)]) && $this->doms[md5(MOTO_ROOT_DIR . "/" . $filename)] != null) return $this->doms[md5(MOTO_ROOT_DIR . "/" . $filename)]["dom"]; if ($file == "" || !file_exists(MOTO_ROOT_DIR . "/" . $filename)) $this->doms[md5(MOTO_ROOT_DIR . "/" . $filename)] = array( "dom" => new MotoXML(), "path" => MOTO_ROOT_DIR . "/" . $filename, ); else $this->doms[md5(MOTO_ROOT_DIR . "/" . $filename)] = array( "dom" => MotoXML::create(MOTO_ROOT_DIR . "/" . $filename), "path" => MOTO_ROOT_DIR . "/" . $filename, ); return $this->doms[md5(MOTO_ROOT_DIR . "/" . $filename)]["dom"]; } public function saveXMLs() { if ($this->ok !== true) { return false; } foreach($this->doms as $i => $xml) { $xml["dom"]->save($xml["path"]); $this->doms[$i] = null; } return true; } function copyFolder2Folder($fromDir, $toDir) { $result = true; $lst = scandir($fromDir); foreach($lst as $name) if ($name != '.' && $name != '..') { if (is_dir($fromDir . '/' . $name)) { if (!is_dir($toDir . '/' . $name)) mkdir($toDir . '/' . $name, 0775, true); $result &= $this->copyFolder2Folder($fromDir . '/' . $name, $toDir . '/' . $name); } else { if (file_exists($toDir . '/' . $name)) unlink($toDir . '/' . $name); copy($fromDir . '/' . $name, $toDir . '/' . $name); } } return $result; } function initBackupParam($cfg = array()) { $param = array(); $param['timestamp'] = time(); $param['mode'] = (!empty($cfg['mode']) ? $cfg['mode'] : 'update'); $param['folder'] = $this->BACKUP_DIR . '/' . date('Ym/dhis', $param['timestamp']); $param['pathZip'] = $this->BACKUP_DIR . '/' . $this->BACKUP_ZIP_BASENAME . date($this->BACKUP_ZIP_PATTERN, $param['timestamp']) . '.zip'; $this->setSesKey('param', $param); return $param; } function getFileNamesByMode($param) { if (defined('MOBILE_WEBSITE_FOLDER') && MOBILE_WEBSITE_FOLDER != '') $addMobileFolder = true; $files = array(); if ($param->mode == self::MODE_FULL) { $files = self::scanDir(MOTO_ROOT_DIR . '/', '' , array( 'folderIn' => false, 'path.exclude' => $param->path_exclude, 'folderAdd' => false, ) , $files); $mainFolder = array('admin', 'assets', 'fonts', 'images', 'media', 'modules', 'music', 'video', 'xml'); for($if = 0; $if < count($mainFolder); $if++) { $files = self::scanDir(MOTO_ROOT_DIR . '/', $mainFolder[$if] , array( 'folderIn' => true, 'path.exclude' => $param->path_exclude, 'folderAdd' => true, ) , $files); } if (addMobileFolder) $files = self::scanDir(MOTO_ROOT_DIR . '/', MOBILE_WEBSITE_FOLDER . '/' , array( 'folderIn' => true, 'path.exclude' => $param->path_exclude, 'folderAdd' => true, ) , $files); } elseif($param->mode == self::MODE_UPDATE) { $files[] = 'index.php'; $files[] = 'config.xml'; $files[] = 'website.swf'; $files[] = 'moto.swf'; $files[] = 'style.css'; $files[] = '.htaccess'; $files = self::scanDir(MOTO_ROOT_DIR . '/', 'xml/' , array( 'folderIn' => true, 'extension.include' => array('xml'), 'path.exclude' => $param->path_exclude, 'folderAdd' => true, ) , $files); $files = self::scanDir(MOTO_ROOT_DIR . '/', 'modules/' , array( 'folderIn' => true, 'path.exclude' => $param->path_exclude, 'folderAdd' => true, ) , $files); $files = self::scanDir(MOTO_ROOT_DIR . '/', 'assets/' , array( 'folderIn' => true, 'path.exclude' => $param->path_exclude, 'folderAdd' => true, ) , $files); $files = self::scanDir(MOTO_ROOT_DIR . '/', 'slots/' , array( 'folderIn' => true, 'path.exclude' => $param->path_exclude, 'folderAdd' => true, ) , $files); $param->path_exclude[] = 'admin/libs/sfTemplating'; $param->path_exclude[] = 'admin/libs/Zend'; $param->path_exclude[] = 'admin/logs'; $files = self::scanDir(MOTO_ROOT_DIR . '/', 'admin/' , array( 'folderIn' => true, 'folderAdd' => true, 'path.exclude' => $param->path_exclude, ) , $files); $files[] = 'admin/logs'; if ($addMobileFolder) { $files[] = MOBILE_WEBSITE_FOLDER . '/config.xml'; $files[] = MOBILE_WEBSITE_FOLDER . '/admin/xml/system.xml'; $mainFolder = array(MOBILE_WEBSITE_FOLDER . '/xml'); for($if = 0; $if < count($mainFolder); $if++) { $files = $this->scanDir(MOTO_ROOT_DIR . '/', $mainFolder[$if] , array( 'folderIn' => true, 'path.exclude' => $param->path_exclude, ) , $files); } } } return $files; } public static function scanDir($root, $dir = "", $filter = array(), $ans = array()) { $rootAdd = (isset($filter["rootAdd"])? $filter["rootAdd"] : false); $folderAdd = (isset($filter["folderAdd"])? $filter["folderAdd"] : false); $subIn = (isset($filter["folderIn"])? $filter["folderIn"] : true); $justFolder = (isset($filter["justFolder"])? $filter["justFolder"] : false); $toLowerCase = (isset($filter["toLowerCase"])? $filter["toLowerCase"] : false); $md5Mode = (isset($filter["md5Mode"])? $filter["md5Mode"] : false); if (!is_dir($root . "/" . $dir)) return $ans; $lst = scandir($root . "/" . $dir); foreach($lst as $filename) if ($filename != "." && $filename != "..") { $path = ($dir != "" ? $dir . "/" :"") . $filename; $path = self::replaceSlashes($path); $add = null; if ($add !== false && isset($filter["path.exclude"])) { if (!isset($add)) $add = true; if (!is_array($filter["path.exclude"])) $filter["path.exclude"] = array($filter["path.exclude"]); foreach($filter["path.exclude"] as $pregFilter) { $pregFilter = preg_replace("/[\/\\\]+/", "\\/", $pregFilter); if ($pregFilter != "" && preg_match("/(" . $pregFilter . ")/i", $path)) { $add = false; break; } } } if ($add !== false && isset($filter["extension.exclude"]) && is_file($root . "/" . $path)) { if (!isset($add)) $add = true; if (!is_array($filter["extension.exclude"])) $filter["extension.exclude"] = array($filter["extension.exclude"]); foreach($filter["extension.exclude"] as $pregFilter) if ($pregFilter != "" && preg_match("/\.(" . $pregFilter . ")$/i", $filename)) { $add = false; break; } } if ($add !== false && isset($filter["folder.exclude"]) && is_dir($root . "/" . $path)) { if (!isset($add)) $add = true; if (!is_array($filter["folder.exclude"])) $filter["folder.exclude"] = array($filter["folder.exclude"]); foreach($filter["folder.exclude"] as $pregFilter) if (preg_match("/^(" . $pregFilter . ")$/i", $filename)) { $add = false; break; } } if ($add !== false && isset($filter["extension.include"]) && is_file($root . "/" . $path)) { $add = false; $pregFilter = (is_array($filter["extension.include"]) ? implode("|", $filter["extension.include"]) : $filter["extension.include"]); if (preg_match("/\.(" . $pregFilter . ")$/i", $filename)) { $add = true; } } if ($add !== false && isset($filter["folder.include"]) && is_dir($root . "/" . $path)) { $add = false; $pregFilter = (is_array($filter["folder.include"]) ? implode("|", $filter["folder.include"]) : $filter["folder.include"]); if (preg_match("/^(" . $pregFilter . ")$/i", $filename)) { $add = true; } } if ($add !== false && isset($filter["path.include"])) { $add = false; $pregFilter = (is_array($filter["path.include"]) ? implode("|", $filter["path.include"]) : $filter["path.include"]); if (preg_match("/(" . $pregFilter . ")/i", $path)) { $add = true; } } if (!isset($add)) $add = true; if ($add) { if ($subIn && is_dir($root . "/" . $path)) { if ($folderAdd || $justFolder === true) $ans[] = preg_replace("/[\/\\\]+/i", "/", ($rootAdd ? $root . "/" :"") . $path); $ans = self::scanDir($root, $path . "/", $filter, $ans); } elseif ($justFolder == true && is_dir($root ."/" . $path)) { $ans[] = preg_replace("/[\/\\\]+/i", "/", ($rootAdd ? $root . "/" :"") . $path); } elseif(is_file($root . "/" . $path)) $ans[] = preg_replace("/[\/\\\]+/i", "/", ($rootAdd ? $root . "/" :"") . $path); if ($toLowerCase === true && count($ans) >= 1) $ans[count($ans)-1] = strtolower($ans[count($ans)-1]); } } return $ans; } function copyFilesToFolder($files, $folder, $option = array()) { $responseVO = new ResponseVO(); $responseVO->status = new StatusVO(); $responseVO->status->status = StatusEnum::INVALID_OPERATION; $responseVO->result = null; $result = new stdClass(); $result->fileCopied = 0; $result->fileSkiped = 0; $result->fileProcessed = 0; $result->fileErrorOnCopy = 0; $result->fileSize = 0; $result->processedDir = 0; try { $folder = rtrim($folder, '/') . '/'; if (!is_dir($folder)) { if (!$this->_mkdir($folder, 0775, true)) {} } $rewrite = (isset($option['rewrite']) ? $option['rewrite'] : true); for($if = 0; $if < count($files); $if++) { if (is_file(MOTO_ROOT_DIR . '/' . $files[$if])) { $result->fileProcessed ++; $copy = false; if (!file_exists($folder . $files[$if])) $copy = true; else { if ($rewrite && is_file($folder . '/' . $files[$if])) { @unlink($folder . '/' . $files[$if]); $copy = true; } } if ($copy) { if ( !file_exists(dirname($folder . $files[$if])) ) { if (!$this->_mkdir(dirname($folder . $files[$if]), 0775, true)) { } chmod(dirname($folder . $files[$if]), 0775); } if (!copy(MOTO_ROOT_DIR . '/' . $files[$if], $folder . $files[$if])) { $result->fileErrorOnCopy ++; } else { @chmod($folder . '/' . $files[$if]); $result->fileCopied ++; } } else $result->fileSkiped ++; } elseif( is_dir(MOTO_ROOT_DIR . '/' . $files[$if]) ) { $result->processedDir ++; if (!is_dir($folder . $files[$if])) @$this->_mkdir($folder . $files[$if], 0775, true); } } $responseVO->status->status = StatusEnum::SUCCESS; } catch (Exception $e) { $responseVO->status->message = $e->getMessage(); $result = null; } $responseVO->result = $result; return $responseVO; } function createArchiveFromDirZip($dir, $zipname, $cfg = null) { $result = new stdClass(); $result->result = true; $result->files = 0; if ($cfg == null) { $cfg = new stdClass(); $cfg->maxFilePerStep = 250; } $fileList = array(); $files = self::scanDir($dir, '', array('folderAdd' => true) ); $fileAdded = 0; $zip = new ZipArchive(); $mode = ZIPARCHIVE::CREATE; if (file_exists($zipname)) $mode = ZIPARCHIVE::OVERWRITE; if ($zip->open($zipname, $mode) !== true) { $result->result = false; $result->message = 'ERROR_CANT_CREATE_ZIP'; return $result; } foreach($files as $file) { if (is_dir($dir . '/' . $file)) { if(!$zip->addEmptyDir($file)) { $result->result = false; $result->message = 'ERROR_CANT_ADD_DIR'; $zip->close(); return $result; } } else { if (!$zip->addFile($dir . '/' . $file, $file)) { $result->result = false; $result->message = 'ERROR_CANT_ADD_FILE'; $zip->close(); return $result; } } if (!$result->result) return $result; $result->files ++; $fileAdded++; if ($fileAdded > $cfg->maxFilePerStep) { $zip->close(); chmod($zipname, 0755); $zip = new ZipArchive(); if ($zip->open($zipname) !== true) { $result->result = false; $result->message = 'ERROR_CANT_REOPEN_ZIP'; unlink($zipname); break; } $fileAdded = 0; } } $zip->close(); $result->size = filesize($zipname); return $result; } function createArchiveFromDir($dir, $zipname) { $responseVO = new ResponseVO(); $responseVO->status = new StatusVO(); $responseVO->status->status = StatusEnum::INVALID_OPERATION; $responseVO->result = null; $result = new stdClass(); $result->status = false; try { if (!is_dir($dir)) { throw new Exception('ERROR_ZIP_DIR_IS_BAD'); } $result = $this->createArchiveFromDirZip($dir, $zipname); $responseVO->status->status = StatusEnum::SUCCESS; } catch (Exception $e) { $responseVO->status->message = $e->getMessage(); $result = null; } $responseVO->result = $result; return $responseVO; } public function removeFolder($dir, $removeMain = true) { if (is_array($dir)) { foreach($dir as $item) { $this->removeFolder($item, $removeMain); } return ; } $items = scandir($dir); @chmod($dir, 0775); foreach($items as $item) { if ($item != '.' && $item != '..') { if (is_dir($dir . '/' . $item)) { @chmod($dir . '/' . $item, 0775); $this->removeFolder($dir . '/' . $item, true); } else { unlink($dir . '/' . $item); } } } if ($removeMain) { @rmdir($dir); } } function _mkdir($dir, $rule = 0775, $rec = true) { return mkdir($dir, $rule, $rec); if (file_exists($dir) || is_dir($dir) || is_link($dir)) return ; $dir = preg_replace("/[\/\\\]+/", "/", $dir); $x = explode('/', $dir); $path = ''; for($i = 0; $i < count($x); $i++) { $path .= $x[$i] . '/'; if ($path == '/') continue; if (!file_exists($path) && !is_dir($path)) { mkdir($path, 0775); chmod($path, 0775); if (!is_dir($path)) { return false; } } } return true; } public static function replaceSlashes($path) { return preg_replace("/[\/\\\]+/", "/", $path); } } 