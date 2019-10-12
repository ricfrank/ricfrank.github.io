<?php
 class ModuleConfigurationVO implements MotoDomObjectInterface { public $options; public function __construct(DOMNode $parent = null) { if (!empty($parent)) { $this->loadDomElement($parent); } } public function loadDomElement(DOMNode $parent) { $this->options = ModuleOptionVO::findAll($parent); return $this; } public function saveDomElement(DOMNode $parent) { foreach ($this->options as $option) { $option->saveDomElement($parent->appendChild(new DOMElement('option'))); } } public static function findAll(DOMNode $context) { return MotoXML::findOneByXPath(".//configuration", $context, get_class()); } } ?>