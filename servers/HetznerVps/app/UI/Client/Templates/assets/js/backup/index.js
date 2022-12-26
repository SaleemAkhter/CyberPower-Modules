
function mgLocationReload(data) {
    window.location.reload();
}


function pmToggleButton(data) {
    let button = $("#backupDataTable .lu-top__toolbar a");
    if(data.htmlData.createButtonStatus)
    {
        button.removeClass('hidden');
    }
    else
    {
        button.addClass('hidden');
    }
}