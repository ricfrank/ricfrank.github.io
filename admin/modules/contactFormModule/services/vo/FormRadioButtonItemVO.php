<?php
 class FormRadioButtonItemVO extends FormItemVO { public $textPosition = "right"; public function loadDomElement(DOMNode $parent) { parent::loadDomElement($parent); $nodeValue = $parent->getElementsByTagName('textPosition')->item(0); if (!is_null($nodeValue)) $this->textPosition = (string) $nodeValue->nodeValue; } public function saveDomElement(DOMNode $parent) { parent::saveDomElement($parent); $parent->appendChild(new DOMElement('textPosition', $this->textPosition)); return $parent; } } ?>