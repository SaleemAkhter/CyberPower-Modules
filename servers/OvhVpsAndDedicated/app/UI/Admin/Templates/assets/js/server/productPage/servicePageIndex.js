
function redirectToUrlCustom(params) {
    window.open(params.rawData.data.url);
}

mgJsComponentHandler.addDefaultComponent('mg-remote-work-loader', {
    template: '#t-mg-remote-work-loader',
    props: [
        'component_id',
        'component_namespace',
        'component_index'
    ],

    data: function () {
        return {
            state: 'loading',
            availableStates: ['loading', 'finished', 'pending'],
            keepLoading: true,
        };
    },
    created: function () {
        this.startLoading();
        var self = this;

        mgEventHandler.on('ModalClosed', "ipmiButton", function(appId, params){
            self.modalClosed();
        });

    },
    methods: {
        startLoading: function(){
            var self = this.loadAjaxData();
        },
        modalClosed: function() {
            var self = this;
            self.keepLoading = false;
            redirectIPMI = false;
        },
        loadAjaxData: function() {
            var self = this;

            if(self.keepLoading === false)
            {
                return;
            }

            var tmpFormDataHandler = new mgFormControler('ipmiActionForm');
            var formData = tmpFormDataHandler.getFieldsData();
            if(formData.formData.task === 'undefined')
            {
                mgPageControler.destructModal();
                return;
            }

            var requestParams = {
                loadData: self.component_id,
                namespace: self.component_namespace,
                index: self.component_index,

                type: formData.formData.type,
                ttl: formData.formData.ttl,
                task: formData.formData.task
            };

            var response = mgPageControler.vueLoader.vloadData(requestParams);
            return response.done(function(data){
                if (typeof data.data !== 'undefined' && typeof data.data.rawData !== 'undefined' && typeof data.data.rawData.state !== 'undefined')
                {
                    data = data.data.rawData;
                    if (jQuery.inArray(data.state,  self.availableStates))
                    {
                        self.state = data.state;
                        if (self.state === 'finished' && typeof data.additionalData['redirectUrl'] !== undefined)
                        {
                            if(self.keepLoading === false) //modalClosed
                            {
                                return;
                            }
                            var win = window.open(data.additionalData['redirectUrl'], '_blank');
                            mgPageControler.destructModal();
                            win.focus();
                        }
                    }
                    else
                    {
                        setTimeout(function(){
                            self.loadAjaxData();
                        }, 1000);
                    }
                }
                else if(data.data.status === 'error')
                {
                    mgPageControler.destructModal();
                    return;
                }
                else
                {
                    setTimeout(function(){
                        self.loadAjaxData();
                    }, 1000);
                }
            }).fail(function(){
                setTimeout(function(){
                    self.loadAjaxData();
                }, 1000);
            });
        }
    }
});



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
        for (var key in mgPageControler.modalInstance.$children) {
            if (!mgPageControler.modalInstance.$children.hasOwnProperty(key)) {
                continue;
            }

            var childId = mgPageControler.modalInstance.$children[key].component_id;

            if($.inArray(childId, $list) !== -1)
            {
                if (Object.keys(mgPageControler.modalInstance.$children[key].reload_fields_ids).length === 0)
                {
                    mgPageControler.modalInstance.$children[key].reload_fields_ids.thisdoesnotexist =  'thisdoesnotexist';
                }

                await self.getPromise(key).then(function(){
                    // self.ajaxComponents[key].status = 'completed';
                });

                if(mgPageControler.modalInstance.$children[key].reload_fields_ids.thisdoesnotexist)
                {
                    delete mgPageControler.modalInstance.$children[key].reload_fields_ids.thisdoesnotexist;
                }
            }
        }
    },
    getPromise: function(key) {
        var self = this;
        return new Promise(function(resolve, reject) {
            mgPageControler.modalInstance.$children[key].loadAjaxData().then(function(data){
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
        console.log($fieldstoreload[appId]);
        mgOvhConfigOptionsFormController.reloadSelects($fieldstoreload[appId]);

        mgOvhConfigOptionsFormController.updateForm();
    }, 100);
});



