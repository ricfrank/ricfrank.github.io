<?php
 class PropertyTypeVO extends AbstractVO { public $id; public $type; public $name; public $parameters = array(); public $dataProvider = array(); public $groupId; public $dependencyId; public $dependencyValue; public function __construct(DOMNode $parent = null) { if (!empty($parent)) { $this->loadDomElement($parent); } } public function loadDomElement(DOMNode $parent) { $this->id = (integer) $parent->getAttribute('id'); $node = MotoXML::findOneByXPath("./name", $parent); if (!is_null($node)) $this->name = (string) MotoUtil::trim($node->nodeValue); $this->type = (string) $parent->getAttribute('type'); $this->groupId =(integer) $parent->getAttribute('groupId'); $this->dependencyId =(integer) $parent->getAttribute('dependencyId'); $node = MotoXML::findOneByXPath("./dependencyValue", $parent); if (!is_null($node)) $this->dependencyValue = (string) MotoUtil::trim($node->nodeValue); $this->parameters = array(); $parameters = MotoXML::findByXPath("./parameters/*", $parent); if (!is_null($parameters)) { foreach ($parameters as $element) { $this->parameters[$element->nodeName] = $element->nodeValue; } } $this->dataProvider = array(); $dataProvider = MotoXML::findByXPath("./dataProvider/*", $parent); if (!is_null($dataProvider)) { foreach ($dataProvider as $item) { $object = new PropertyTypeDataProviderItemVO($item); array_push($this->dataProvider, $object); } } if ( self::getDefaultOption('jsEnabled', false) ) $this->keyName = ($parent->hasAttribute('keyName') ? $parent->getAttribute('keyName') : $this->name ); return $this; } public function saveDomElement(DOMNode $parent) { $propertyNode = $parent->appendChild(new DOMElement('property')); $propertyNode->setAttribute('id', (integer) $this->id); $propertyNode->appendChild(new DOMElement('name')) ->appendChild($parent->ownerDocument->createCDATASection($this->name)); $propertyNode->setAttribute('type', (string) $this->type); $propertyNode->setAttribute('groupId', (string) $this->groupId); if ($this->dependencyId != 0) { $propertyNode->setAttribute('dependencyId', (string) $this->dependencyId); } if ($this->dependencyValue != "") { $propertyNode->appendChild(new DOMElement('dependencyValue')) ->appendChild($parent->ownerDocument->createCDATASection($this->dependencyValue)); } return $parent; } public static function findById($id, DOMNode $context) { return MotoXML::findOneByXPath("./properties/property[@id='{$id}']", $context, get_class()); } public static function findAll(DOMNode $context) { return MotoXML::findByXPath("./properties/property", $context, get_class()); } } 