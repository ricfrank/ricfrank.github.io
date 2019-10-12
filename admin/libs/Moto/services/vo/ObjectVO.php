<?php
 class ObjectVO extends MotoObjectVO { public $type; public $value; public $click = ''; public $buttonMode = false; public $lockActions = false; public function __construct(DOMNode $parent = null) { parent::__construct($parent); if (!empty($parent)) { $this->loadDomElement($parent); } } public function loadDomElement(DOMNode $parent) { parent::loadDomElement($parent); $this->type = (string) $parent->getAttribute('type'); if ($parent->hasAttribute('buttonMode')) $this->buttonMode = (boolean) MotoUtil::toBoolean($parent->getAttribute('buttonMode')); if ($parent->hasAttribute('lockActions')) $this->lockActions = (boolean) MotoUtil::toBoolean($parent->getAttribute('lockActions')); if ($parent->hasAttribute('click')) $this->click = (string) $parent->getAttribute('click'); $this->value = (string) MotoXML::findOneByXPath('./data', $parent)->nodeValue; return $this; } public function saveDomElement(DOMNode $parent) { parent::saveDomElement($parent); $parent->setAttribute('type', (string) $this->type); if ((string) MotoUtil::boolToString($this->buttonMode) != 'false') $parent->setAttribute('buttonMode', (string) MotoUtil::boolToString($this->buttonMode)); if ((string) MotoUtil::boolToString($this->lockActions) != 'false') $parent->setAttribute('lockActions', (string) MotoUtil::boolToString($this->lockActions)); if (!empty($this->click)) $parent->setAttribute('click', (string) $this->click); if ($this->type == "htmlText") { if (isset($this->value)) $this->value = MotoUtil::optimizeHtmlText($this->value); } $parent->appendChild(new DOMElement('data')) ->appendChild($parent->ownerDocument->createCDATASection($this->value)); if (isset($this->parameters)) foreach ($this->parameters as $key => $value) { if ( ($key == 'autoSize' && $value == 'none') || ($key == 'smoothing' && ($value === true || $value == 'true')) || ($key == 'backgroundColor' && $value == '0x000000') || ($key == 'guide' && ($value === false || $value == 'false')) || ($key == 'blocked' && ($value === false || $value == 'false')) || ($key == 'antiAliasType' && $value == 'normal') ) continue; if (is_bool($value)) $parent->setAttribute($key, MotoUtil::boolToString($value)); else $parent->setAttribute($key, $value); } return $parent; } public static function findById($id, DOMNode $context) { return MotoXML::findOneByXPath(".//object[@id='{$id}']", $context, get_class()); } public static function findAll(DOMNode $context) { return MotoXML::findByXPath("./object", $context, get_class()); } public static function findAllByExpression(DOMNode $context, $expression) { return MotoXML::findByXPath($expression, $context, get_class()); } function getType() { return null; } }