Object.filter = (obj, predicate) =>
Object.keys(obj)
.filter( key => predicate(obj[key]) )
.reduce( (res, key) => (res[key] = obj[key], res), {} );

var ssa='';

mgJsComponentHandler.addDefaultComponent('mg-tokens-table', {
    template: '#t-mg-tokens-table',
    props: [
    'component_id',
    'component_namespace',
    'component_index',
    'component_tokens',
    ],
    data : function() {
        return {
            tokens: [],
            search:'',
        }
    },
    mounted: function () {
        var self = this;
        self.tokens = jQuery.parseJSON(self.component_tokens);
    },

    watch: {
        search:  function (newData, oldData) {
            var self=this;
            self.tokens=[];
            var tokens=jQuery.parseJSON(self.component_tokens);
            if(!self.search.length){
                self.tokens= tokens;
            }
            var l={};
            for (var key in tokens) {
                if (!tokens.hasOwnProperty(key)) continue;

                var token = tokens[key];
                if(!token.length) continue;
                if(token.toLowerCase().includes(self.search.toLowerCase())){
                    l[key] = tokens[key];
                }
            }
            self.tokens= l;
        }
    },
    methods: {

    }
});

