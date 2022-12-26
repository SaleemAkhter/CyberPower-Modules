function wpOnThemePackageCreatedAjaxDone(data) {
    var id = data['htmlData']['id'];
    $("#generalTab input[name=id]").val(id);
    window.location.href='addonmodules.php?module=WordpressManager&mg-page=Themes&mg-action=update&id='+id;
}

//Add New
mgEventHandler.on('DatatableDataLoaded', 'themeItemsAdd', function(id, params){
    var show = params.data.length <= 0;
    $("#themeItemsAdd .alert-info").toggle(show);
}, 1000);

function wpOnThemePackageItemCreated(data) {
    mgPageControler.vueLoader.refreshingState = ['themeItems'];
    mgPageControler.vueLoader.runRefreshActions();
}

if(!window.location.href.match(/id\=/)){
    $(".lu-app-main .vue-app-main-container .lu-widget__nav a").eq(1).attr("disabled",true);
}