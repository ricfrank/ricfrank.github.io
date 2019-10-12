function _backup(name)
{
	this.name = name;
	this.mapa = null;
	this.currentStep = null;
}

_backup.prototype.merge = function($a, $b, $rewrite)
{
	for(var i in $a)
	{
		if (!$b[i] || $rewrite)
			$b[i] = $a[i];
	}
	return $b;
}

_backup.prototype.reset = function()
{
	$('.backup_step').hide();
	$('#backup_cp').show();
	$('.backup_cp').show();
}

_backup.prototype.init = function()
{
	$('.backup_step').hide();
	$('#backup_config').show();
	this.mapa = null;
	this.currentStep = null;

}

_backup.prototype.create = function()
{

}

_backup.prototype._2obj = function ($array)
{
	var ans = new Object();
	for(var i in $array)
	{
		ans[$array[i]['name']] = $array[i]['value'];
	}
	return ans;
}

_backup.prototype.initReq = function()
{
	var $data = $req;
	$data = this.merge($data, this._2obj($('#FBConfig').serializeArray()));
	return $data;
}

_backup.prototype.printStep = function($data)
{
	var html = '';
	var n = 0;
	for(var i in $data)
	{
		n++;
		var row = $data[i];
		html += '<div class="step step_' + row['step'] + '">' + n + '. <span>' + row['title'] + '</span></div>';
	}
	$('#backup_work').html(html);
	//backup_work
}

_backup.prototype.start = function()
{
	var $data = this.initReq();
	$data['action'] = 'initstart';
	$data['callback'] = this.name + '.step';
	$('.backup_step').hide();
	$('#backup_wait').show();
	$('#backup_work').show();
	$('.backup_start_items_hide').hide();
	$('.backup_start_items_show').show();
	$('.backup_start_items').show();
	$('#backup_work').html('');
	this.currentStep = null;
	this.mapa == null;
	_send($data);
	return false;
}

_backup.prototype.step = function($data, $req)
{
	if (this.map == null && $data['setStep'])
	{
		this.map = $data['setStep'];
		this.printStep($data['setStep']);
	}

	if ($data['autorun'])
	{
		this.autorun($data, $req);
	}
}

_backup.prototype.autorun = function($data, $req)
{
	var next = null;
	if (this.currentStep != null)
		next = '';
	for(var i in this.map)
	{
		if (next == null)
		{
			next = this.map[i]['step'];
			break;
		}
		if (this.currentStep == this.map[i]['step'])
			next = null;
	}

	if ($data['add2Work'] && $data['add2Work']['value'])
	{
		$('#backup_work').append($data['add2Work']['value']);
	}
	if (this.currentStep != null)
	{
		if ($data['ok'] && $data['ok'] == 1)
		{
			var $class = 'passed';
			$('#backup_work .step_' + this.currentStep).addClass($class);
			if ($data['message'] && $data['message'] != '')
			{
				$('#backup_work .step_' + this.currentStep + ' span').html($data['message']);
			}
		}
		else
		{
			var $class = 'error';
			if ($data['class'] && $data['class'] != '')
				$class = $data['class'];
			$('#backup_work .step_' + this.currentStep).addClass($class);
			if ($data['message'] && $data['message'] != '')
			{
				$('#backup_work .step_' + this.currentStep + ' span').html($data['message']);
			}
		}
	}


	if (next != null && next != '')
	{
		var $data = this.initReq();
		$data['action'] = next;
		$('#backup_work .step_' + next).addClass('current');

		$data['callback'] = this.name + '.autorun';
		this.currentStep = next;
		_send($data);
	}
	else
	{
		///finished
		$('#backup_cp').show();
		$('#backup_work_cp').show();
		$('#backup_wait').hide();
		$('.backup_start_items_hide').show();
		$('.backup_start_items_show').hide();
	}
}

_backup.prototype.close = function()
{
	$('.backup_step').hide();
	$('#backup_cp').show();
	this.map = null;
	this.currentStep = null;
}

var $debug = new Array();

if (!Backup)
	var Backup = null;



var isSend = false;
var $req = {
		output: 'json',
		action: '',
		sub: 'backup',
		reqId: 0
	};
function getMessage(key)
{
	if (Messages[key])
		return Messages[key];
	return key;
}

function backupCreate()
{
	backupGo();
}

function backupGo($step)
{
	if (!$step)
		$step = 1;
	var result = false;
	switch($step)
	{
		case 1:
			result = _stepInit();
			break;
		case 2:
			result = _stepCopy();
			break;
		case 3:
			result = _stepZip();
			break;
		case 4:
			result = _stepFinish();
			break;
	}
}

function _stepInit($data)
{
	if (!$data)
	{
		var $data = $req;
		$data['action'] = 'init';
		$data['callback'] = '_stepInit';
		_send($data);
		return;
	}
	//alert(1);
}

function _stepCopy()
{

}

function _stepZip()
{

}

function _stepFinish()
{

}

function _send($request)
{
	if (!$request['action'] || $request['action'] == '')
		return false;
	$request['reqId'] ++;
	$.ajax({
		url: 'index.php',
		type: 'POST',
		data: $request,
		dataType: 'json',
		success: function ($data)
			{
				if ($request['callback'] && $request['callback'] != '')
					eval($request['callback'] + '($data);');
				else
					_get($data, $request);
			},
		error: function (x,e)
		{
			//alert('Error: status = ' + x.status + ';\nE: ' + e + ';\n' + x.responseText);
		}

	});
	return true;
}

function _get($data, $request)
{
	if ($data['alert'] && $data['alert'] != '')
		alert($data['alert']);


	if ($data['callback'] && $data['callback'] != '')
		eval($data['callback'] + '($data);');
}
