
mgJsComponentHandler.addDefaultComponent('mg-config-hint-widget', {
    template : '#t-mg-config-hint-widget',
    props: [
        'component_id',
        'component_namespace',
        'component_index',
        'force_open',
        'component_is_hidden'
    ],
    data : function() {
        return {
            show: true,
            closeButtonIconClass: 'lu-btn__icon lu-zmdi lu-zmdi-close'
        };
    },
    created: function () {
        this.show = !this.component_is_hidden;
        if (this.force_open)
        {
            this.show = true;
        }
    },
    methods: {
        hideHintsBox: function(){
            var self = this;
            this.showCloseButtonLoader();
            mgPageControler.vueLoader.vloadData({
                loadData: this.component_id,
                namespace: this.namespace,
                index: this.component_index
            }).done(function(data){
                self.showCloseButtonIcon();
                var data = data.data;
                mgPageControler.vueLoader.addAlert(data.status, data.message);
                setTimeout(function(){
                    self.show = false;
                },1000);
            }).fail(function() {
                self.showCloseButtonIcon();
            });
        },
        showCloseButtonLoader: function() {
            this.closeButtonIconClass = 'lu-btn__icon lu-preloader lu-preloader--sm';
        },
        showCloseButtonIcon: function() {
            this.closeButtonIconClass = 'lu-btn__icon lu-zmdi lu-zmdi-close';
        }
    }
});
