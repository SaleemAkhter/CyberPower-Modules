//Push To Live WordPress
mgEventHandler.on('ModalLoaded', 'pushToLiveButton', function(id, params){

  if(id != 'pushToLiveButton'){
      return;
  }
  $(".mg-wrapper input[name=custom_push]").trigger('change');

}, 1000);

var lastprogress=1;
function installationInProgress(data) {
    jQuery("#installationImportForm").hide();
    jQuery("#progress_bar").removeClass("hidden");
    if(data.status=="success"){
        var countTime = 0;
        var storeTimeInterval = setInterval(function(){
            ++countTime;
            jQuery.ajax({
                'method':"POST",
                'data':{'hostingId':data.htmlData.hostingId,'ref':data.htmlData.checkid},
                success:function(response){
                    if(parseInt(response.message.progress)==100){
                        clearInterval(storeTimeInterval);
                        jQuery("#insurl").attr("href",data.htmlData.url).text(data.htmlData.url);
                        jQuery("#adminurl").attr("href",response.message.loginurl).text(data.htmlData.url+"/wp-admin");

                        increaseProgress(100);


                    }else{
                        jQuery("#progress_txt").text(response.message.message);

                        // jQuery("#progress_color").attr("width",response.message.progress+"%");
                    }
                    if(parseInt(response.message.progress)<=parseInt(lastprogress) ){
                        if(lastprogress<95){
                            increaseProgress(parseInt(lastprogress)+5,true);
                        }
                    }else{
                        increaseProgress(response.message.progress);
                    }
                }
            });
            // if(countTime == 50){
            //     clearInterval(storeTimeInterval);
            // }
        }, 10000);
    }



    // if(!$("#productDataTable").size()){
    //     return;
    // }
    // mgPageControler.vueLoader.refreshingState = ['productDataTable'];
    // mgPageControler.vueLoader.runRefreshActions();
}
function increaseProgress(progress,fake=false) {
    if(parseInt(progress)>parseInt(lastprogress)){

        var progressTimeInterval = setInterval(function(){
            if(lastprogress<=100){
                jQuery("#progress_color").attr("width",lastprogress+"%");
                jQuery("#progress_percent").text(lastprogress+"%");
                if(progress==lastprogress || lastprogress>100){
                    clearInterval(progressTimeInterval);
                }
            }
            if(progress==100){
                clearInterval(progressTimeInterval);
                jQuery("#completed").removeClass("hidden");
                jQuery("#progress_bar").addClass("hidden");
            }
            if(fake && lastprogress<95){
                ++lastprogress;
            }else if(!fake){
                ++lastprogress;
            }

            if(progress==lastprogress){
                clearInterval(progressTimeInterval);
            }

        }, 300);
    }
}

function checktab(type){
    jQuery("#formType").val(type);
    jQuery("#installationImportForm").submit();
}



jQuery("#installationImportForm").append("<div class='importprogress hidden'></div>");

