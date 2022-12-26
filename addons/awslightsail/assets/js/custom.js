jQuery("#blueprintform").submit(function(e){
    e.preventDefualt();
   var datastring = jQuery("form#blueprintform").serialize();

jQuery.ajax({
    url:"",
    type:"POST",
    data: datastring,
    success: function(resp){
        console.log(resp);
    },
});
});