mgJsComponentHandler.addDefaultComponent('mg-date-picker', {
    template: '#t-mg-date-picker',
    props: [
        'component_id',
        'component_namespace',
        'component_index',
        'predefined_date',
        'date_format'
    ],
    data : function() {
        return {
            date: null
        }
    },
    components : {
        vuejsDatepicker
    },
    created: function () {
        if(this.predefined_date.length === 0)
        {
            this.date = new Date();
        }
        else
        {
            this.date = new Date(moment(this.predefined_date, this.date_format).toString());
        }
    },
    methods: {
        customFormatter: function(date) {
            return moment(date).format(this.date_format);
        },
    }
});

