
mgEventHandler.on('AppsPreLoad', null, function(id, params){
    if (typeof params.appContainers !== 'undefined') {
        for (var key in params.appContainers) {
            if (!params.appContainers.hasOwnProperty(key)) {
                continue;
            }
            var isIntegration = $(params.appContainers[key]).parents('.mg-integration-container');
            if (isIntegration.length === 1) {
                var tempIntCode = $(params.appContainers[key])[0].outerHTML;
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
}, 1000);
