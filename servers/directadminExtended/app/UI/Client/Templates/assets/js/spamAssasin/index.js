function switcherLoaderShow(event)
{

    const switcher = $(event.target).parents().find('.lu-switch').first();
    const label    = switcher.parent();
    label.find('input[name="onOff"]').attr('disabled', true);
    switcher.hide();
    label.append('<div class="lu-preloader"></div>');
}

function switcherLoaderHide(eventName)
{

    const switcher = $('input[name="'+ eventName +'"').parents().find('.lu-switch').first();
    const label    = switcher.parent();
    label.find('input[name="onOff"]').attr('disabled', false);
   // console.log('show', label.find('.lu-preloader').first())
    label.find('.lu-preloader').first().remove();
    switcher.show();
}

function spamassassinForm( event)
{
    switcherLoaderShow(event)
    console.log(event);
    const form = $(event.target).parents().find('form').first();
    var switchPostData = $(event.target).is(':checked') ? {'value': 'on'} : {'value': 'off'};
    hideFieldsOnLoad(switchPostData);
    mgPageControler.vueLoader.ajaxAction(event, event.target.id, getItemNamespace(event.target.id), getItemIndex(event.target.id), switchPostData);

}

function switchForm(event, data)
{
    event.data.value === 'on' ? $('#spamAssassin').css('display', '') : $('#spamAssassin').css('display', 'none');
    switcherLoaderHide(event.data.elementID)
}

function hideFieldsOnLoad(data)
{
    if(data.value == "on")
    {
        if($('select[name="score"]').val() != "custom")
        {
            $('input[name="customScore"]').parent('div').css('display', 'none');
        }
        if($('input[name="noSubject"]').val() == "on")
        {
            $('input[name="subject"]').parent('div').css('display', 'none');
        }
    }
}


function hideInputByName(name, event, reverse)
{
    var isChecked = event.target.checked;
    if (reverse)
    {
        if (isChecked === false)
        {
            $($('input[name="' + name + '"]').parent()).hide();
            hideInput = true;
        } else
        {
            $($('input[name="' + name + '"]').parent()).show();
            hideInput = false;
        }
    } else

    if (isChecked === true)
    {
        $($('input[name="' + name + '"]').parent()).hide();
        hideInput = true;
    } else
    {
        $($('input[name="' + name + '"]').parent()).show();
        hideInput = false;
    }
}
function hideInputIfValue(name, value, event)
{

    var selectedValue = event.target.value;

    if(selectedValue === value)
    {
        $($('input[name="' + name + '"]').parent()).show();
        hideInput = false
    }else
    {
        $($('input[name="' + name + '"]').parent()).hide();
        hideInput = true;
    }
}

$(document).ready(function(){
    var data = {}
    data.value = $('input[name="onOff"]').val();
    if(data.value === "off"){
        $('select[name="score"]').val('5.0');
    }
    console.log(data);
    hideFieldsOnLoad(data);
});


