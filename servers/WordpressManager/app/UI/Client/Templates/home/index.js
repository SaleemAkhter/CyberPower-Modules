//Push To Live WordPress 
mgEventHandler.on('ModalLoaded', 'pushToLiveButton', function(id, params){
  
  if(id != 'pushToLiveButton'){
      return;
  }
  $(".mg-wrapper input[name=custom_push]").trigger('change');

}, 1000);
$(".mg-wrapper").on("change","input[name=custom_push]",function(e){
    var show = $(this).is(":checked");
    $("#pushToLiveForm .lu-form-group").toggle(show);
    $("#pushToLiveForm .lu-form-check").eq(1).toggle(show);
    $("#pushToLiveForm .lu-form-check").eq(2).toggle(show);
    $("#pushToLiveForm .lu-form-check").eq(3).toggle(show);
});

function wpOnInstallationCreated(data) {
    console.log("asdasdas");
    if(!$("#productDataTable").size()){
        return;
    }
    mgPageControler.vueLoader.refreshingState = ['productDataTable']; 
    mgPageControler.vueLoader.runRefreshActions();
}
function wpInstallationdeleteAjaxDone(data) {
    console.log("wpInstallationdeleteAjaxDone",data);
    window.location.href='index.php?m=WordpressManager';
}
function reloadInstallationIndexPage(data) {
    console.log("wpInstallationdeleteAjaxDone",data);
    // window.location.href='index.php?m=WordpressManager';
}
