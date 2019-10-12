<?php
 class Moto_Html_Content { protected $_data = array(); protected $_content = null; protected $_structure = null; protected $_holders = array(); protected $_options = array(); public $website = null; public $layout = null; public $page = null; public $popup = null; protected $_type = 'page'; function __construct($options = null) { $this->setOptions($options); } function __set($name, $value) { $this->_data[$name] = $value; } function __isset($name) { return (isset($this->_data[$name])); } function __get($name) { return ( isset($this->_data[$name]) ? $this->_data[$name] : null ); } function setOptions($options) { if (is_object($options) || is_array($options)) foreach($options as $name => $value) $this->_options[$name] = $value; } function setOption($name, $value) { $this->_options[$name] = $value; } function getOption($name, $value = null) { return ( isset($this->_options[$name]) ? $this->_options[$name] : $value ); } function setDomContent($dom) { $this->_content = $dom; return $this; } function setDomStructure($dom) { $this->_structure = $dom; return $this; } function setPage($page, $statusCode = 200) { AbstractVO::setDefaultOption('PageVO.loaderElements', true); AbstractVO::setDefaultOption('PopupVO.loaderElements', true); if ($statusCode != 401 && method_exists($page, 'loadContentElements')) $page->loadContentElements(); if ($page instanceof PageVO) $this->page = $page; else { $this->setOption('load.layout', false); $this->popup = $page; $this->_type = 'popup'; } return $this; } protected function _loadWebsite() { $node = MotoXML::findOneByXPath('./website', $this->_content); if ($node != null) { $this->website = new WebsiteVO($node); $holders = array(); foreach($this->website->holders as $holder) { $this->_holders[$holder->id] = $holders[$holder->id] = $holder; } $this->website->holders = $holders; if (!is_null($node = MotoXML::findOneByXPath('.//website', $this->_structure))) { $holders = ContentHolderVO::findAll($node); if ($holders != null) foreach($holders as $holder) { $this->_holders[$holder->id] = $this->website->holders[$holder->id] = $holder; } } } } protected function _loadLayout() { $node = MotoXML::findOneByXPath('./layouts/layout[@layoutType="' . $this->page->layoutTypeId . '"]', $this->_content); if ($node != null) { $this->layout = new LayoutVO($node); $holders = array(); foreach($this->layout->holders as $holder) { $this->_holders[$holder->id] = $holders[$holder->id] = $holder; } $this->layout->holders = $holders; if (!is_null($node = MotoXML::findOneByXPath('./layouts/layout[@id="' . $this->page->layoutTypeId . '"]', $this->_structure))) { $holders = ContentHolderVO::findAll($node); if ($holders != null) foreach($holders as $holder) { $this->_holders[$holder->id] = $this->layout->holders[$holder->id] = $holder; } } } } function dispatch() { if ($this->getOption('load.website')) $this->_loadWebsite (); if ($this->getOption('load.layout')) $this->_loadLayout(); $type = $this->_type; $holders = array(); if (isset($this->{$type}->holders)) foreach($this->{$type}->holders as $holder) { $this->_holders[$holder->id] = $holders[$holder->id] = $holder; } $this->{$type}->holders = $holders; if ($type == 'page') { $query = './pages/page[@id=' . $this->page->pageTypeId . ']'; } else { $query = './popups/popup[@id=' . $this->popup->popupTypeId . ']'; } if (!is_null($node = MotoXML::findOneByXPath($query, $this->_structure))) { $holders = ContentHolderVO::findAll($node); if ($holders != null) foreach($holders as $holder) { $this->_holders[$holder->id] = $this->{$type}->holders[$holder->id] = $holder; } } } function getStructureHolders() { if (!is_null($node = MotoXML::findOneByXPath('.//website', $this->_structure))) { $this->websiteStructure = ContentHolderVO::findAll($node); foreach($this->websiteStructure as $holder) { $this->website->holders[$holder->id] = $holder; } } if (!is_null($node = MotoXML::findOneByXPath('.//layout', $this->_structure))) { $this->layoutStructure = ContentHolderVO::findAll($node); } } }