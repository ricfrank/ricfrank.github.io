<?php
 class FormConfigurationVO implements MotoDomObjectInterface { public $emailTo; public $serverProcessorType; public $serverProcessorFileName; public $validateRequiredOnly; public $submitFormOnEnter; public $messageSentText; public $messageSentFailedText; public $formProcessingText; public $useSmtp = false; public $smtpServer; public $smtpPort; public $smtpLogin = ''; public $smtpPassword = ''; public $smtpAuth = 'none'; public $smtpSecure = 'none'; public $plainText; public $emailFromSource; public $subjectSource; public $checkBoxSelected; public $checkBoxUnselected; public $radioButtonUnselected; public $validationErrorMessages = array(); public function __construct(DOMNode $parent = null) { if (!empty($parent)) { $this->loadDomElement($parent); } } public function loadDomElement(DOMNode $parent) { $nodeValue = $parent->getElementsByTagName('emailTo')->item(0); if (!is_null($nodeValue)) $this->emailTo = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('serverProcessorType')->item(0); if (!is_null($nodeValue)) $this->serverProcessorType = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('serverProcessorFileName')->item(0); if (!is_null($nodeValue)) $this->serverProcessorFileName = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('validateRequiredOnly')->item(0); if (!is_null($nodeValue)) $this->validateRequiredOnly = (boolean) MotoUtil::toBoolean($nodeValue->nodeValue); $nodeValue = $parent->getElementsByTagName('submitFormOnEnter')->item(0); if (!is_null($nodeValue)) $this->submitFormOnEnter = (boolean) MotoUtil::toBoolean($nodeValue->nodeValue); $nodeValue = $parent->getElementsByTagName('messageSentText')->item(0); if (!is_null($nodeValue)) $this->messageSentText = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('messageSentFailedText')->item(0); if (!is_null($nodeValue)) $this->messageSentFailedText = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('formProcessingText')->item(0); if (!is_null($nodeValue)) $this->formProcessingText = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('useSmtp')->item(0); if (!is_null($nodeValue)) $this->useSmtp = (boolean) MotoUtil::toBoolean($nodeValue->nodeValue); $nodeValue = $parent->getElementsByTagName('smtpServer')->item(0); if (!is_null($nodeValue)) $this->smtpServer = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('smtpPort')->item(0); if (!is_null($nodeValue)) $this->smtpPort = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('smtpLogin')->item(0); if (!is_null($nodeValue)) $this->smtpLogin = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('smtpPassword')->item(0); if (!is_null($nodeValue) && $nodeValue->nodeValue != "") { $this->smtpPassword = (string) $nodeValue->nodeValue; $c = new ContentService(); $p = $c->getProductInfo(); $this->smtpPassword = MotoUtil::decrypt($this->smtpPassword, $p['product_id']); } $nodeValue = $parent->getElementsByTagName('smtpAuth')->item(0); if (!is_null($nodeValue)) $this->smtpAuth = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('smtpSecure')->item(0); if (!is_null($nodeValue)) $this->smtpSecure = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('plainText')->item(0); if (!is_null($nodeValue)) $this->plainText = (boolean) MotoUtil::toBoolean($nodeValue->nodeValue); $nodeValue = $parent->getElementsByTagName('emailFromSource')->item(0); if (!is_null($nodeValue)) $this->emailFromSource = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('subjectSource')->item(0); if (!is_null($nodeValue)) $this->subjectSource = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('checkBoxSelected')->item(0); if (!is_null($nodeValue)) $this->checkBoxSelected = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('checkBoxUnselected')->item(0); if (!is_null($nodeValue)) $this->checkBoxUnselected = (string) $nodeValue->nodeValue; $nodeValue = $parent->getElementsByTagName('radioButtonUnselected')->item(0); if (!is_null($nodeValue)) $this->radioButtonUnselected = (string) $nodeValue->nodeValue; $this->validationErrorMessages = array( 'fieldIsRequired' => (string) self::findMessageByType("fieldIsRequired", $parent), 'emailNotValid' => (string) self::findMessageByType("emailNotValid", $parent), 'minCharsLimitError' => (string) self::findMessageByType("minCharsLimitError", $parent), 'reqExpError' => (string) self::findMessageByType("reqExpError", $parent), 'biggerThanMaxError' => (string) self::findMessageByType("biggerThanMaxError", $parent), 'lowerThanMinError' => (string) self::findMessageByType("lowerThanMinError", $parent), 'notANumberError' => (string) self::findMessageByType("notANumberError", $parent), 'negativeError' => (string) self::findMessageByType("negativeError", $parent), 'minRequirementError' => (string) self::findMessageByType("minRequirementError", $parent), 'maxRequirementError' => (string) self::findMessageByType("maxRequirementError", $parent), 'shouldBeEqualError' => (string) self::findMessageByType("shouldBeEqualError", $parent), 'dateIsNotValidError' => (string) self::findMessageByType("dateIsNotValidError", $parent)); return $this; } public function saveDomElement(DOMNode $parent) { $parent->appendChild(new DOMElement('emailTo')) ->appendChild($parent->ownerDocument->createCDATASection($this->emailTo)); $parent->appendChild(new DOMElement('serverProcessorType', $this->serverProcessorType)); $parent->appendChild(new DOMElement('serverProcessorFileName', $this->serverProcessorFileName)); $parent->appendChild(new DOMElement('validateRequiredOnly', MotoUtil::boolToString($this->validateRequiredOnly))); $parent->appendChild(new DOMElement('submitFormOnEnter', MotoUtil::boolToString($this->submitFormOnEnter))); $parent->appendChild(new DOMElement('messageSentText')) ->appendChild($parent->ownerDocument->createCDATASection($this->messageSentText)); $parent->appendChild(new DOMElement('messageSentFailedText')) ->appendChild($parent->ownerDocument->createCDATASection($this->messageSentFailedText)); $parent->appendChild(new DOMElement('formProcessingText')) ->appendChild($parent->ownerDocument->createCDATASection($this->formProcessingText)); $parent->appendChild(new DOMElement('useSmtp', MotoUtil::boolToString($this->useSmtp))); $parent->appendChild(new DOMElement('smtpServer')) ->appendChild($parent->ownerDocument->createCDATASection($this->smtpServer)); $parent->appendChild(new DOMElement('smtpPort', $this->smtpPort)); if ($this->smtpAuth == 'none' || $this->smtpAuth == '') { $this->smtpLogin = ''; $this->smtpPassword = ''; } $parent->appendChild(new DOMElement('smtpLogin')) ->appendChild($parent->ownerDocument->createCDATASection($this->smtpLogin)); $password = $this->smtpPassword; if ($password != '') { $c = new ContentService(); $p = $c->getProductInfo(); $password = MotoUtil::encrypt($password, $p['product_id']); } $parent->appendChild(new DOMElement('smtpPassword')) ->appendChild($parent->ownerDocument->createCDATASection($password)); $parent->appendChild(new DOMElement('smtpAuth', $this->smtpAuth)); $parent->appendChild(new DOMElement('smtpSecure', $this->smtpSecure)); $parent->appendChild(new DOMElement('plainText', MotoUtil::boolToString($this->plainText))); $parent->appendChild(new DOMElement('emailFromSource', $this->emailFromSource)); $parent->appendChild(new DOMElement('subjectSource')) ->appendChild($parent->ownerDocument->createCDATASection($this->subjectSource)); $parent->appendChild(new DOMElement('checkBoxSelected')) ->appendChild($parent->ownerDocument->createCDATASection($this->checkBoxSelected)); $parent->appendChild(new DOMElement('checkBoxUnselected')) ->appendChild($parent->ownerDocument->createCDATASection($this->checkBoxUnselected)); $parent->appendChild(new DOMElement('radioButtonUnselected')) ->appendChild($parent->ownerDocument->createCDATASection($this->radioButtonUnselected)); $validationErrorMessages = $parent->appendChild(new DOMElement('validationErrorMessages')); foreach ($this->validationErrorMessages as $key => $value) { $node = $validationErrorMessages->appendChild(new DOMElement('message')); $node->appendChild($validationErrorMessages->ownerDocument->createCDATASection($value)); $node->setAttribute("type", $key); } return $parent; } public static function findMessageByType($type, DOMNode $context) { return MotoXML::findOneByXPath(".//message[@type='{$type}']", $context)->nodeValue; } } ?>