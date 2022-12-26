

function pmToggleButton(data) {
    let button = $(".pmCreateButton");
    if(data.htmlData.createButtonStatus)
    {
        button.removeClass('hidden');
    }
    else
    {
        button.addClass('hidden');
    }
}