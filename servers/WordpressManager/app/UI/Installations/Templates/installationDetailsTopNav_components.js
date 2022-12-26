mgJsComponentHandler.addDefaultComponent('mg-wp-installation-details-top-nav', {
    template : '#t-mg-wp-installation-details-top-nav',
    props: [
        'component_id',
        'component_namespace',
        'component_index'
    ],
    data : function() {
        return {
            data: {
                domain: null
            },            
        };
    },
    created: function () {
        var self = this;
        /*self.$nextTick(function() {
            self.loadAjaxData();
        });*/
    },
    methods: {
        loadAjaxData: function() {
        },
        loadModal: function(event, targetId, namespace, index){
            let ind = $(event.target).closest('.lu-widget').index();
               mgPageControler.vueLoader.loadModal(
                    event, targetId,
                    typeof namespace !== 'undefined' ? namespace : getItemNamespace(targetId)
                    );    
        },
        redirect: function(event, targetId ){
            let ind = $(event.target).closest('.lu-widget').index();
            window.open(targetId.rawUrl ,'_self');
        },
    }
});
