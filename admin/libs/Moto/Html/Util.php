<?php
class Moto_Html_Util { static function get($obj, $key, $value = null) { if (is_array($obj) && isset($obj[$key])) return $obj[$key]; if (is_object($obj) && isset($obj->$key)) return $obj->$key; return $value; } static function renderTemplate($template, $data) { $keys = array_keys($data); $keys = '{%' . implode('%},{%', $keys) . '%}'; $keys = explode(',', $keys); $values = array_values($data); $html = str_replace($keys, $values, $template); return $html; } static function camel($str, $ch = '-') { $result = ''; $str = explode($ch, $str); for($i = 0; $i < count($str) ; $i++) { $result .= ucfirst(strtolower($str[$i])); } return $result; } static function propertyRender($item, $type) { switch($type) { case 'link' : $item = Moto_Html_Render_Click::getInstance()->parse( $item['value'])->toArray(); break; case 'color' : $item['value'] = Moto_Html_Util::properyParserColor( $item['value'] ); break; case 'htmlText' : $html = Moto_Html_Util::properyParserHtmlText( $item['value'], true ); if ($html instanceof Moto_Html_Render_HtmlTextVO) { $item['styles'] = $html->getFullStyle(); $item['style'] = Moto_Html_Util::implodeStyleArray($item['styles']); $item['value'] = $html->__toString(); } break; case 'plainText' : $item['value'] = Moto_Html_Util::properyParserPlainText( $item['value'] ); break; case 'textFormat' : $format = Moto_Html_Util::properyParserTextFormat( $item['value'] ); $item['value'] = $format['style']; $item['styles'] = $format['styles']; break; case 'image' : $item['image'] = Moto_Html_MediaLibrary::findBySource($item['value']); break; case 'backgroundStyle' : case 'style' : $style = new StyleVO(); try { $xml = '<root>' . $item['value'] . '</root>'; $dom = new MotoXML(); $dom->loadXML($xml); $node = MotoXML::findOneByXPath('./style', $dom); if ($node != null) $style->loadDomElement($node); } catch(Exception $e) { echo $e->getTraceAsString(); } $item['value'] = $style->__toString(); $item['style'] = $style; break; default: break; } return $item; } static function itemPropertiesFill($properties, $typeById, $defaults = array()) { if ( !is_array($defaults) ) $defaults = array(); foreach($properties as $i => $property) { $value = $property->parameters; $value['value'] = $property->value; $itemType = ( isset($typeById[$property->propertyType]) ? $typeById[$property->propertyType] : null); $keyName = $i; if ($itemType != null) { $keyName = ( isset($itemType->keyName) != null ? $itemType->keyName : $i); $value = Moto_Html_Util::propertyRender($value, $itemType->type); } $defaults[$keyName] = $value; } return $defaults; } static function properyParserColor($value) { return str_replace ('0x', '', $value); } static function properyParserHtmlText($value, $full = false) { $params['returnObject'] = $full; return Moto_Html_Render_HtmlText::getInstance()->parse( $value, $params ); } static function properyParserPlainText($value) { return strip_tags( $value ); } static function properyParserTextFormat($value, $params = null) { if ($params == null) $params = array(); if (!isset($params['returnObject'])) $params['returnObject'] = true; $defaultStyle = array( 'font-weight' => 'normal', 'font-style' => 'normal', 'text-decoration' => 'none', ); $format = Moto_Html_Render_HtmlText::getInstance()->parse($value, $params); $styles = $format->getFullStyle(); $styles = array_merge($defaultStyle, $styles); $style = Moto_Html_Util::implodeStyleArray($styles); $result = array( 'styles' => $styles, 'style' => $style ); return $result; } static function textFormatParser($format, $params = null) { return self::properyParserTextFormat($format, $params); } static function implodeStyleArray($styles) { $style = ''; foreach($styles as $key => $value) { $style .= $key . ':' . $value . ';'; } return $style; } static function getStyleOfHtml($html) { $style = ''; if ( preg_match_all('/style=\"([^\"]+)\"/', $html, $match) ) { $style = implode(';', $match[1]); } return $style; } static function implodeArray($array, $data = array(), $sub = '') { if (is_array($array) || is_object($array)) foreach($array as $key => $value) { if ( substr($key, 0, 1) == '_') continue; if ( is_array($value) || is_object($value) ) { $data = self::implodeArray($value, $data, ( $sub != '' ? $sub . '.' : '' ) . $key); } else { $data[ ( $sub != '' ? $sub . '.' : '' ) . $key] = $value; } } return $data; } static function implodeArraySimple($array, $sub = '') { foreach($array as $key => $value) { if ( substr($key, 0, 1) == '_') continue; if ( is_array($value) || is_object($value) ) { } else { $data[ ( $sub != '' ? $sub . '.' : '' ) . $key] = $value; } } return $data; } static function getWebsiteDimension($contentFilename) { $website = array( 'width' => 0, 'height' => 0, ); if (file_exists($contentFilename)) { $dom = new MotoXML( $contentFilename ); $root = MotoXML::findOneByXPath('.', $dom); $websiteNode = MotoXML::findOneByXPath('./website', $root); if ($websiteNode != null) { $website['width'] = $websiteNode->getAttribute('width'); $website['height'] = $websiteNode->getAttribute('height'); } } return $website; } }