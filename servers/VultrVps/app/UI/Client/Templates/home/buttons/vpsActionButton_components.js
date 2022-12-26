mgJsComponentHandler.addDefaultComponent('mg-vps-action-button', {
    template: '#t-mg-vps-action-button',
    props: [
        'component_id',
        'component_namespace',
        'component_index',
        'status_component_id'
    ],
    data: function () {
        return {
            status: 'disabled',
            updateInProgress: false,
            loading: false
        };
    },
    created: function () {
        var self = this;
        mgEventHandler.on('StatusWidgetDataUpdated', this.status_component_id, function (componentId, data) {
            self.updateStatus();
            self.updateInProgress = false;
        }, 2000);
        mgEventHandler.on('StatusWidgetDataUpdateStarted', this.status_component_id, function (componentId, data) {
            self.updateInProgress = false;
        }, 2000);
        mgEventHandler.on('ModalLoaded', null, function (componentId, data) {
            self.loading = false;
        });
    },
    methods: {
        loadModal: function (event, targetId, namespace, index, params, addSpinner) {
            this.loading = true;
            mgPageControler.vueLoader.loadModal(event, targetId, namespace, index, params, false);
        },

        updateStatus: function () {
            var status = this.$store.getters.getComponentData(this.status_component_id, this.component_id, 'buttonsStates');

            this.status = status;
        }
    }
});
