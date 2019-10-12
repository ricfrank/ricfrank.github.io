<?php
 class Messages { static protected $messages = array(); static protected $loaded = false; static protected $_base = '/language/%tool_name%_%lang%.xml'; static protected $config = array('lang' => 'eng', 'defLang' => 'eng'); static protected $tool_name = ''; static function __init($tool_name) { self::$config['tool_name'] = self::$tool_name = $tool_name; } static function setLang($lang) { self::$config['lang'] = strtolower($lang); self::$config['tool_name'] = self::$tool_name; $base = MOTO_TOOL_DIR . self::render(self::$_base, self::$config); if (!file_exists($base) || !is_file($base)) self::$config['lang'] = self::$config['defLang']; } static function init($base = null) { if (self::$loaded) return true; if (is_null($base)) $base = self::render(self::$_base, self::$config); $base = MOTO_TOOL_DIR . $base; if (!file_exists($base) || !is_file($base)) { return false; } $dom = new MotoXML($base); $texts = MotoXML::findByXPath(".//text", $dom); if (!is_null($texts)) { for($itext = 0; $itext < $texts->length; $itext++) if ($texts->item($itext)->getAttribute("id") != "") { self::$messages[trim(strtoupper($texts->item($itext)->getAttribute("id")))] = $texts->item($itext)->nodeValue; } } else { } self::$loaded = true; return true; } static function _init($base = null) { if (self::$loaded) return true; if (is_null($base)) $base = self::$base; if (!file_exists($base) || !is_file($base)) { return false; } $str = explode("\n", file_get_contents($base)); $previous = ''; for($is = 0, $maxis = count($str); $is < $maxis; $is++) { if (trim($str[$is]) == '') continue; echo "<b>".($str[$is][0])."</b> ".$str[$is]."<br>"; if (preg_match('/^([a-z0-9\-\_]+)[ ]?=[ ]?(.*)$/i', $str[$is], $match)) { echo "$is|".$str[$is]."|\n<br>\n"; if (!isset(self::$messages[$match[1]])) { self::$messages[$match[1]] = $match[2]; } else { self::$messages[$match[1]] .= $match[2]; } $previous = $match[1]; } elseif($previous != '') { self::$messages[$previous] .= $str[$is]; } else { } } self::$loaded = true; return true; } static function get($key, $data = null) { $key = strtoupper($key); if (!self::init() || !isset(self::$messages[$key])) return $key; if (is_array($data)) { $data = array_merge( array( 'MOTO_ROOT_URL' => MOTO_ROOT_URL, 'MOTO_ADMIN_URL' => MOTO_ADMIN_URL, 'MOTO_ROOT_DIR' => MOTO_ROOT_DIR, 'MOTO_ADMIN_DIR' => MOTO_ADMIN_DIR, ) , $data); return self::render(self::$messages[$key], $data); } else return self::$messages[$key]; } static function getAll() { self::init(); return self::$messages; } static function render($tmpl, $data) { $vars = explode(',', '%' . implode("%,%", array_keys($data)) . '%'); $values = array_values($data); return str_replace($vars, $values, $tmpl); } }