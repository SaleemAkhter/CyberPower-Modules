function wpOnPluginPackageCreatedAjaxDone(data) {
    var id = data['htmlData']['id'];
    $("#generalTab input[name=id]").val(id);
    window.location.href='addonmodules.php?module=WordpressManager&mg-page=Plugins&mg-action=update&id='+id;
}
function wpPluginsSearchAjaxDone(data , vuePageControler) {
    if(data.records.length <= 0){
        $("#pluginsTab #pluginItemsAdd .alert-info").show();
    }else{
        $("#pluginsTab #pluginItemsAdd .alert-info").hide();
    }
}

//Add New
mgEventHandler.on('DatatableDataLoaded', 'pluginItemsAdd', function(id, params){
    var show = params.data.length <= 0;
    $("#pluginsTab #pluginItemsAdd .alert-info").toggle(show);
}, 1000);

function wpOnPluginPackageItemCreated(data) {
    mgPageControler.vueLoader.refreshingState = ['pluginItems']; 
    mgPageControler.vueLoader.runRefreshActions();
}

if(!window.location.href.match(/id\=/)){
    $(".lu-app-main .vue-app-main-container .lu-widget__nav a").eq(1).attr("disabled",true);
}