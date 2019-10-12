<?php
class Moto_Html_ClicksVO extends AbstractVO { public $pages = array(); public $popups = array(); public $mapUrl = array(); protected $_data = array(); function __construct($parent) { if ($parent instanceof DOMNode) $this->loadDomElement ($parent); } function loadDomElement(DOMNode $parent) { $query = './pages/page | ./popups/popup'; $nodes = MotoXML::findByXPath($query, $parent); if ($nodes != null) { foreach($nodes as $node) { $item = new stdClass(); $item->type = $node->nodeName; $item->id = $node->getAttribute('id'); $item->isProtected = (boolean) MotoUtil::toBoolean($node->getAttribute('isProtected')); $item->noIndex = (boolean) MotoUtil::toBoolean($node->getAttribute('noIndex')); $item->noFollow = (boolean) MotoUtil::toBoolean($node->getAttribute('noFollow')); $_node = MotoXML::findOneByXPath('./url', $node); if (!is_null($_node)) $item->url = (string) MotoUtil::trim($_node->nodeValue); $_node = MotoXML::findOneByXPath('./title', $node); if (!is_null($_node)) $item->title = (string) MotoUtil::trim($_node->nodeValue); $_node = MotoXML::findOneByXPath('./name', $node); if (!is_null($_node)) $item->name = (string) MotoUtil::trim($_node->nodeValue); if ($item->type == 'popup') $this->popups[$item->id] = $item; else $this->pages[$item->id] = $item; $this->mapUrl[$item->url] = $item; } } } function getByUrl($url, $default = null) { return ( isset($this->mapUrl[$url]) ? $this->mapUrl[$url] : $default ); } function getPageById($id, $default = null) { return ( isset($this->pages[$id]) ? $this->pages[$id] : $default ); } function getPopupById($id, $default = null) { return ( isset($this->popups[$id]) ? $this->popups[$id] : $default ); } function getFirstPage() { reset($this->pages); return $this->getPageById(key($this->pages)); } }