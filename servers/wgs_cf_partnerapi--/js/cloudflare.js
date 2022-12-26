function callcfaction(mode) {
    $("#cfaction").val(mode);
    $("#cfactionform").submit();
}

function doUnserialize(str) {
    var formvalues = new Object();
    str.split("&").forEach(function (part) {
        var item = part.split("=");
        formvalues[item[0]] = decodeURIComponent(item[1]);
    });
    return formvalues;
}

function toggleGraph(obj, id) {
    jQuery('.graphs').each(function () {
        jQuery(this).hide();
    });
    jQuery('.active-tab').each(function () {
        jQuery(this).removeClass('active-tab');
    });
    jQuery('#' + id).show();
    jQuery(obj).addClass('active-tab');
}

jQuery(document).ready(function () {
    jQuery("#choose_plan").change(function () {
        jQuery("#plan_price_field").remove();
        if (jQuery(this).find("option:selected").val() != "") {
            jQuery("#choose_plan").css("border", "none");
            jQuery("#planText").text(jQuery(this).find("option:selected").text());
            var price = jQuery(this).find("option:selected").attr('price').substring(1);
            jQuery("#planPrice").text(jQuery(this).find("option:selected").attr('price'));
            jQuery("#upgradeForm").append('<input type="hidden" name="plan_price" id="plan_price_field" value="' + price + '">');
        } else {
            jQuery("#planText").text("");
            jQuery("#planPrice").text("");
        }
    });
});
function manageDomain(obj, domain) {

    jQuery('#website').val(domain);

    jQuery('#cloudflareform').submit();

}

function deleteDomain(obj, domain) {

    var warningMsg = confirm("{$wgs_lang.cf_domains_domain_warning} " + domain + " ?");
    if (warningMsg) {
        jQuery('#deletewebsite').val(domain);

        jQuery('#deletedomainform').submit();
    }
}

function upgradeDomain(obj, domain, plan, zone_id) {
    jQuery("#plan_zone, #zone_id").remove();
    jQuery("#upgradeForm").append('<input type="hidden" name="zone" id="plan_zone" value="' + domain + '">');
    jQuery("#upgradeForm").append('<input type="hidden" name="zone_id" id="zone_id" value="' + zone_id + '">');
    jQuery('#upgradeModal').modal('show');
    jQuery("#current_plan").val(plan);
}

function upgradeSubScription(obj) {
    if (jQuery("#choose_plan").val() == "") {
        jQuery("#choose_plan").focus();
        jQuery("#choose_plan").css("border", "1px solid #ff0000");
    }
    else {
        jQuery("#choose_plan").css("border", "none");
        jQuery("#upgarderes").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
        jQuery(obj).css('pointer-events', 'none');
        jQuery.ajax({
            "url": "",
            "type": "post",
            "data": "upgardeSub=true&" + jQuery("#upgradeForm").serialize(),
            "success": function (data) {
                var response = jQuery.parseJSON(data);
                jQuery(obj).css('pointer-events', 'auto');
                jQuery("#upgarderes").html(response.msg);
            }
        });
    }
}