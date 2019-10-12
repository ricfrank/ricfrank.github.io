<?php
 class LanguageVO implements MotoDomObjectInterface { public $id; public $texts = array(); public function __construct(DOMNode $parent = null) { if (!empty($parent)) { $this->loadDomElement($parent); } } public function loadDomElement(DOMNode $parent) { $this->id = $parent->getAttribute("id"); $textsXML = MotoXML::findByXPath('./text', $parent); foreach ($textsXML as $text) { $id = (string) $text->getAttribute("id"); $value = (string) $text->nodeValue; if (!empty($id) && ! empty($value)) $this->texts[$id] = $value; } return $this; } public function saveDomElement(DOMNode $parent) { } } ?>