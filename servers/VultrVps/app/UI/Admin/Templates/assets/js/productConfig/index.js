//replace url wrapper
var mgUrlParser = {
    oldMgUrlParser: mgUrlParser,

    getCurrentUrl: function () {
        var url = this.oldMgUrlParser.getCurrentUrl();
        return url.replace("action=edit", "action=module-settings").replace("&success=true", "");
    }
};

function refreshAmis() {
    for (var key in mgPageControler.vueLoader.$children) {
        if (!mgPageControler.vueLoader.$children.hasOwnProperty(key)) {
            continue;
        }

        if (mgPageControler.vueLoader.$children[key].component_id === 'ami') {
            mgPageControler.vueLoader.$children[key].loadAjaxData();
            return;
        }
    }
}

mgEventHandler.on('DatatableCreated', 'searchImages', function (id, params) {
    params.datatable.noData = true;
});
