<?php
 class SlotTypeVO extends ObjectTypeVO { public $animated; public $slotTemplate; public $mainPropertyID; public function loadDomElement(DOMNode $parent) { parent::loadDomElement($parent); $this->animated = (boolean) MotoUtil::toBoolean($parent->getAttribute('animated')); if (self::getDefaultOption('SlotTypeVO.loadTemplate', true)) { $this->slotTemplate = new SlotVO(MotoXML::findOneByXPath('.//template', $parent)); } $this->mainPropertyID = (integer) $parent->getAttribute('mainPropertyID'); if ($this->external) $this->preload = AssetsService::checkItem($this->url, AssetsService::SLOT, $this->id); if ( self::getDefaultOption('jsEnabled', false)) { $this->_loadRequirements($parent); $this->loadExtraData($parent); } return $this; } public function saveDomElement(DOMNode $parent) { return $parent; } public function updateTemplate(DOMNode $parent, SlotVO $slotVO) { $newNode = new DOMElement('template'); $oldNode = MotoXML::findOneByXPath("./template", $parent); if (!is_null($oldNode)) { $parent->replaceChild($newNode, $oldNode); } else { $parent->appendChild($newNode); } $slotVO->saveDomElement($newNode); $toRemove = array("id" , "holder" , "slotType", "x", "y", "depth", "scaleX", "scaleY" ); if (isset($toRemove)) foreach($toRemove as $attribute) $newNode->removeAttribute($attribute); $this->loadDomElement($parent); return $this; } public static function findById($id, DOMNode $context) { $typeVO = null; if ( self::getDefaultOption('TypeVO.factory', false) ) { $typeVO = parent::getTypeVOById($id, get_class()); } if ($typeVO == null) { $typeVO = MotoXML::findOneByXPath(".//slot[@id='{$id}']", $context, get_class()); if ($typeVO != null) { parent::setTypeVO($typeVO); } } return $typeVO; } public static function findAll(DOMNode $context) { return MotoXML::findByXPath('./slot', $context, get_class()); } } 