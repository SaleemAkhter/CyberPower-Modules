function testSMTPConnection(vueObj, params, event, namespace, index) {

    vueObj.showSpinner(event);
    vueObj.refreshUrl();
    vueObj.addUrlComponent('loadData', index);
    vueObj.addUrlComponent('namespace', namespace);
    vueObj.addUrlComponent('index', index);
    vueObj.addUrlComponent('ajax', '1');

    var data = {
        formFields: $('form[name="packagefrm"]').serialize()
    }

    $.post(vueObj.targetUrl, data)
        .done(function (data) {
                var data = data.data;
                vueObj.hideSpinner(event);
                vueObj.addAlert(data.status, data.message);
                if (data.htmlData) {
                    if (typeof window[data.callBackFunction] == "function") {
                        window[data.callBackFunction](data.htmlData.refreshState);
                    }
                }
            }
        );
}

//replace url wrapper 
var mgUrlParser = {
    oldMgUrlParser: mgUrlParser,
    
    getCurrentUrl: function(){
        var url = this.oldMgUrlParser.getCurrentUrl();
        return url.replace("action=edit", "action=module-settings").replace("&success=true", "");
    }
};

$(document).ready(function() {
    $('form[name="packagefrm"]').attr('id', 'packagefrm');
});

function redirectToConfigurableOptionsTab() {
    var windowAddress = String(window.location);
    var hashTab = windowAddress.indexOf('#tab=');
    var andTab = windowAddress.indexOf('&tab=');
    if (hashTab > 0 && andTab > 0) {
        var count = Math.min(hashTab, andTab);
    } else {
        var count = Math.max(hashTab, andTab);
    }
    var redeirectTo = (count > 0) ? windowAddress.substring(0, count) : windowAddress;
    window.location = redeirectTo + '&tab=5';
}

function test(params, data, test1, test2) {

}

mgOvhConfigOptionsFormController = {
    options: {},

    addOptionData: function (id, data){
        this.options[id] = data;
    },

    updateForm: function(){
        $.each(this.options, function (id, values) {
            $.each(values.additionalData, function (fieldName, fieldOptions) {

                var fieldId;
                var field;

                if(fieldOptions.fieldType === 'select')
                {
                    fieldId= "packageconfigoption_vps" + fieldName;
                    field = $("#"+fieldId).parent('.lu-form-group');
                }
                else
                {
                    fieldId= "packageconfigoption_vps" + fieldName;
                    field = $("input[name='"+fieldId+"']").parents('.lu-form-check').first();
                }

                if(fieldOptions.action === 'hide' )
                {
                    $(field).addClass('hidden');
                }
                else
                {
                    $(field).removeClass('hidden');
                }


            });
        });
    },

    reloadSelects: async function($list){

        var self = this;
        for (var key in mgPageControler.vueLoader.$children) {
            if (!mgPageControler.vueLoader.$children.hasOwnProperty(key)) {
                continue;
            }

            var childId = mgPageControler.vueLoader.$children[key].component_id;

            if($.inArray(childId, $list) !== -1)
            {
                if (Object.keys(mgPageControler.vueLoader.$children[key].reload_fields_ids).length === 0)
                {
                    mgPageControler.vueLoader.$children[key].reload_fields_ids.thisdoesnotexist =  'thisdoesnotexist';
                }

                await self.getPromise(key).then(function(){
                    // self.ajaxComponents[key].status = 'completed';
                });

                if(mgPageControler.vueLoader.$children[key].reload_fields_ids.thisdoesnotexist)
                {
                    delete mgPageControler.vueLoader.$children[key].reload_fields_ids.thisdoesnotexist;
                }
            }
        }
    },
    getPromise: function(key) {
        var self = this;
        return new Promise(function(resolve, reject) {
            mgPageControler.vueLoader.$children[key].loadAjaxData().then(function(data){
                resolve();
            });
        });
    },
};

mgEventHandler.on('SelectFieldDataLoaded', null, function(appId, params){

    mgOvhConfigOptionsFormController.addOptionData(appId, params.data);
    mgOvhConfigOptionsFormController.updateForm();
});

mgEventHandler.on('SelectFieldValueChanged', null, function(appId, params){
    $fieldstoreload = {
        packageconfigoption_vpsCategory: [
            'packageconfigoption_vpsProduct',
            'packageconfigoption_vpsOs',
            'packageconfigoption_vpsDistribution',
            'packageconfigoption_vpsVersion',
            'packageconfigoption_vpsLanguage'
        ],
        packageconfigoption_vpsProduct: [
            'packageconfigoption_vpsOs',
            'packageconfigoption_vpsDistribution',
            'packageconfigoption_vpsVersion',
            'packageconfigoption_vpsLanguage',
            'packageconfigoption_vpsPlesk'
        ],
        packageconfigoption_vpsOs: [
            'packageconfigoption_vpsDistribution',
            'packageconfigoption_vpsVersion',
            'packageconfigoption_vpsLanguage'
        ],
        packageconfigoption_vpsDistribution: [
            'packageconfigoption_vpsVersion',
            'packageconfigoption_vpsLanguage',
            'packageconfigoption_vpsPlesk'
        ],
        packageconfigoption_vpsVersion: [
            'packageconfigoption_vpsLanguage'
        ]
    };


    setTimeout(function () {
        mgOvhConfigOptionsFormController.reloadSelects($fieldstoreload[appId]);

        mgOvhConfigOptionsFormController.updateForm();
    }, 100);
});