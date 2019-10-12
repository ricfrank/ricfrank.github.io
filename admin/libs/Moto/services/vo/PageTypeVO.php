<?php
 class PageTypeVO extends HoldersStackVO { public $id; public $name; public $preview; public $layoutType; public $locked; public $pageTemplate; public $properties; public function __construct(DOMNode $parent = null) { parent::__construct($parent); if (!empty($parent)) { $this->loadDomElement($parent); } } public function loadDomElement(DOMNode $parent) { parent::loadDomElement($parent); $this->id = (integer) $parent->getAttribute('id'); $node = MotoXML::findOneByXPath("./name", $parent); if (!is_null($node)) $this->name = (string) MotoUtil::trim($node->nodeValue); $this->properties = PropertyTypeVO::findAll($parent); $this->preview = (string) $parent->getAttribute('preview'); $this->layoutType = (integer) $parent->getAttribute('layoutType'); $this->locked = (boolean) MotoUtil::toBoolean($parent->getAttribute('locked')); if (self::getDefaultOption('PageTypeVO.loadTemplate', true)) { $this->pageTemplate = new PageVO(MotoXML::findOneByXPath('.//template', $parent)); } return $this; } public function saveDomElement(DOMNode $parent) { parent::saveDomElement($parent); return $parent; } public function updateTemplate(DOMNode $parent, PageVO $pageVO) { $newNode = new DOMElement('template'); $oldNode = MotoXML::findOneByXPath("./template", $parent); if (!is_null($oldNode)) { $parent->replaceChild($newNode, $oldNode); } else { $parent->appendChild($newNode); } $needs = array("objects", "slots", "modules"); foreach($pageVO->modules as $object) { if (isset($object->parameters)) { if (isset($object->parameters["externalData"])) $object->parameters["externalData"] = "false"; if (isset($object->parameters["externalFile"])) $object->parameters["externalFile"] = ""; } } $needs = array("objects", "slots", "modules"); foreach($needs as $need) { $objs = new DOMElement($need); $newNode->appendChild($objs); foreach($pageVO->$need as $object) { $obj = new DOMElement(substr($need, 0 , strlen($need)-1)); $objs->appendChild($obj); $object->saveDomElement($obj); } } $ids = MotoXML::findByXPath(".//*[@id>0]", $newNode); if (!is_null($ids)) { foreach($ids as $id) { $id->removeAttribute("id"); } } $this->loadDomElement($parent); return $this; } public static function findById($id, DOMNode $context) { return MotoXML::findOneByXPath(".//page[@id='{$id}']", $context, get_class()); } public static function findAll(DOMNode $context) { return MotoXML::findByXPath(".//page", $context, get_class()); } }