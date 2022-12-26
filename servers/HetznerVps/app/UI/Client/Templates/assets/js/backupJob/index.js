
function toggleBackupButton(data) {
    let button = $("#dackupJobDataTable .lu-top__toolbar a");
    if(data.htmlData.createButtonStatus)
    {
        button.removeClass('hidden');
    }
    else
    {
        button.addClass('hidden');
    }
}


function pmOnBackupJobChanged(data) {
    mgPageControler.vueLoader.refreshingState = ['dackupJobDataTable'];
    mgPageControler.vueLoader.runRefreshActions();
}