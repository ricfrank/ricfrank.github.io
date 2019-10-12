<?php
 class SlotVO extends MotoObjectVO { public $slotType; protected $_slotType; public $slotTypeId; public $click = ''; public $buttonMode = false; public $lockActions = false; public $properties = array(); public function __construct(DOMNode $parent = null) { parent::__construct($parent); if (!empty($parent)) { $this->loadDomElement($parent); } } public function loadDomElement(DOMNode $parent) { parent::loadDomElement($parent); if ($parent->hasAttribute('buttonMode')) $this->buttonMode = (boolean) MotoUtil::toBoolean($parent->getAttribute('buttonMode')); if ($parent->hasAttribute('lockActions')) $this->lockActions = (boolean) MotoUtil::toBoolean($parent->getAttribute('lockActions')); if ($parent->hasAttribute('click')) $this->click = (string) $parent->getAttribute('click'); $this->slotTypeId = (integer) $parent->getAttribute('slotType'); $loadType = !defined('IS_SMART_GETCONTENT'); $loadType = self::getDefaultOption('SlotVO.loadType', $loadType); if ($loadType) { if (self::$_structure == null) self::$_structure = MotoXML::create(STRUCTURE_RESOURSE_PATH); if ( $parent->hasAttribute('slotType') ) $this->slotType = SlotTypeVO::findById( $parent->getAttribute('slotType') , parent::$_structure); } $this->loadProperties($parent); $this->parameters = array(); $exclude = array_keys(get_object_vars($this)); foreach ($parent->attributes as $attrName => $attrNode) { if (in_array($attrName, $exclude)) continue; $this->parameters[$attrName] = MotoUtil::trim($attrNode->nodeValue); } return $this; } public function saveDomElement(DOMNode $parent) { parent::saveDomElement($parent); if ((string) MotoUtil::boolToString($this->buttonMode) != 'false') $parent->setAttribute('buttonMode', (string) MotoUtil::boolToString($this->buttonMode)); if ((string) MotoUtil::boolToString($this->lockActions) != 'false') $parent->setAttribute('lockActions', (string) MotoUtil::boolToString($this->lockActions)); if (!empty($this->click)) $parent->setAttribute('click', (string) $this->click); $parent->setAttribute('slotType', (string) isset($this->slotType) ? $this->slotType->id : null); $propertiesNode = $parent->appendChild(new DOMElement('properties')); foreach ($this->properties as $item) { $item->saveDomElement($propertiesNode->appendChild(new DOMElement('item'))); } foreach ($this->parameters as $key => $value) { if (is_bool($value)) $parent->setAttribute($key, MotoUtil::boolToString($value)); else $parent->setAttribute($key, $value); } return $parent; } public static function findById($id, DOMNode $context) { return MotoXML::findOneByXPath(".//slot[@id='{$id}']", $context, get_class()); } public static function findAll(DOMNode $context) { return MotoXML::findByXPath("./slot", $context, get_class()); } public static function findAllByExpression(DOMNode $context, $expression) { return MotoXML::findByXPath($expression, $context, get_class()); } function getType() { return ( $this->_slotType != null ? $this->_slotType : $this->slotType); } function getExtraData($name, $default = '') { return $this->getType()->getExtraData($name, $default); } function loadProperties($parent) { $this->properties = PropertyVO::findAll($parent); if ( AbstractVO::getDefaultOption('jsEnabled', false) && $this->getType() != null) { $typeVO = $this->getType(); $defaults = $typeVO->getDefaultProperties(); $typeById = $typeVO->getPropertiesMapById(); $this->properties = Moto_Html_Util::itemPropertiesFill($this->properties, $typeById, $defaults); $this->_slotType = $this->slotType; $this->slotType = null; $this->widgetType = 'slot'; $this->type = $this->getType()->librarySymbolLinkage; } } function getCssClass() { return $this->getType()->getCssClass(); } function getTemplate($name = 'main') { $template = ''; if ( $this->getType() != null && isset($this->getType()->htmlTemplates) ) { if ( isset($this->getType()->htmlTemplates[$name]) ) $template = $this->getType()->htmlTemplates[$name]; } return $template; } }