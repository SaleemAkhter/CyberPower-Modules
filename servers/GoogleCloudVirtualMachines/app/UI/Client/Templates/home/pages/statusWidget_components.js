mgJsComponentHandler.addDefaultComponent('mg-service-actions', {
    template: '#t-mg-service-actions',
    props: [
        'component_id',
        'component_namespace',
        'component_index'
    ],
    data : function() {
        return {
            instanceState: null,
            loadingState: false,
            exists: true,
            data: {
                instanceDetails: {
                    InstanceDetails: {
                        title: '',
                        details: []
                    }
                }
            },
            statusColor: 'lu-label lu-label--lg lu-label--default lu-label--status mg-status-title'
        };
    },
    created: function () {
        this.instanceState = '';
        var self = this;
        setTimeout(function () {
            self.loadAjaxDataRecurring();
        }, 10);
    },
    methods: {
        loadAjaxDataRecurring: function(){
            var self = this;
            self.loadAjaxData();
            setTimeout(function () {
                if (self.exists === true) {
                    self.loadAjaxDataRecurring();
                }
            }, 60000);
        },
        loadAjaxData: function() {
            var self = this;
            self.loadingState = true;
            mgEventHandler.runCallback('StatusWidgetDataUpdateStarted', self.component_id, {data: {}, widget: self});
            var requestParams = {
                loadData: self.component_id,
                namespace: self.component_namespace,
                index: self.component_index
            };

            var response = mgPageControler.vueLoader.vloadData(requestParams);
            response.done(function(data){
                Vue.set(self, 'data', data.data.rawData);
                self.loadingState = false;
                self.instanceState = self.uppercaseFirstLetter(self.data.status);
                self.setButtonsStates(self.data.buttonsStates);
                self.updateStatusColor(self.data.statusColor);
                self.$nextTick(function(){
                    mgEventHandler.runCallback('StatusWidgetDataUpdated', self.component_id, {data: self.data, widget: self});
                });
            }).fail(function(){
                self.$nextTick(function(){
                    mgEventHandler.runCallback('StatusWidgetDataUpdated', self.component_id, {data: self.data, widget: self});
                });
                self.loadingState = false;
            });
        },
        setButtonsStates: function(states){
            this.$store.commit('updateComponentData', {componentName: this.component_id, optionName: 'buttonsStates', optionValue: states});
        },
        updateStatusColor: function(newColor){
            if ($.inArray(newColor, ['success', 'danger', 'warning', 'default']) >= 0) {

                this.statusColor =  newColor;
            }
        },
        uppercaseFirstLetter: function (stringValue) {
          //  return stringValue.charAt(0).toUpperCase() + stringValue.slice(1);
        },
        detailsAvaliable: function(){
            if (typeof this.data.instanceDetails === 'undefined')
            {
                return false;
            }

            if (typeof this.data.instanceDetails === 'object' && Object.keys(this.data.instanceDetails).length > 0)
            {
                return true;
            }

            return this.data.instanceDetails.length > 0;
        },
        $destroy: function () {
            this.exists = false;
        }
    }
});
