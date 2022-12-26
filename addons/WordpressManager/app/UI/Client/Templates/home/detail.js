

function wpOnChangeDomainAjaxDone(data) {
    console.log('asdjaslkdklasdjasd');
    window.location.reload();
}

function wpInstallationdeleteAjaxDone(data) {
    console.log("wpInstallationdeleteAjaxDone detail",data);
    window.location.href='index.php?m=WordpressManager';
}
//Change Domain 
$(".mg-wrapper").on("change","select[name='domain']",function(e){
    if($(this).val()!="0" &&  $(".mg-wrapper input[name='newDomain']").size()){
        $(".mg-wrapper input[name='newDomain']").prop("disabled",true);
        $(".mg-wrapper input[name='newDomain']").parent('div').children('.lu-form-label').addClass("disabled");
        $(".mg-wrapper input[name='subDomain']").prop("disabled",true);
        $(".mg-wrapper input[name='subDomain']").parent('div').children('.lu-form-label').addClass("disabled");
        $(".mg-wrapper input[name='password']").prop("disabled",true);
        $(".mg-wrapper input[name='password']").parent('div').children('.lu-form-label').addClass("disabled");
    }else if( $(".mg-wrapper input[name='newDomain']").size()){
        $(".mg-wrapper input[name='newDomain']").prop("disabled",false);
        $(".mg-wrapper input[name='newDomain']").parent('div').children('.lu-form-label').removeClass("disabled");
        $(".mg-wrapper input[name='subDomain']").prop("disabled",false);
        $(".mg-wrapper input[name='subDomain']").parent('div').children('.lu-form-label').removeClass("disabled");
        $(".mg-wrapper input[name='password']").prop("disabled",false);
        $(".mg-wrapper input[name='password']").parent('div').children('.lu-form-label').removeClass("disabled");
    }
});

function wpSslChange(data) {
    window.location.reload();
}

$(".mg-wrapper").on("change","select[name='domain']",function(e){
    
});

$(".mg-wrapper").on("change","input[name=push_db]",function(e){
    var pushFull = $(this).is(":checked");
    if(pushFull){
        $("#pushToLiveForm .lu-form-group").eq(0).addClass('disabled');
        $("#pushToLiveForm .lu-form-group").eq(1).addClass('disabled');
    }else{
        $("#pushToLiveForm .lu-form-group").eq(0).removeClass('disabled');
        $("#pushToLiveForm .lu-form-group").eq(1).removeClass('disabled');
    }
});
