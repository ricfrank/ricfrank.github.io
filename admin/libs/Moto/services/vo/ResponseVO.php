<?php
 class ResponseVO { public $result; public $status; function __construct() { $this->status = new StatusVO(); } }