
var mgIntegrationHelper = {
    //help determine what part of app should be integrated
    getIngegrationInsertType: function(isIntegration) {
        var allowedInsertTypes= ['full', 'content', 'mc_content'];
        var intParam = $(isIntegration).attr('mg-integration-insert-type');
        if (allowedInsertTypes.includes(intParam)) {
            return intParam;
        } else {
            return 'full';
        }
    },
    //get integration HTML code to be passed on the page
    getIngegrationCode: function(selector, integrationInsertType) {
        if(integrationInsertType === 'full'){
            return $(selector).parents('.mg-integration-container').children('#layers').first()[0].outerHTML;
        } else if (integrationInsertType === 'mc_content') {
            return $(selector)[0].innerHTML;
        } else {
            return $(selector)[0].outerHTML;
        }
    }
}

mgEventHandler.on('AppsPreLoad', null, function(id, params){
    if (typeof params.appContainers !== 'undefined') {
        //check all app container, looking for integration containers
        for (var key in params.appContainers) {
            if (!params.appContainers.hasOwnProperty(key)) {
                continue;
            }

            //each integration container needs to have 'mg-integration-container' class
            var isIntegration = $(params.appContainers[key]).parents('.mg-integration-container');

            if (isIntegration.length === 1) {
                var integrationInsertType = mgIntegrationHelper.getIngegrationInsertType(isIntegration);
                var tempIntCode = mgIntegrationHelper.getIngegrationCode(params.appContainers[key], integrationInsertType);
                var integrationType = $(isIntegration).attr('mg-integration-type');
                var integrationTarget = $(isIntegration).attr('mg-integration-target');
                if (integrationType === 'append') {
                    $(params.appContainers[key])[0].remove();
                    $(integrationTarget).append(tempIntCode);
                } else if(integrationType === 'replace') {
                    $(params.appContainers[key])[0].remove();
                    $(integrationTarget).replaceWith(tempIntCode);
                } else if(integrationType === 'after') {
                    $(params.appContainers[key])[0].remove();
                    $(integrationTarget).after(tempIntCode);
                } else if(integrationType === 'before') {
                    $(params.appContainers[key])[0].remove();
                    $(integrationTarget).before(tempIntCode);
                } else if(integrationType === 'prepend') {
                    $(params.appContainers[key])[0].remove();
                    $(integrationTarget).prepend(tempIntCode);
                } else if(integrationType === 'custom') {
                    var contId = $(params.appContainers[key]).attr('id');
                    var integrationFunction = $(isIntegration).attr('mg-integration-function');
                    if (integrationFunction && typeof window[integrationFunction] === "function") {
                        window[integrationFunction](integrationTarget, contId);
                    }
                    if (integrationTarget !== 'null') {
                        $(params.appContainers[key])[0].remove();
                        $(integrationTarget).addClass('vue-app-main-container');
                        if (typeof $(integrationTarget).attr('id') === 'undefined'){
                            $(integrationTarget).attr('id', contId);
                        }

                    }
                }
            }
        }
    }
}, 3000);
