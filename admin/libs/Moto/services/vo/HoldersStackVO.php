<?php
 class HoldersStackVO extends AbstractVO { public $holders; public function __construct(DOMNode $parent = null) { if (!empty($parent)) { $this->loadDomElement($parent); } } public function loadDomElement(DOMNode $parent) { if (self::getDefaultOption('HoldersStackVO.loadHolders', true)) $this->holders = ContentHolderVO::findAll($parent); return $this; } public function saveDomElement(DOMNode $parent) { return $parent; } }