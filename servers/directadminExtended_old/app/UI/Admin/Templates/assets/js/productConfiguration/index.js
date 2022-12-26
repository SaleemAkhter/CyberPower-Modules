//replace url wrapper
var mgUrlParser = {
    oldMgUrlParser: mgUrlParser,

    getCurrentUrl: function(){
        var url = this.oldMgUrlParser.getCurrentUrl();
        return url.replace("action=edit", "action=module-settings").replace("&success=true", "");
    }
};


function redirectToPage(tabNumber)
{
    var windowAddress = String(window.location);
    var hashTab = windowAddress.indexOf('#tab=');
    var andTab = windowAddress.indexOf('&tab=');
    if (hashTab > 0 && andTab > 0) {
        var count = Math.min(hashTab, andTab);
    } else {
        var count = Math.max(hashTab, andTab);
    }

    var redirectTo = (count > 0) ? windowAddress.substring(0, count) : windowAddress;
    window.location = redirectTo + '&tab=' + tabNumber;
}

function redirectToConfigurableOptionsTab()
{
    redirectToPage(5);
}

function redirectToCustomFieldsTab()
{
    redirectToPage(4);

}

function productConfigurationSelect(event)
{
    productConfigurationSettings(event, event.target.value);
}

function productConfigurationSettings(event, packageName)
{
    const formFields        = $(event.target).parents().find('.lu-form-group , .lu-form-check');
    const protectedFields = ['mgpci[package]','mgpci[dedicated_ip]','mgpci[reseller_ip]','mgpci[suspend_at_limit]'];
    formFields.each(function(key, item){
        let name = $(item).find('input')[0].name;
        if (!name)
        {
            name = $(item).find('select')[0].name;
        }
        if (protectedFields.indexOf(name) === -1)
        {
            if (packageName === 'custom'){
                $(item).show();
            }
            else{
                $(item).hide();
            }
        }
    });
}

$('#ProductConfigurationPage').ready(function()
{
    var packageName = $('select[name="mgpci[package]"]').val()

    if(packageName !== "custom")
    {

        var protectedArrayFields = ['mgpci[package]','mgpci[dedicated_ip]','mgpci[reseller_ip]','mgpci[suspend_at_limit]'];
        var fields = $('#layers').find('.lu-form-group , .lu-form-check')

        fields.each(function(){
            var fieldName = $(this).find('input').attr('name')

            if(typeof fieldName === "undefined"){
                fieldName = $(this).find('select').attr('name')
            }
            if (protectedArrayFields.indexOf(fieldName) === -1)
            {
                $(this).hide();
            }
        })


    }

})