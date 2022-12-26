mgJsComponentHandler.addDefaultComponent('mg-wp-installation-details', {
    template: '#t-mg-wp-installation-details',
    props: [
        'component_id',
        'component_namespace',
        'component_index'
    ],
    data: function() {
        return {
            data: {
                installations: [],
                domain: null
            },
        };
    },
    created: function() {
        var self = this;
        self.$nextTick(function() {
            self.loadAjaxData();
        });
    },
    methods: {
        loadAjaxData: function() {
            var self = this;
            self.loading_state = true;

            var requestParams = {
                loadData: self.component_id,
                namespace: self.component_namespace,
                index: self.component_index
            };

            var response = mgPageControler.vueLoader.vloadData(requestParams);
            return response.done(function(data) {
                self.data = data.data.rawData.data;
                self.loading_state = false;

                self.$nextTick(function() {
                    //$('#mg-wp-installation-details [data-toggle="lu-tooltip"], [data-tooltip]').luTooltip({});
                });
            }).fail(function() {
                self.loading_state = false;
            });
        },
        loadModal: function(event, targetId, namespace, index) {
            let ind = $(event.target).closest('.lu-widget').index();
            mgPageControler.vueLoader.loadModal(event, targetId,
                typeof namespace !== 'undefined' ? namespace : getItemNamespace(targetId),
                typeof index !== 'undefined' ? index : getItemIndex(targetId), [{
                    name: 'wpid',
                    value: this.data.installations[ind].id
                }]);
        },
        redirect: function(event, targetId) {
            let ind = $(event.target).closest('.lu-widget').index();
            window.location.assign(targetId.rawUrl + '&wpid=' + this.data.installations[ind].id);
        },
    }
});