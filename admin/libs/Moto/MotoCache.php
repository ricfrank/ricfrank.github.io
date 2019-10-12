<?php
if (!defined('TEMP_LOCATION_PATH')) define('TEMP_LOCATION_PATH', MOTO_ROOT_DIR . '/admin/_tmp'); class MotoCache { const CLEANING_MODE_ALL = 'all'; const CLEANING_MODE_OLD = 'old'; const CLEANING_MODE_MATCHING_TAG = 'matchingTag'; const CLEANING_MODE_NOT_MATCHING_TAG = 'notMatchingTag'; const CLEANING_MODE_MATCHING_ANY_TAG = 'matchingAnyTag'; private $cache = null; private $enabled = null; private static $instance = null; function __construct($config = null) { if (is_null($config)) $config = MotoFrontController::getConfig(); $frontendOptions = array( 'lifetime' => (isset($config['cacheLifeTime']) ? $config['cacheLifeTime'] : 86400) ); $backendOptions = array( 'cache_dir' => TEMP_LOCATION_PATH . '/' . (isset($config['cacheDir']) ? $config['cacheDir'] : '/cache'), ); $this->enabled = (isset($config['cacheEnabled']) ? MotoUtil::toBoolean($config['cacheEnabled']) : true); if (!$this->enabled) return ; if (!file_exists($backendOptions['cache_dir'])) { if ((isset($config['cacheDirAutoCreate']) ? $config['cacheDirAutoCreate'] : true)) { MotoUtil::createDir($backendOptions['cache_dir']); } } if (!file_exists($backendOptions['cache_dir']) || !is_dir($backendOptions['cache_dir']) || !is_writable($backendOptions['cache_dir']) ) $this->enabled = false; if ($this->enabled) { $this->cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions); } } public static function getInstance($config = null) { if (is_null(self::$instance)) self::$instance = new MotoCache($config); return self::$instance; } public function isEnabled() { return $this->enabled; } public function load($id) { if (!$this->isEnabled()) return false; return $this->cache->load($id); } public function save($data, $id = null, $tags = array(), $specificLifetime = false, $priority = 8) { if (!$this->isEnabled()) return false; return $this->cache->save($data, $id, $tags, $specificLifetime, $priority); } public function remove($id) { if (!$this->isEnabled()) return false; return $this->cache->remove($id); } public function clean($mode = 'all', $tags = array(), $action = '') { $mode = 'all'; $tags = array(); if (!$this->isEnabled()) return false; return $this->cache->clean($mode, $tags); } }