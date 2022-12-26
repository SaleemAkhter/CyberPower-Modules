
function hrToggleUnmountButton(data) {
    let button = $("#isosTable .lu-top__toolbar a");
    if(data.htmlData.unmountButton)
    {
        button.removeClass('hidden');
    }
    else
    {
        button.addClass('hidden');
    }
}