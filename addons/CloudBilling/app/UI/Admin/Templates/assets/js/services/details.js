
mgJsComponentHandler.addDefaultComponent('mg-service-form', {
    template: '#t-mg-service-form',
    props: [
        'component_id',
        'component_namespace',
        'component_index'
    ],
    data: function () {
        return {
            remoteSearchFields: [],
            remoteSourceFields: [],
            selectFields: ['billingType'],
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
        this.$nextTick(function(){
            this.loadCustomFields();
        });
    },
    methods: {
        initSelects: function(){
            this.initRemoteSearchSelects();
            this.initRemoteSourceSelects();
            this.initSelectFields();
        },
        initSelectFields: function(){
            for (var key in this.selectFields)
            {
                this.initNormalSelect(this.selectFields[key]);
            }
        },
        initRemoteSourceSelects: function(){
            for (var key in this.remoteSourceFields)
            {
                this.initRemoteSourceSelect(this.remoteSourceFields[key]);
            }
        },
        initRemoteSearchSelects: function(){
            for (var key in this.remoteSearchFields)
            {
                this.initRemoteSearchField(this.remoteSearchFields[key]);
            }
        },
        initNormalSelect: function(fieldName) {
            if (typeof this.selectInstances[fieldName] !== "undefined")
            {
                this.selectInstances[fieldName].destroy();
            }
            var selSelect = $('form[id="' + this.component_id + '"]').first().find('select[name="' + fieldName + '"]').first().selectize({});

            this.selectInstances[fieldName]  = selSelect[0].selectize;
            this.selectedValues[fieldName] = this.selectInstances[fieldName].getValue();

            var self = this;
            this.selectInstances[fieldName].on('change', function(value){
                self.selectedValues[fieldName] = value;
                self.selectChangedCallback(fieldName, value);
            });
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
        initRemoteSearchField: function(fieldName){
            var self = this;
            var field = $('form[id="' + this.component_id + '"]').first().find('select[name="' + fieldName + '"]');
            if (typeof field[0] !== 'undefined') {
                this.options[fieldName] = [];
                var selSelect = $(field).selectize({
                    valueField: 'key',
                    labelField: 'value',
                    searchField: 'value',
                    plugins: ['remove_button'],
                    options: self.options[fieldName],
                    load: function(query, callback) {
                        //if no search string
                        if (!query.length) {
                            // return;
                            return callback([]);
                        } else {
                            self.addLoader(fieldName);
                            var tmpres = self.loadRemoteSearchData(fieldName, query);
                            tmpres.done(function(data){
                                setTimeout(function (){
                                    callback(self.options[fieldName]);
                                },100);
                                self.removeLoader(fieldName);
                            }).fail(function(){
                                self.removeLoader(fieldName);
                            });
                        }
                    }
                });

                self.selectInstances[fieldName]  = selSelect[0].selectize;
                self.selectInstances[fieldName].on('change', function(value){
                    self.selectedValues[fieldName] = value;
                    self.selectChangedCallback(fieldName);
                });
            }
        },
        selectChangedCallback: function(fieldName, value) {
            if (fieldName === 'billingType'){
                this.loadCustomFields();
            }
        },
        loadCustomFields: function(){
            var self = this;
            $('#mgModalContainer').append('<div id="mg-full-modal-wrapper" class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="1"><div class="lu-preloader lu-preloader--sm"></div></div>');
            var response = this.loadAjaxData({index: 'customFields', formData: this.getFormData()}).done(function(data){
                if (typeof data.data.rawData !== 'undefined' && typeof data.data.rawData.fields !== 'undefined'){
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
                    this.selectizeRawSelects();
                    this.insertTextValues();
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
        loadRemoteSearchData: function (fieldName, query){
            var self = this;
            self.options[fieldName] = [];
            var response = this.loadAjaxData({index: fieldName, searchQuery: query, formData: this.getFormData()}).done(function(data){
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
            }).fail(function(){

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
        selectizeRawSelects: function(){
            rawSelectsNames = ['projectDataset'];
            projectDataset = $($('[actionid="projectDataset"]').children()[1]).html();
            
            $.each(rawSelectsNames, function(id, name) {
                customSelect = $("[name=" + name + "]");
                    
                switch (name) {
                    case 'projectDataset':
                        customSelect.val(projectDataset);
                        break;
                }
                if(customSelect.length === 1) {
                    customSelect.selectize();
                }
            })
        
        },
        insertTextValues: function (){
            project = $($('[actionid="project"]').children()[1]).html();
            labelName = $($('[actionid="labelName"]').children()[1]).html();
            $('[type="text"][name="project"]') . val(project);
            
            labelNameField = $('[type="text"][name="labelName"]');
            if(labelNameField.length == 1)
                labelNameField . val (labelName);
        }
    }
});
