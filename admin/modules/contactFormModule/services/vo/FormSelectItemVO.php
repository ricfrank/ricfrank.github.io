<?php
 class FormSelectItemVO extends FormItemVO { public $dataProvider = ""; public function loadDomElement(DOMNode $parent) { parent::loadDomElement($parent); $node = MotoXML::findOneByXPath("./data", $parent); if (!is_null($node)) $this->dataProvider = (string) MotoUtil::trim($node->nodeValue); } public function saveDomElement(DOMNode $parent) { parent::saveDomElement($parent); $dataNode = $parent->appendChild(new DOMElement('data')); $currentDataNode = MotoXML::findByXPath('./data', $parent); if ($currentDataNode == null) { $parent->replaceChild($dataNode, $currentDataNode); } $dataNode->appendChild($parent->ownerDocument->createCDATASection((string) $this->dataProvider)); return $parent; } } ?>