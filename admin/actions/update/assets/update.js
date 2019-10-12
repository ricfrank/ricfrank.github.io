var currentStep = '';
var steps = new Array('backup', 'update', 'finish');
var $U = {
    backup:{
        need:false,
        done:false,
        req:{
            mode:'update',
            output:'json',
            action:'initstart',
            sub:'backup',
            reqId:1,
            callback:'Backup.step'
        },
        error:false,
        actions:null
    }
};
function getMessage(key)
{
	if (Messages[key])
		return Messages[key];
	return key;
}

function run(action, sub, param)
{
	var form = document.forms.mainForm;
	form.action.value = action;
	form.sub.value = sub;
	form.param.value = param;
	form.submit();
}
function initBackup()
{
    $U.backup.actions = new Array();
    $.post('index.php'
        , $U.backup.req
        , function (data)
        {
            if (data['ok'] == 0)
            {
                showErrorBackup(data);
                return;
            }
            var info = '<br />';
            for(var i in data['setStep'])
            {
                var step = data['setStep'][i];
                $U.backup.actions[i] = step['step'];
                info += ''
                    + '<div id="step_backup_info-'+step['step']+'" class="backup-wait">' 
                    + step['title']
                    + '</div>';
            }
            $('#step_backup_info').append(info);
            makeBackup();            
        }
        ,'json'
    );

}
function makeBackup(step, data)
{
    var b = $U.backup.req;
    var actions = $U.backup.actions;
    if (!step)
        step = 0;
    if (!data)
        data = {};
    if (!actions[step])
    {
        $U.backup.done = true;
        $U.backup.need = false;
        startUpdate();
        return;
    }
    b.action = actions[step];
    
    $('#step_backup_info-'+actions[step]).removeClass('backup-wait').addClass('backup-working');
    $.post('index.php'
        , b
        , function (data)
        {
            $('#step_backup_info-'+actions[step]).html(data['message']).removeClass('backup-working').addClass('backup-finished');
            if (data['ok'])
                makeBackup(step+1, data);
            else
                showErrorBackup(data);
        }
        ,'json'
    );
}

function showErrorBackup(data)
{
    $('#step_backup_info').append(getMessage('JS_E_ON_WORK_BACKUP'));
}

function startBackup()
{
    $("#startUpdate").html(getMessage('JS_START_BACKUP'));
    $('#step_backup').show();
    initBackup();
}
function continueUpdate()
{
    $("#startUpdate").html(getMessage('JS_START_UPDATE'));
    runStep('update');
}

function startUpdate()
{
    $U.backup.need = backup_need;
    if ($U.backup.need && !$U.backup.done)
    {
        startBackup();
        return;
    }
    
    $("#startUpdate").html(getMessage('JS_START_UPDATE'));
    runStep('update');
}

function runStep(step)
{
	$("#step_" + step +  "_img").html('<img src="assets/loader.gif"/>');
	$("#step_" + step).show();
	$("#step_" + step + "_status").html(getMessage('JS_START_WAIT'));
	$("#mainForm input[name=step]").val(step);
	$.post('index.php'
			, $("#mainForm").serialize()
			, function(data)
			{
				switch(data['step'])
				{
					case 'backup':
					case 'update':
					case 'copying':
						renderResult(data);
						break;
					case 'finish':
						renderResult(data);
						$("#startUpdate").html('');
						break;
				}
			}
			, 'json'

	);
}

function createStepInfo(data)
{
	var html = "";
	$("#mainList")

}
function renderResult(data)
{
	var objId = "#step_" + data['step'];
	$(objId + "_img").html(data['img']);
	$(objId + "_info").html(data['html']);
	$(objId + "_status").html('');
	//alert(data['nextStep'] + " |"+data['ok']+"|");
	if (parseInt(data['ok']) > 0 && data['nextStep'])
	{
		runStep(data['nextStep']);
	}

}