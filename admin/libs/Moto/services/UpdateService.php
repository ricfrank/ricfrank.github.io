<?php
 class UpdateService { public function getCurrentVersion() { $responseVO = new ResponseVO(); $statusVO = new StatusVO(); try { $version = new VersionVO( (isset($_SESSION['MOTO_UPDATE']) ? $_SESSION['MOTO_UPDATE'] : array()) ) ; $statusVO->status = StatusEnum::SUCCESS; $responseVO->result = $version; $responseVO->status = $statusVO; } catch (Exception $e) { $responseVO->result = null; $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_XML; $responseVO->status->message = $e->getMessage(); } return $responseVO; } } ?>