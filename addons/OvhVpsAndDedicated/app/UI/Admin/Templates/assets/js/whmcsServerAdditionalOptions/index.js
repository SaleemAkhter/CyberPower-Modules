
var inputs = [];

function ovhServerAdditionalOptions(integrationTarget, contId){

    $('#' + contId).find('.mg-server-integration-row').each(function(){
        var tmpCode =  $(this)[0].outerHTML;
        inputs.push(tmpCode);
        $(this).remove();
        // $('form table:eq(2) > tbody:last-child').append(tmpCode);
    });
}



jQuery(document).ready(function () {
   checkServer();
});

$($('#inputServerType')).change(function () {
   checkServer();
});

function changeNames(change) { //ale kaszana

    var config = [
        {
            fieldType: 'input',
            name: 'username',
            text: 'Application Key'
        },
        {
            fieldType: 'input',
            name: 'password',
            text: 'Application Secret'
        },
        {
            fieldType: 'textarea',
            name: 'accesshash',
            text: 'Consumer Key'
        },
    ];

    var old = [
        {
            fieldType: 'input',
            name: 'username',
            text: 'Username'
        },
        {
            fieldType: 'input',
            name: 'password',
            text: 'Password'
        },
        {
            fieldType: 'textarea',
            name: 'accesshash',
            text: "Access Hash\n" +
                "(Instead of password\n" +
                "for cPanel servers)"
        },
    ];

    if(!change)
    {
        config = old;
    }


    $.each(config, function (index , value) {
        $(value.fieldType+'[name="'+value.name+'"]').parent('td').prev('td').html(value.text);
    });
}

function checkServer()
{

   var server = $('#inputServerType option:selected').val();
   if(server != 'OvhVpsAndDedicated')
   {
       changeNames();
       removeInput();
       return;
   }
   changeNames(true);
   appendInput();
}


function removeInput()
{
   var ovhInputs = $('.ovhCustomInput');
   $.each(ovhInputs, function (i, val) {
       $(this).parents('tr').remove();
   })

}



function appendInput()
{
    $.each(inputs, function (index, value) {
        $('form table:eq(2) > tbody:last-child').append(value);
    });

   // var template = '#TEMPLATE_TO_REPLACE#';
   // var table = $('input[name="accesshash"]').parents('tr');
   // table.after(template);
}
