
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

function pmToggleCreateJobButton(data) {
    let button = $(".pmCreateJobButton");
    if(data.htmlData.createJobButtonStatus)
    {
        button.removeClass('hidden');
    }
    else
    {
        button.addClass('hidden');
    }
}