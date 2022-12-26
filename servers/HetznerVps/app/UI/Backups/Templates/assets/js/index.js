
function hrToggleCreateButton(data) {
    let button = $("#backupsTable .lu-top__toolbar a");
    if(data.htmlData.createButton)
    {
        button.removeClass('hidden');
    }
    else
    {
        button.addClass('hidden');
    }
}