
function closeCredentialsModal(){
    mgPageControler.modalInstance.closeModal();
}

function mgTestConnection(appInstance, params, event, namespace, index) {

    var actionId = $(event.target).first().parents('tr').first().attr('actionid');

    var requestParams = {
        loadData: index,
        namespace: namespace,
        index: index,
        actionId: actionId
    };
    if(event.target.getAttribute('originall-button-icon')) return;
    appInstance.showSpinner(event);

    var result = appInstance.vloadData(requestParams)
        .done(function (data) {
            appInstance.addAlert(data.data.status, data.data.message);

            appInstance.hideSpinner(event);
        }).fail(function() {
            console.log('Action Failed');
            appInstance.hideSpinner(event);
        });
}


mgJsComponentHandler.addDefaultComponent('mg-service-form', {
    template: '#t-mg-service-form',
    props: [
        'component_id',
        'component_namespace',
        'component_index'
    ],
    data: function () {
        return {
            remoteSourceFields: ['credentialsType'],
            customFields: [],
            options: {},
            selectedValues: {},
            selectInstances: {}
        };
    },
    created: function () {
        this.$nextTick(function () {
            this.initSelects();
        })
    },
    methods: {
        initSelects: function(){
            this.initRemoteSourceSelects();
        },
        initRemoteSourceSelects: function(){
            for (var key in this.remoteSourceFields)
            {
                this.initRemoteSourceSelect(this.remoteSourceFields[key]);
            }
        },
        initRemoteSourceSelect: function(fieldName){
            if (typeof this.selectInstances[fieldName] !== "undefined")
            {
                this.selectInstances[fieldName].destroy();
            }
            var selSelect = $('form[id="' + this.component_id + '"]').first().find('select[name="' + fieldName + '"]').first().selectize({
                valueField: 'key',
                labelField: 'value',
                searchField: 'value',
                create: false,
                plugins: ['remove_button'],
                copyClassesToDropdown: false,
                options: this.options[fieldName],
                items: this.selectedValues[fieldName]
            });

            this.selectInstances[fieldName]  = selSelect[0].selectize;

            var self = this;
            this.selectInstances[fieldName].on('change', function(value){
                self.selectedValues[fieldName] = value;
                self.selectChangedCallback(fieldName, value);
            });
        },
        selectChangedCallback: function(fieldName, value) {

            if (fieldName === 'credentialsType'){
                this.loadCustomFields();
            }
        },
        loadCustomFields: function(){
            var self = this;
            
            $("#mgModalContainer .customfield").remove();
            $('#mgModalContainer').append('<div id="mg-full-modal-wrapper" class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="1"><div class="lu-preloader lu-preloader--sm"></div></div>');
            var response = this.loadAjaxData({index: 'customFields', formData: this.getFormData()}).done(function(data){
                if (typeof data.data.rawData !== 'undefined'){
                    self.customFields = data.data.rawData.fields;
                } else {
                    self.customFields = [];
                }
                self.$nextTick(function() {
                    $('#mg-full-modal-wrapper').remove();
                    $("#mgModalContainer").find(".lu-tooltip").each(function () {
                        clearEventListener(this);
                    });
                    tempCustomFields = JSON.parse(JSON.stringify(self.customFields))

                    for (const property in tempCustomFields) {
                        $("[name=" + property + "]").parent().parent().find("i").attr('data-title', tempCustomFields[property].description)
                    }
                    initModalTooltips();

                    this.convertTextFieldsToPassword();
                });
            }).fail(function(){
                $('#mg-full-modal-wrapper').remove();
            });

            return response;
        },
        loadRemoteSourceData: function (fieldName){
            this.options[fieldName] = [];
            this.addLoader(fieldName);
            var self = this;
            var response = this.loadAjaxData({index: fieldName, formData: this.getFormData()}).done(function(data){
                for (var key in data.data.rawData.options) {
                    if (!data.data.rawData.options.hasOwnProperty(key)) {
                        continue;
                    }

                    var tmpOpt = {};
                    tmpOpt[key] = data.data.rawData.options[key];
                    self.options[fieldName].push({}[key] = data.data.rawData.options[key]);
                }
                self.selectedValues[fieldName] = (typeof data.data.rawData.selected === 'string' || typeof data.data.rawData.selected === 'number')
                    ? [data.data.rawData.selected] : data.data.rawData.selected;

                self.removeLoader(fieldName);
                self.$nextTick(function() {
                    self.initRemoteSourceSelect(fieldName);
                    self.selectChangedCallback(fieldName, self.selectedValues[fieldName]);
                });
            }).fail(function(){
                self.removeLoader(fieldName);
            });

            return response;
        },
        loadAjaxData: function(params) {
            var self = this;

            var requestParams = {
                loadData: self.component_id,
                namespace: self.component_namespace,
                index: self.component_index,
                mgformtype : 'updateFormStructure',
            };

            for (var key in params) {
                requestParams[key] = params[key];
            }

            return mgPageControler.vueLoader.vloadData(requestParams);
        },
        addLoader: function(fieldName)
        {
            var selectizeLoader = '<div class="lu-preloader lu-preloader--sm mg-selectize-loader"></div>';
            $('form[id="' + this.component_id + '"]').first().find('select[name="' + fieldName + '"]').parent().find('.selectize-input').first().append(selectizeLoader);
        },
        removeLoader: function (fieldName) {
            $('form[id="' + this.component_id + '"]').first().find('select[name="' + fieldName + '"]').parent().find('.mg-selectize-loader').remove();
        },
        getFormData: function () {
            var fieldValues = {};
            for (var key in this.selectedValues) {
                if (!this.selectedValues.hasOwnProperty(key)) {
                    continue;
                }
                fieldValues[key] = this.selectedValues[key];
            }

            return fieldValues;
        },
        convertTextFieldsToPassword: function()
        {
            $.each(this.customFields, function (field, object) {
                if(object.type == 'password')
                    $("[name=" + field + "]").attr('type', 'password')
            });
        }
        
    }
});

