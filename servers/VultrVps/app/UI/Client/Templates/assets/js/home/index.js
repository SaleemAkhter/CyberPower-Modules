function changePasswordElement() {
    var type = jQuery('.elementPasswordInput').attr('type');

    if(type == "password"){
        jQuery('.elementPasswordIcon').removeClass('lu-zmdi-eye-off');
        jQuery('.elementPasswordIcon').addClass('lu-zmdi-eye');
        jQuery('.elementPasswordInput').attr('type', 'text')
    }
    else{
        jQuery('.elementPasswordIcon').addClass('lu-zmdi-eye-off');
        jQuery('.elementPasswordIcon').removeClass('lu-zmdi-eye');
        jQuery('.elementPasswordInput').attr('type', 'password')
    }
}

