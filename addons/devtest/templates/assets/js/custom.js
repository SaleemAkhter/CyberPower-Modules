//* YOUR CUSTOMIZED JAVASCRIPT *//
$( document ).ready(function() {
    $(".intl-tel-input").remove();
    $("#populatedCountryCodephonenumber").removeAttr("value");
    $("#populatedCountryCodephonenumber").prev().css("display", "inline-block");
    $('#populatedCountryCodephonenumber').clone().attr({type: "tel", name: "phonenumber", id: "inputPhone", placeholder: "+1 123 123 1234"}).insertAfter('#populatedCountryCodephonenumber').prev().remove();
});