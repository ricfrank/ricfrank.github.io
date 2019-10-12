<?php
 class ContentVO implements MotoDomObjectInterface { public $websiteContent; public $layoutsContent = array(); public $pagesContent = array(); public $popupsContent = array(); public $popupsFolders = array(); public function __construct(DOMNode $parent = null, $mode = 'full') { if (!empty($parent)) { $this->loadDomElement($parent, $mode); } } public function loadDomElement(DOMNode $parent, $mode = 'full') { if (!is_null($node = MotoXML::findOneByXPath('.//website', $parent))) $this->websiteContent = new WebsiteVO($node); if (!is_null($node = MotoXML::findOneByXPath('.//layouts', $parent))) $this->layoutsContent = LayoutVO::findAll($node); if ($mode == 'full') { if (!is_null($node = MotoXML::findOneByXPath('.//pages', $parent))) $this->pagesContent = PageVO::findAll($node); if (!is_null($node = MotoXML::findOneByXPath('.//popups', $parent))) $this->popupsContent = PopupVO::findAll($node); } else { $nodes = MotoXML::findByXPath('.//pages/page', $parent); if ($nodes != null) { $this->pagesContent = array(); foreach($nodes as $node) $this->pagesContent[] = $node->getAttribute('id'); } $nodes = MotoXML::findByXPath('.//popups/popup', $parent); if ($nodes != null) { $this->popupsContent = array(); foreach($nodes as $node) $this->popupsContent[] = $node->getAttribute('id'); } } if (!is_null($node = MotoXML::findOneByXPath('.//popups', $parent))) $this->popupsFolders = MotoFolderVO::findAll($node); return $this; } public function saveDomElement(DOMNode $parent) { $this->websiteContent->saveDomElement($parent->appendChild(new DOMElement('website'))); $this->layoutsContent->saveDomElement($parent->appendChild(new DOMElement('layouts'))); $this->pagesContent->saveDomElement($parent->appendChild(new DOMElement('pages'))); $this->popupsContent->saveDomElement($parent->appendChild(new DOMElement('popups'))); return $parent; } } 