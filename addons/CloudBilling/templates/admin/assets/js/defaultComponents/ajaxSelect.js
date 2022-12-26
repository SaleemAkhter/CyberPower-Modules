mgJsComponentHandler.addDefaultComponent('mg-ajax-select', {
    template : '#t-mg-ajax-select',
    props: [
        'component_id',
        'component_namespace',
        'component_index',
        'reload_fields_ids',
        'is_multiple',
        'hide_by_default_if_no_data'
    ],
    data : function() {
        return {
            options: [],
            selected: null,
            selectInstance: null
        };
    },
    created: function () {
        var self = this;

        //create reload events
        for (var key in self.reload_fields_ids) {
            if (!self.reload_fields_ids.hasOwnProperty(key)) {
                continue;
            }

            mgEventHandler.on('SelectFieldValueChanged', self.reload_fields_ids[key], function(id, params){
                self.loadAjaxData();
            });
        }

        self.$nextTick(function() {
            self.initSelect();
            self.loadAjaxData();
        });
    },
    methods: {
        initSelect: function() {
            var self = this;

            if ($('#' + self.component_id).hasClass('selectized')) {
                var selectizedInstance = $('#' + self.component_id).selectize();
                selectizedInstance[0].selectize.destroy();
            }

            var selSelect = $('#' + self.component_id).selectize({
                valueField: 'key',
                labelField: 'value',
                searchField: 'value',
                create: false,
                plugins: ['remove_button'],
                copyClassesToDropdown: false,
                options: self.options,
                items: self.selected
            });

            self.selectInstance  = selSelect[0].selectize;

            self.selectInstance .on('change', function(value){
                self.selectChangeAction();
            });
        },
        loadAjaxData: function(sendFormData) {
            var self = this;

            this.addLoader();

            var loadFormData = sendFormData ? true : false;
            for (var key in self.reload_fields_ids) {
                if (!self.reload_fields_ids.hasOwnProperty(key)) {
                    continue;
                }
                if (self.reload_fields_ids[key] !== '' && self.reload_fields_ids)
                {
                    loadFormData = true;
                    break;
                }
            }

            if (loadFormData) {
                var tmpForm = $("select[name='" + self.component_id + "']").parents('form').first();
                var tmpFormId = $(tmpForm).attr('id');
                var tmpFormDataHandler = new mgFormControler(tmpFormId);
                var formData = tmpFormDataHandler.getFieldsData();
            } else {
                var formData = {};
            }

            var requestParams = {
                loadData: self.component_id,
                namespace: self.component_namespace,
                index: self.component_index
            };

            mgPageControler.vueLoader.refreshUrl();
            mgPageControler.vueLoader.addUrlComponent('loadData', self.component_id);
            mgPageControler.vueLoader.addUrlComponent('namespace', self.component_namespace);
            mgPageControler.vueLoader.addUrlComponent('index', self.component_index);
            mgPageControler.vueLoader.addUrlComponent('ajax', '1');

            $.ajax({
                type: "POST",
                url: mgPageControler.vueLoader.targetUrl,
                data: formData,
                processData: false,
                contentType: false})
                .done(function( data ) {
                mgPageControler.vueLoader.refreshUrl();
                self.options = [];
                for (var key in data.data.rawData.options) {
                    if (!data.data.rawData.options.hasOwnProperty(key)) {
                        continue;
                    }

                    var tmpOpt = {};
                    tmpOpt[key] = data.data.rawData.options[key];
                    self.options.push({}[key] = data.data.rawData.options[key]);
                }

                self.selected = (typeof data.data.rawData.selected === 'string' || typeof data.data.rawData.selected === 'number')
                                    ? [data.data.rawData.selected] : data.data.rawData.selected;
                self.$nextTick(function() {
                    self.showSelectIfHidden();
                });
                self.$nextTick(function() {
                    self.initSelect();
                });
                mgEventHandler.runCallback('SelectFieldDataLoaded', self.component_id, {instance: self, data: data.data.rawData});
                self.removeLoader();
            }).fail(function(){
                mgPageControler.vueLoader.refreshUrl();
                self.removeLoader();
                self.$nextTick(function() {
                    self.initSelect();
                });
                mgEventHandler.runCallback('SelectFieldDataLoaded', self.component_id, {instance: self, data: data.data.rawData});
            });
        },
        showSelectIfHidden : function() {
            var self = this;
            if (self.hide_by_default_if_no_data && self.options.length > 0) {
                if ($('#' + self.component_id).parents('.lu-form-group').first().length > 0) {
                    $('#' + self.component_id).parents('.lu-form-group').first().removeAttr('hidden');
                }
            }
        },
        selectChangeAction : function() {
            mgEventHandler.runCallback('SelectFieldValueChanged', this.component_id, {data: this});
        },
        addLoader: function() {
            var selectizeLoader = '<div class="lu-preloader lu-preloader--sm mg-selectize-loader"></div>';
            $('#' + this.component_id).parent().find('.selectize-input').first().append(selectizeLoader);
        },
        removeLoader: function () {
            $('#' + this.component_id).parent().find('.mg-selectize-loader').remove();
        }
    }
});
