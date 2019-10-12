<?php
 class WebsiteVO extends MotoObjectsHolderVO { public $properties; public $loginForm; public $width; public $height; public $style; public function __construct(DOMNode $parent = null) { parent::__construct($parent); if (!empty($parent)) { $this->loadDomElement($parent); } } public function loadDomElement(DOMNode $parent) { parent::loadDomElement($parent); $this->loadProperties($parent); if (!is_null($node = MotoXML::findOneByXPath('./elements/loginForm', $parent))) $this->loginForm = new LoginFormVO($node); $this->width = (integer) $parent->getAttribute('width'); $this->height = (integer) $parent->getAttribute('height'); $this->style = new StyleVO(MotoXML::findOneByXPath("./style", $parent)); return $this; } public function saveDomElement(DOMNode $parent) { parent::saveDomElement($parent); if (!is_null($this->properties)) { $properties = $parent->appendChild(new DOMElement('properties')); foreach ($this->properties as $property) { $property->saveDomElement($properties->appendChild(new DOMElement('item'))); } } $elements = $parent->appendChild(new DOMElement('elements')); if (!is_null($this->loginForm)) { $this->loginForm->saveDomElement($elements->appendChild(new DOMElement('loginForm'))); } $parent->setAttribute('width', (integer) $this->width); $parent->setAttribute('height', (integer) $this->height); if (isset($this->style) && $this->style != null) $this->style->saveDomElement($parent); return $parent; } public function updateDomElement(DOMNode $parent) { $newNode = new DOMElement('website'); $oldNode = MotoXML::findOneByXPath('.//website', $parent); if (!is_null($oldNode)) { $parent->replaceChild($newNode, $oldNode); } else { $parent->appendChild($newNode); } return $this->saveDomElement($newNode); } function loadProperties(DOMNode $parent) { if (!is_null($node = MotoXML::findOneByXPath('./properties', $parent))) $this->properties = PropertyVO::findAll($node); if ( AbstractVO::getDefaultOption('jsEnabled', false)) { $typeById = $this->getPropertiesMapById(); $this->properties = Moto_Html_Util::itemPropertiesFill($this->properties, $typeById); } } function getPropertiesMapById() { $dom = MotoXML::create(STRUCTURE_RESOURSE_PATH); $websiteNode = MotoXML::findOneByXPath('./website', $dom); if ($websiteNode == null) return null; $properties = PropertyTypeVO::findAll($websiteNode); if ($properties == null) return null; $typeById = array(); foreach($properties as $property) { $typeById[$property->id] = $property; } return $typeById; } } 