<?php
class Moto_Html_MediaLibrary { static protected $_dom = null; static function init($file) { if (self::$_dom == null) self::$_dom = MotoXML::create($file); } static function findBySource($source) { return MediaLibraryItemVO::findBySource($source, self::$_dom); } }