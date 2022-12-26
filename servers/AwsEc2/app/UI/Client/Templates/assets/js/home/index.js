
function mgUpdateTasksQueue(table)
{
    setTimeout(function() {
        table.updateProjects();
        mgUpdateTasksQueue(table);
    }, 61000);
}

mgEventHandler.on('DatatableCreated', 'scheduledTasks', function(id, params){
    mgUpdateTasksQueue(params.datatable);
});

function awsShowWindowsPassword(data, targetId, event) {
    if (typeof data.rawData !== 'object' || typeof data.rawData.password !== 'string') {

    } else {
        var awspassword = data.rawData.password;
        var awsPasswordLabel = typeof data.rawData.passwordLabel === 'string' ? data.rawData.passwordLabel : 'Password';
        var modalContainer = $('#' + targetId).first();
        modalContainer.find('.lu-modal__body').find('form').find('.lu-form-group').remove();
        modalContainer.find('.lu-modal__body').find('.lu-alert').remove();
        modalContainer.find('.lu-modal__actions').find('.mg-submit-form').remove();
        modalContainer.find('.lu-modal__body').find('form').first().append('<div class="lu-form-group"><label>' + awsPasswordLabel + '</label> <input type="text" value="' + atob(awspassword) + '"placeholder="" class="lu-form-control" spellcheck="false"></div>');

        awspassword = null;
        data = null;
    }
}
