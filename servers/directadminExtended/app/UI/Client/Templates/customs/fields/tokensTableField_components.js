
mgJsComponentHandler.addDefaultComponent('mg-tokens-table', {
    template: '#t-mg-tokens-table',
    props: [
        'component_id',
        'component_namespace',
        'component_index',
        'tokens',
    ],
    data : function() {
        return {
            // tokens: null,
            search:null
        }
    },
    mounted: function () {
        var self = this;
        self.tokens = jQuery.parseJSON(self.tokens);
        console.log(self.tokens );
    },
    // watch: {
    //     password:  function (newData, oldData) {
    //         this.calculatePasswordStr(newData, oldData)
    //     }
    // },
    methods: {

    }
});

