mgJsComponentHandler.addDefaultComponent('mg-wp-details', {
    template : '#t-mg-wp-details',
    props: [
        'component_id',
        'component_namespace',
        'component_index'
    ],
    data : function() {
        return {
            data: {
                installation:[],
                details:{
                    userins: {
                        live_ins: {
                            site_name: null
                        }
                    },
                    
                },
            },
            loading_state: false,
             passwordShow: false,
            
        };
    },
    created: function () {
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
            return response.done(function(data){
                self.data = data.data.rawData.data;
                self.loading_state = false;
            }).fail(function(){
               self.loading_state = false;
            });
        },
        upgradePlugins:function(){
            var self = this;
            self.loading_state = true;

            var requestParams = {
                loadData: self.component_id,
                namespace: self.component_namespace,
                index: self.component_index
            };

            var response = mgPageControler.vueLoader.vloadData(requestParams);
            return response.done(function(data){
                self.data = data.data.rawData.data;
                self.loading_state = false;
            }).fail(function(){
               self.loading_state = false;
            });
        },
        upgradeThemes:function(){
            var self = this;
            self.loading_state = true;

            var requestParams = {
                loadData: self.component_id,
                namespace: self.component_namespace,
                index: self.component_index
            };

            var response = mgPageControler.vueLoader.vloadData(requestParams);
            return response.done(function(data){
                self.data = data.data.rawData.data;
                self.loading_state = false;
            }).fail(function(){
               self.loading_state = false;
            });
        }

    }
});
