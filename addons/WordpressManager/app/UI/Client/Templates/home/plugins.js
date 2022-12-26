//Install
function wpOnPluginInstalledAjaxDone(data) {
    mgPageControler.vueLoader.refreshingState = ['mg-plugins-installed']; 
    mgPageControler.vueLoader.runRefreshActions();
    console.log('asdasdwerwer');
}
//Add New
mgEventHandler.on('DatatableDataLoaded', 'mg-plugin-install', function(id, params){
    if(params.data.length <= 0){
        $("#mg-plugin-install .alert-info").show();
    }else{
        $("#mg-plugin-install .alert-info").hide();
    };
}, 1000);
