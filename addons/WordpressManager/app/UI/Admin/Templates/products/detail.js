
function wpOnPluginBlockedCreatedAjaxDone(data) {
    mgPageControler.vueLoader.refreshingState =  ['mg-plugins-blocked']; 
    mgPageControler.vueLoader.runRefreshActions();
}

function wpOnThemeBlockedCreatedAjaxDone(data) {
    mgPageControler.vueLoader.refreshingState =  ['mg-themes-blocked'];
    mgPageControler.vueLoader.runRefreshActions();
}
//Add New
mgEventHandler.on('DatatableDataLoaded', 'mg-plugin-block', function(id, params){
    if(params.data && params.data.length <= 0){
        $("#mg-plugin-block .alert-info").show();
    }else{
        $("#mg-plugin-block .alert-info").hide();
    };
}, 1000);

mgEventHandler.on('DatatableDataLoaded', 'mg-theme-block', function(id, params){
    if(params.data && params.data.length <= 0){
        $("#mg-theme-block .alert-info").show();
    }else{
        $("#mg-theme-block .alert-info").hide();
    };
}, 1000);


function wpProductSaveAjaxDone(data) {
    window.location.reload();
}

$(document).ready(function () {

    //auto install
    $("select[name=autoInstall]").on("change",function (e) {
        switch ($(this).val()) {
            case "0":
                $("#autoInstallScript").closest('.lu-form-group').hide();
                $("#autoInstallInstanceImage").closest('.lu-form-group').hide();
                $("select[name=autoInstallEmailTemplate]").closest('.lu-form-group').hide();
                $("select[name=autoInstallSoftProto]").closest('.lu-form-group').hide();
                break;
            case 'instanceIamge':
                $("#autoInstallInstanceImage").closest('.lu-form-group').show();
                $("#autoInstallScript").closest('.lu-form-group').hide();
                $("select[name=autoInstallEmailTemplate]").closest('.lu-form-group').show();
                $("select[name=autoInstallSoftProto]").closest('.lu-form-group').show();
                break;
            case 'script':
                $("#autoInstallInstanceImage").closest('.lu-form-group').hide();
                $("#autoInstallScript").closest('.lu-form-group').show();
                $("select[name=autoInstallEmailTemplate]").closest('.lu-form-group').show();
                $("select[name=autoInstallSoftProto]").closest('.lu-form-group').show();
                break;
            default:
                $("#autoInstallScript").closest('.lu-form-group').hide();
                $("#autoInstallInstanceImage").closest('.lu-form-group').hide();
                $("select[name=autoInstallEmailTemplate]").closest('.lu-form-group').hide();
                $("select[name=autoInstallSoftProto]").closest('.lu-form-group').hide();
        }
    });
    $("select[name=autoInstall]").trigger("change");
})
