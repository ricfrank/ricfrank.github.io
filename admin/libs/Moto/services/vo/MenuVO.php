<?php
 class MenuVO extends AbstractVO { protected $_mapByParent = null; protected $_mapById = null; public $id; public $title; public $items; public $menuType; private $_node = null; public function __construct(DOMNode $parent = null) { if (!empty($parent)) { $this->loadDomElement($parent); } } public function loadDomElement(DOMNode $parent) { $this->_node = $parent; $this->id = (integer) $parent->getAttribute('id'); if ( self::getDefaultOption('MenuVO.loadType', true) ) { $this->menuType = MenuTypeVO::findById($parent->getAttribute('menuType'), $parent->ownerDocument); } $this->title = (string) MotoUtil::trim($parent->getElementsByTagName('title')->item(0)->nodeValue); $this->items = MenuItemVO::findAll($parent); if ($this->items != null) { if ( self::getDefaultOption('MenuVO.createMapItems', false) ) { $this->createMapItems(); } } return $this; } public function saveDomElement(DOMNode $parent) { $this->_node = $parent; $parent->setAttribute('id', (integer) $this->id); $parent->setAttribute('menuType', (string) isset($this->menuType) ? $this->menuType->id : null); $parent->appendChild(new DOMElement('title')) ->appendChild($parent->ownerDocument->createCDATASection($this->title)); $data = $parent->appendChild(new DOMElement('data')); foreach ($this->items as $item) { $item->saveDomElement($data->appendChild(new DOMElement('item'))); } return $parent; } public function remove() { if (!is_null($this->_node)) { $this->_node->parentNode->removeChild($this->_node); $this->_node = null; } return true; } function sortItems() { usort($this->items, array($this, 'compare')); } protected function compare($a, $b) { if ($a->order == $b->order) return 0; return ($a->order < $b->order) ? -1 : 1; } function getById($id, $default = null) { return ( isset($this->_mapById[$id]) ? $this->_mapById[$id] : $default); } function getMap($name) { $name = '_mapBy'.ucfirst(strtolower($name)); return ( isset($this->$name) ? $this->$name : null); } function createMapItems() { if ($this->items == null || count($this->items) == 0 ) return false; if ( $this->_mapById != null) return true; $this->_mapById = array(); $this->_mapByParent = array(); $this->sortItems(); foreach($this->items as $item) { $item->xxxinit($this->menuType); $this->_mapById[$item->id] = $item; if (!isset($this->_mapByParent[$item->parent])) $this->_mapByParent[$item->parent] = array(); $this->_mapByParent[$item->parent][] = $item->id; } foreach($this->items as $item) { if ($item->parent > 0) { $parent = ( isset($this->_mapById[$item->parent]) ? $this->_mapById[$item->parent] : null); if ($parent != null) { $parent->addChild($item); } } } return true; } public static function findById($id, DOMNode $context) { return MotoXML::findOneByXPath(".//menu[@id='{$id}']", $context, get_class()); } public static function findAll(DOMNode $context) { return MotoXML::findByXPath(".//menu", $context, get_class()); } }