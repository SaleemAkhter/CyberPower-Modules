$( document ).ready(function() {
    /* trigg switcher action on load  */
    triggSwitcherAction();
});

///**
// *
// * @description trigg switcher change event action
// * @returns {undefined}
// */
function triggSwitcherAction()
{
    $('.lu-switch__checkbox').each(function () {
        /* should run event on loaded */
        jQuery(this).trigger('change');
    });
}

function changeFieldStatus(names, event, value)
{
    var self = event.target;

    for (var key in names)
    {
        if (!names.hasOwnProperty(key))
        {
            continue;
        }


       var field = $(self).parents('form').find('[name="'+ names[key] +'"]');

        if(field.length === 0){
            continue
        }
        var status = (self.checked === value) ? true : false;
        field.parent().attr('disabled', status);

    }
}

function checkSection(vueControler, params, event)
{
    if(!event.currentTarget)
    {
        return;
    }
    var div = event.currentTarget.parentElement.parentElement.parentElement.parentElement;
    inputs = $(div).find('input');
    inputs.each(function(key,input){
        if($(event.currentTarget).find('input')[0].checked == true)
        {
            input.checked = true;
        }
        else
        {
            input.checked = false;
        }

    });
}

function checkOptionUnderSection(event)
{
    if(!event.currentTarget)
    {
        return;
    }

    var allInputs  = $(event.currentTarget.parentElement.parentElement.parentElement).find('input');
    var inputsToCheck = $(event.currentTarget.parentElement.parentElement).find('input')
    var checked = true;
    inputsToCheck.each(function(key,input){
        if(input.checked == false)
        {
            checked = false;
        }
    });

    if(checked == false)
    {
        allInputs[0].checked = false;
    }
    else
    {
        allInputs[0].checked = true;
    }
}
