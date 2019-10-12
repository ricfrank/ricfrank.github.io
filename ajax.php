<?php
if (file_exists('./admin/actions/precheck.php'))
{
	$testing_from = '';
	require_once './admin/actions/precheck.php';
}
$response = '';
try
{
	require_once 'admin/config/ProjectConfig.php';
	ProjectConfig::setup();
	ini_set('display_errors', 'off');
	$_REQUEST['template'] = 'json';
	$options = array();
	$application = new Moto_Html_Application($options);
	$response = $application->dispatch();
}
catch(Exception $e)
{
	$result['status'] = false;
	$result['message'] = $e->getMessage();
	$result['code'] = $e->getCode();
}
if (!is_string($response))
	$response = json_encode ($response);
echo $response;
