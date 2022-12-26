//Install
function wpOnThemeInstalledAjaxDone(data) {
    mgPageControler.vueLoader.refreshingState = ['mg-theme-installed']; 
    mgPageControler.vueLoader.runRefreshActions();
}
//Add New
mgEventHandler.on('DatatableDataLoaded', 'mg-theme-install', function(id, params){
    if(params.data.length <= 0){
        $("#mg-theme-install .alert-info").show();
    }else{
        $("#mg-theme-install .alert-info").hide();
    };
}, 1000);