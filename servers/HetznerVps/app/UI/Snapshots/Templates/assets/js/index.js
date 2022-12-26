
function hrToggleCreateButton(data) {
    let button = $("#snapshotsTable .lu-top__toolbar a");
    if(data.htmlData.createButton)
    {
        button.removeClass('hidden');
    }
    else
    {
        button.addClass('hidden');
    }
}