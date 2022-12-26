mgJsComponentHandler.addDefaultComponent('base-control-button', {
    template: '#t-base-control-button',
    props: [
        'component_id',
        'component_namespace',
        'component_index'
    ],
    data: function () {
        return {
            loading_state: false
        };
    },
    created: function () {
        var self = this;
        mgEventHandler.on('ModalLoaded', null, function () {
            self.loading_state = false;
        });
        mgEventHandler.on('ModalLoadFailed', self.component_id, function () {
            self.loading_state = false;

        });
    },
    methods: {
        loadModal: function (event, id, namespace, index, params, addSpinner) {
            this.loading_state = true;
            mgPageControler.vueLoader.loadModal(event, id, namespace, index, params, false);
        }
    }
});
