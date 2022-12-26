<link type="text/css" rel="stylesheet" href="/modules/servers/wgs_cloudflare_reseller/css/cloudflare.css">
{$headerhtml}
{$menu}
{literal}
    <script type="text/javascript">
        function manageDNS(m) {
            var inserbeforehtml = "<img style='margin: 20px 0 0 280px;' class='dnsloader' src='{/literal}{$moduleURL}{literal}images/load.gif' alt='Loading...'>";
            if (m === "add") {
                var sd = $("#form-adddnsrecord").serialize();
                var formvalues = doUnserialize(sd);
                if (formvalues["cfdnsname"].length === 0 && formvalues["cfdnsvalue"].length === 0) {
                    $("#form-adddnsrecord").find("input[name=cfdnsname]").css('border', '1px solid #ff0000');
                    $("#form-adddnsrecord").find("input[name=cfdnsvalue]").css('border', '1px solid #ff0000');
                    return false;
                }
                if (formvalues["cfdnsname"].length === 0) {
                    $("#form-adddnsrecord").find("input[name=cfdnsname]").css('border', '1px solid #ff0000');
                    return false;
                } else {
                    $("#form-adddnsrecord").find("input[name=cfdnsname]").css('border', 'none');
                }
                if (formvalues["cfdnsvalue"].length === 0) {
                    $("#form-adddnsrecord").find("input[name=cfdnsvalue]").css('border', '1px solid #ff0000');
                    return false;
                } else {
                    $("#form-adddnsrecord").find("input[name=cfdnsvalue]").css('border', 'none');
                }
                $('#addnewdnsmodal .modal-body').before(inserbeforehtml);
            } else if (m === "edit") {
                sd = $("#form-editdnsrecord").serialize();
                /*
                 * validate dnsname and dnscontent
                 */
                formvalues = doUnserialize(sd);
                if (formvalues["cfdnsname"].length === 0) {
                    $("#form-editdnsrecord").find("input[name=cfdnsname]").css('border', '1px solid #ff0000');
                    return false;
                } else {
                    $("#form-editdnsrecord").find("input[name=cfdnsname]").css('border', 'none');
                }
                if (formvalues["cfdnsvalue"].length === 0) {
                    $("#form-editdnsrecord").find("input[name=cfdnsvalue]").css('border', '1px solid #ff0000');
                    return false;
                } else {
                    $("#form-editdnsrecord").find("input[name=cfdnsvalue]").css('border', 'none');
                }
                $('#editdnsmodal .modal-body').before(inserbeforehtml);
            }
            $.ajax({
                url: '',
                method: "POST",
                data: "modop=custom&a=ManageCf&cf_action=manageWebsite&website={/literal}{$domain}{literal}&cfaction=dns&ajaxaction=dnsrecordpost&salt={/literal}{$salt}{literal}&user_lang={/literal}{$user_lang}{literal}&" + sd
            })
                    .done(function (resp) {
                        jQuery('.cfa_error, .cfa_success').remove();
                        $(".dnsloader").hide();
                        var res = resp.split("_");
                        if (res[0] == "E") {
                            inserbeforehtml = '<div class="cfa_error" style="width: auto !important;"><span></span>' + res[1] + '</div>';
                        } else if (res[0] == "S") {
                            inserbeforehtml = '<div class="cfa_success" style="width: auto !important;"><span></span>' + res[1] + '</div>';
                        }

                        if (m === "add") {
                            if (res[0] == "E") {
                                $('#addnewdnsmodal .modal-body').before(inserbeforehtml);
                                setTimeout(function () {
                                    $('.adddns .cfa_error').slideUp('slow');
                                }, 5000);
                            } else if (res[0] == "S") {
                                $("#deleteadddnsaction").find("input[name=messagetype]").val("success");
                                $("#deleteadddnsaction").find("input[name=message]").val(res[1]);
                                $("#deleteadddnsaction").submit();
                            }
                        } else if (m === "edit") {
                            $('#editdnsmodal .modal-body').before(inserbeforehtml);
                            if (res[0] == "E") {
                                setTimeout(function () {
                                    $('#editdnsmodal .cfa_error').slideUp('slow');
                                }, 5000);
                            } else if (res[0] == "S") {
                                setTimeout(function () {
                                    $('#editdnsmodal .cfa_success').slideUp('slow');
                                    $("#deleteadddnsaction").submit();
                                }, 3000);
                            }
                        }
                    });
        }

        /*
         * Delete DNS Record
         */

        function deleteDNS() {
            var sd = $("#form-deletedns").serialize();
            $.ajax({
                url: '',
                method: "POST",
                data: "modop=custom&a=ManageCf&cf_action=manageWebsite&website={/literal}{$domain}{literal}&cfaction=dns&ajaxaction=dnsrecordpost&salt={/literal}{$salt}{literal}&user_lang={/literal}{$user_lang}{literal}&" + sd
            })
                    .done(function (resp) {
                        var res = resp.split("_");
                        if (res[0] == "E") {
                            inserbeforehtml = '<div class="cfa_error" style="width: auto !important;"><span></span>' + res[1] + '</div>';
                        } else if (res[0] == "S") {
                            $("#deleteadddnsaction").find("input[name=messagetype]").val("success");
                            $("#deleteadddnsaction").find("input[name=message]").val(res[1]);
                            $("#deleteadddnsaction").submit();
                        }
                        $('#deletednsmodal .modal-body').before(inserbeforehtml);
                    });
        }

        $(document).ready(function () {

            var bootstrap_enabled = (typeof $().modal == 'function');
            if (bootstrap_enabled === false) {
                $.getScript("{/literal}{$systemURL}{literal}/assets/js/bootstrap.min.js", function () {
                    console.log("bootstrap loaded");
                });
            }

            /* Add DNS Record */
            $('#addnewdnsmodal').on('show.bs.modal', function () {
                var modal = $(this);
                modal.find(".cfa_error,.cfa_success").remove();
                modal.find(".modal-body").html("<img style='padding:40px 260px;' src='{/literal}{$moduleURL}{literal}images/load.gif' alt='Loading...'>");
                var dnsrecordtype = $("#addnewdnsrecord").val();
                var zoneid = "{/literal}{$zoneid}{literal}";
                $.ajax({
                    url: '',
                    method: "POST",
                    data: "modop=custom&a=ManageCf&cf_action=manageWebsite&website={/literal}{$domain}{literal}&cfaction=dns&ajaxaction=adddnsrecord&salt={/literal}{$salt}{literal}&user_lang={/literal}{$user_lang}{literal}&dnstype=" + dnsrecordtype
                })
                        .done(function (resp) {
                            modal.find(".modal-body").html(resp);
                        });
            });
            /* Edit DNS Record */
            $(".edit").click(function () {
                if ($(this).attr('data-enable_dns') == 1) {
                    $(this).removeAttr('data-toggle');
                    jQuery('#showmsg').html('<div class="cfa_error enabledisablemsg" style="width: auto !important;"><span></span>{/literal}{$wgs_lang.cf_dns_cant_edit}{literal}</div>');
                    setTimeout(function () {
                        $('.enabledisablemsg').slideUp('slow');
                    }, 3000);
                } else {
                    jQuery('.enabledisablemsg').remove();
                    $(this).attr('data-toggle', 'modal');
                }
            });
            $("#editdnsmodal").on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var modal = $(this);
                modal.find(".cfa_error,.cfa_success").remove();
                modal.find(".modal-body").html("<img style='padding:40px 260px;' src='{/literal}{$moduleURL}{literal}images/load.gif' alt='Loading...'>");

                var dnsdetailsid = button.data('whatever');
                $.ajax({
                    url: '',
                    method: "POST",
                    data: "modop=custom&a=ManageCf&cf_action=manageWebsite&website={/literal}{$domain}{literal}&cfaction=dns&ajaxaction=editdnsrecord&salt={/literal}{$salt}{literal}&user_lang={/literal}{$user_lang}{literal}&dnsid=" + dnsdetailsid
                })
                        .done(function (resp) {
                            modal.find(".modal-body").html(resp);
                        });
            });
            /* Delete DNS Record */
            $("#deletednsmodal").on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                var dnsrecordid = button.data('whatever');
                var cfdnstype = button.data('accesskey');
                var zoneid = "{/literal}{$zoneid}{literal}";
                modal.find('input[name=dnsrecordid]').val(dnsrecordid);
                modal.find('input[name=cfdnstype]').val(cfdnstype);
                modal.find('input[name=zone_id]').val(zoneid);
            });
        });

        function enableDisableDns(obj, action) {
            var dnsdetailsid = jQuery(obj).attr('data-whatever');
            var status = jQuery(obj).attr('status');
            jQuery('.customloader').remove();
            jQuery('#showmsg').html("<img style='margin: auto;left: 0;right: 0;position: absolute;' class='dnsloader customloader' src='{/literal}{$moduleURL}{literal}images/load.gif' alt='Loading...'>");
            $.ajax({
                url: '',
                method: "POST",
                data: "modop=custom&a=ManageCf&cf_action=manageWebsite&website={/literal}{$domain}{literal}&cfaction=dns&ajaxaction=enabledisabledns&salt={/literal}{$salt}{literal}&user_lang={/literal}{$user_lang}{literal}&dnsid=" + dnsdetailsid + "&proxied=" + status,
                success: function (resp) {

                    jQuery('.customloader').remove();
                    res = resp.split("_");
                    if (res[0] == "E") {
                        var inserbeforehtml = '<div class="cfa_error enabledisablemsg" style="width: auto !important;"><span></span>' + res[1] + '</div>';
                        setTimeout(function () {
                            $('.enabledisablemsg').slideUp('slow');
                        }, 3000);
                    } else if (res[0] == "S") {
                        if (res[2] == "1") {
                            jQuery(obj).addClass('upcloudgrey');
                            jQuery(obj).removeClass('upcloudorange');
                            jQuery(obj).attr('status', '');
                            jQuery(obj).parent().next('td').find('.edit').removeAttr('data-enable_dns');
                        } else {
                            jQuery(obj).addClass('upcloudorange');
                            jQuery(obj).removeClass('upcloudgrey');
                            jQuery(obj).attr('status', '1');
                            jQuery(obj).parent().next('td').find('.edit').attr('data-enable_dns', '1');
                        }
                        inserbeforehtml = '<div class="cfa_success enabledisablemsg" style="width: auto !important;"><span></span>' + res[1] + '.</div>';
                        setTimeout(function () {
                            $('.enabledisablemsg').slideUp('slow');
                        }, 3000);
                    }
                    jQuery('.enabledisablemsg').remove();
                    jQuery('#showmsg').html(inserbeforehtml);
                }
            });
        }
        function submitDnssec(obj, enable_text, disable_text) {
            jQuery(obj).html('<i class="fa fa-spinner fa-spin" style="font-size:36px;"></i>');
            jQuery(obj).addClass('clicked');
            jQuery('#dnssec_record_msg').removeClass('dnssec_success dnssec_error').html('');
            jQuery('#dnssec_record_loader').hide();
            jQuery.ajax({
                url: '',
                method: "POST",
                data: 'dnsaction=dnssec&' + jQuery('#dnssecform').serialize(),
                success: function (resp) {
                    var response = jQuery.parseJSON(resp);
                    if (response.status == 'success') {
                        jQuery('#dnssec_record_msg').addClass('dnssec_success').html(response.msg);
                        if (jQuery('input[name="dnssec"]').val() == 'active') {
                            jQuery('.dnssec_record_body, #dnssec_record_loader, #dnspending, .dnssec_record_list_desc').show();
                            jQuery('input[name="dnssec"]').val('disabled');
                            jQuery(obj).html(disable_text);
                            getDnssecList();
                        } else if (jQuery('input[name="dnssec"]').val() == 'disabled') {
                            jQuery('#dnssec_record_cont, .dnssec_record_head').hide();
                            jQuery(obj).removeClass('clicked');
                            jQuery('input[name="dnssec"]').val('active');
                            jQuery('.dnssec_record_body, #dnspending').hide();
                            jQuery(obj).html(enable_text);
                        }
                    } else {
                        jQuery(obj).removeClass('clicked');
                        jQuery('#dnssec_record_msg').addClass('dnssec_error').html(response.msg);
                        if (jQuery('input[name="dnssec"]').val() == 'active') {
                            jQuery(obj).html(enable_text);
                        } else if (jQuery('input[name="dnssec"]').val() == 'disabled') {
                            jQuery('#dnssec_record_cont, .dnssec_record_head').show();
                            jQuery(obj).html(disable_text);
                        }
                    }
                }
            });
        }

        function getDnssecList() {
            jQuery('#dnssec_record_loader').show();
            jQuery('#dnssec_record_msg').removeClass('dnssec_success dnssec_error').html('');
            jQuery.ajax({
                url: '',
                method: "POST",
                data: 'dnsaction=dnsseclist&modop=custom&a=ManageCf&cf_action=manageWebsite&website={/literal}{$domain}{literal}&cfaction=dns',
                success: function (resp) {
                    jQuery('#dnssec_record_loader').hide();
                    var response = jQuery.parseJSON(resp);
                    if (response.status == 'success') {
                        if (response.result.ds != null) {
                            jQuery('#submit_dnssec').removeClass('clicked');
                            jQuery('.dnssec_record_body, .dnssec_record_head, .dnssec_record_list_desc').show();
                            jQuery('#ds_record').text(response.result.ds);
                            jQuery('#digest').text(response.result.digest);
                            jQuery('#digest_type').text(response.result.digest_algorithm);
                            jQuery('#algorithm').text(response.result.algorithm);
                            jQuery('#publick_key').text(response.result.public_key);
                            jQuery('#keytag').text(response.result.key_tag);
                            jQuery('#flag').text(response.result.flags);
                            jQuery('#hideshowdnsrecord').trigger('click');
                        } else {
                            jQuery('#submit_dnssec').removeClass('clicked');
                            getDnssecList();
                        }
                    } else {
                        jQuery('#dnssec_record_msg').addClass('dnssec_error').html(response.msg);
                        jQuery('.dnssec_record_body, .dnssec_record_head, #dnssec_record_cont').hide();
                    }
                }
            });
        }
        function hideshowdnsrecord(obj, showtext, hidetext) {
            jQuery('#dnssec_record_cont').toggle();
            var text = jQuery(obj).text() == showtext ? hidetext : showtext;
            jQuery(obj).text(text);
        }
        function cnameFlatten(obj) {
            jQuery(obj).css('pointer-events', 'none');
            jQuery('#cname_flatten_result').addClass('active_cname').html('<div class="cname_flattern_loader"><i class="fa fa-spinner fa-spin" style="font-size:36px;"></i></div>');
            jQuery.ajax({
                url: '',
                method: "POST",
                data: jQuery('#cname_flattener_from').serialize(),
                success: function (resp) {
                    var response = jQuery.parseJSON(resp);
                    jQuery(obj).css('pointer-events', 'auto');
                    jQuery('#cname_flatten_result').addClass('active_cname').html('');
                    if (response.status == 'success') {
                        jQuery('#cname_flatten_result').addClass('active_cname').html('<div class="cname_flattern_success">' + response.msg + '</div>');
                    } else {
                        jQuery('#cname_flatten_result').addClass('active_cname').html('<div class="cname_flattern_error">' + response.msg + '</div>');
                    }
                    setTimeout(function () {
                        jQuery('#cname_flatten_result').removeClass('active_cname').html('');
                    }, 3000);
                }
            });
        }
    </script>
{/literal}

<div class="cfcontent">
    <h3 class="cfcontentmargin">{$wgs_lang.cf_dns_settings}</h3>
</div>
<div class="cfinternal">
    {if $error}
        <div class="cfa_error">
            <span></span>
            {$error}
        </div>
    {/if}
    {if $actionsucess}
        <div class="cfa_success">
            <span></span>
            {$actionsucess}
        </div>
    {/if}

    <form id="deleteadddnsaction" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
        <input type="hidden" name="modop" value="custom">
        <input type="hidden" name="a" value="ManageCf">
        <input type="hidden" name="cf_action" value="manageWebsite">
        <input type="hidden" name="website" value="{$domain}">
        <input type="hidden" name="cfaction" value="dns">
        <input type="hidden" name="dnsaction" value="deleteadddnsrecords">
        <input type="hidden" name="messagetype" value="">
        <input type="hidden" name="message" value="">
    </form>

    <table class="cfcontenttabletype2">
        <tr>
            <td>
                {if $ipv6.editable}
                    <form name="dnsipv6form" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="dns">
                        <input type="hidden" name="dnsaction" value="ipv6">
                        <span>{$wgs_lang.cf_dns_ipv6_compatibility}</span>
                        <div>{$wgs_lang.cf_dns_ipv6_compatibility_desc}</div>
                        <div>
                            <select name="ipv6" class="input-sm">
                                <option value="on" {if $ipv6.value eq "on"} selected {/if}>{$wgs_lang.cf_dns_ipv6_on}</option>
                                <option value="off" {if $ipv6.value eq "off"} selected {/if}>{$wgs_lang.cf_dns_ipv6_off}</option>
                            </select>
                        </div>
                        <div class="btn btn-primary" onclick="dnsipv6form.submit();">{$wgs_lang.cf_dns_save_changes}</div>
                    </form>
                {/if}
            </td>
            <td>
                {if $pseudo_ipv4}
                    <form name="dnsipv4form" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="dns">
                        <input type="hidden" name="dnsaction" value="ipv4">
                        <span>{$wgs_lang.cf_dns_pseudo_ipv4}</span>
                        <div>{$wgs_lang.cf_dns_pseudo_ipv4_desc}</div>
                        <div>
                            <select name="ipv4" class="input-sm">
                                {foreach from=$ipv4settingvalues key=v item=settingvalue}
                                    <option value="{$v}" {if $pseudo_ipv4.value eq $v} selected {/if}>{$settingvalue}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="btn btn-primary" onclick="dnsipv4form.submit();">{$wgs_lang.cf_dns_save_changes}</div>
                    </form>
                {/if}
            </td>
        </tr>
    </table>
    {if $zonetype eq 'full'}
        <div class="dnssec_sec">
            <div class="dnssec-body">
                <h3>{$wgs_lang.cf_dns_dnssec}</h3>
                <p>{$wgs_lang.cf_dns_dnssec_desc}</p>
                <p id="dnspending" style="display: {if $dnssec.status eq 'pending'}block{else}none{/if};">{$wgs_lang.cf_dns_dnssec_pending_text}</p>
                <form name="dnssecform" id="dnssecform">
                    <input type="hidden" name="modop" value="custom">
                    <input type="hidden" name="a" value="ManageCf">
                    <input type="hidden" name="cf_action" value="manageWebsite">
                    <input type="hidden" name="website" value="{$domain}">
                    <input type="hidden" name="cfaction" value="dns">
                    <input type="hidden" name="dnssec" value="{if $dnssec.status neq 'disabled'}disabled{else}active{/if}">
                    <div class="btn btn-primary" id="submit_dnssec" onclick="submitDnssec(this, '{$wgs_lang.cf_dns_enablednssec}', '{$wgs_lang.cf_dns_disableldnssec}');">{if $dnssec.status neq 'disabled'}{$wgs_lang.cf_dns_disableldnssec}{else}{$wgs_lang.cf_dns_enablednssec}{/if}</div>
                </form>
                <div class="dnssec_record">
                    <div id="dnssec_record_msg"></div>

                    <div class="dnssec_record_body" style="display: {if $dnssec.status neq 'disabled'}block{else}none{/if};">

                        <div class="dnssec_record_head" style="display: {if $dnssec.status neq 'disabled'}block{else}none{/if};">
                            <a href="javascript:void(0);" id="hideshowdnsrecord" onclick="hideshowdnsrecord(this, '{$wgs_lang.cf_dns_dnssec_showdsrecord}', '{$wgs_lang.cf_dns_dnssec_hidedsrecord}');">{$wgs_lang.cf_dns_dnssec_showdsrecord}</a>
                        </div>
                        <div class="dnssec_record_loader" id="dnssec_record_loader" style="display: none;">
                            <div class="dnssec_loader">
                                <i class="fa fa-spinner fa-spin" style="font-size:36px;"></i>
                                <p>{$wgs_lang.cf_dns_dnssec_loader_text}</p>
                            </div>
                        </div>
                        <div  id="dnssec_record_cont" style="display:none;">

                            <div class="dnssec_record_list_desc" style="display: {if $dnssec.status eq 'pending'}block{else}none{/if};"><h2>{$wgs_lang.cf_dns_dnssec_howtoenable}</h2>{$wgs_lang.cf_dns_dnssec_instruction_text} <a target="_blank" href="https://support.cloudflare.com/hc/en-us/articles/209114378">{$wgs_lang.cf_dns_dnssec_here}</a></a></div>

                            <h3>{$wgs_lang.cf_dns_dnssec_dsrecordfor} {$domain}</h3>
                            <p>{$wgs_lang.cf_dns_dsrecord_desc}</p>
                            <div class="dnssec_record_list">
                                <h3>{$wgs_lang.cf_dns_dsrecord}</h3>
                                <textarea readonly="readonly" rows="2" id="ds_record">{$dnssec.ds}</textarea>
                            </div>
                            <div class="dnssec_record_list">
                                <h3>{$wgs_lang.cf_dns_dsrecord_digest}</h3>
                                <textarea readonly="readonly" rows="1" id="digest">{$dnssec.digest}</textarea>
                            </div>
                            <div class="dnssec_record_list">
                                <h3>{$wgs_lang.cf_dns_dsrecord_digest_type}</h3>
                                <textarea readonly="readonly" rows="1" id="digest_type">{$dnssec.digest_algorithm}</textarea>
                            </div>
                            <div class="dnssec_record_list">
                                <h3>{$wgs_lang.cf_dns_dsrecord_algorithm}</h3>
                                <textarea readonly="readonly" rows="1" id="algorithm">{$dnssec.algorithm}</textarea>
                            </div>
                            <div class="dnssec_record_list">
                                <h3>{$wgs_lang.cf_dns_dsrecord_pub_key}</h3>
                                <textarea readonly="readonly" rows="2" id="publick_key">{$dnssec.public_key}</textarea>
                            </div>
                            <div class="dnssec_record_list">
                                <h3>{$wgs_lang.cf_dns_dsrecord_key_tag}</h3>
                                <textarea readonly="readonly" rows="1" id="keytag">{$dnssec.key_tag}</textarea>
                            </div>
                            <div class="dnssec_record_list">
                                <h3>{$wgs_lang.cf_dns_dsrecord_flag}</h3>
                                <textarea readonly="readonly" rows="1" id="flag">{$dnssec.flags}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {/if}
    {if $plan neq 'free'}
        <div class="cname_flattener_sec">
            <div class="cname_flattener_body">
                <h3>{$wgs_lang.cf_dns_cname_flattener}</h3>
                <p>{$wgs_lang.cf_dns_cname_flattener_desc}</p>
                <p>{$wgs_lang.cf_dns_cname_flattener_desc1} {$domain}</p>
                <form name="cname_flattener_from" id="cname_flattener_from">
                    <input type="hidden" name="modop" value="custom">
                    <input type="hidden" name="a" value="ManageCf">
                    <input type="hidden" name="cf_action" value="manageWebsite">
                    <input type="hidden" name="website" value="{$domain}">
                    <input type="hidden" name="cfaction" value="dns">
                    <input type="hidden" name="dnsaction" value="cnameflattern">
                    <select name="cname_flattener" class="input-sm" onchange="cnameFlatten(this);">
                        <option value="flatten_at_root" {if $cname_flattern eq 'flatten_at_root'}selected="selected"{/if}>{$wgs_lang.cf_dns_cname_flattener_at_root}</option>
                        <option value="flatten_all" {if $cname_flattern eq 'flatten_all'}selected="selected"{/if}>{$wgs_lang.cf_dns_cname_flattener_all_cnames}</option>
                        <option value="flatten_none" {if $cname_flattern eq 'flatten_none'}selected="selected"{/if}>{$wgs_lang.cf_dns_cname_flattener_none}</option>
                    </select>
                    <div id="cname_flatten_result"></div>
                </form>
            </div>
        </div>
    {/if}
    <div class="cfcontent">
        <h2 class="cfcontentmargin">{$wgs_lang.cf_dns_rescordes}</h2>
    </div>
    <div id="showmsg"></div>  
    <div class="cfshowdnsrecord">
        <select name="addnewdnsrecord" class="input-sm" id="addnewdnsrecord" style="margin: 0px;">
            {foreach from=$dnsrecordtypes key=dnsrecordvalue item=dnsrecordtype}
                <option value="{$dnsrecordvalue}">{$dnsrecordtype}</option>
            {/foreach}
        </select>
        <input type="button" class="btn btn-primary" data-toggle="modal" data-target="#addnewdnsmodal" value="{$wgs_lang.cf_dns_add_new_dns_record}">
    </div>

    <table class="cfcontenttable">
        <tr>
            <th>{$wgs_lang.cf_dns_type}</th>
            <th>{$wgs_lang.cf_dns_name}</th>
            <th>{$wgs_lang.cf_dns_value}</th>
            <th>{$wgs_lang.cf_dns_ttl}</th>
            <th>{$wgs_lang.cf_dns_status}</th>
            <th></th>
        </tr>
        {foreach from=$dnsrecords item=dnsrecord}
            <tr>
                <td>{$dnsrecord.type}</td>
                <td>{$dnsrecord.name}</td>
                <td>{$dnsrecord.content|truncate:60}</td>
                <td>{if $dnsrecord.ttl eq "1"}Automatic{else}{$dnsrecord.ttl}{/if}</td>
                <td>{if $dnsrecord.proxiable}{if $dnsrecord.proxied}<span style="cursor:pointer!important;" class="upcloudorange" onclick="enableDisableDns(this);" status="{$dnsrecord.proxied}" data-whatever="{$dnsrecord.id}"></span>{else}<span  style="cursor:pointer!important;" class="upcloudgrey" onclick="enableDisableDns(this, '{$dnsrecord.proxied}');" data-whatever="{$dnsrecord.id}"></span>{/if}{/if}</td>
                        {*                <td>{if $dnsrecord.proxiable}{if $dnsrecord.proxied}<span class="upcloudorange"></span>{else}<span class="upcloudgrey"></span>{/if}{/if}</td>*}
                <td>
                    <span data-toggle="modal" data-target="#editdnsmodal" class="edit" data-whatever="{$dnsrecord.id}" {if $dnsrecord.proxiable}{if $dnsrecord.proxied} data-enable_dns="{$dnsrecord.proxied}" {/if}{/if}></span>
                    <span data-toggle="modal" data-target="#deletednsmodal" class="delete" data-accesskey="{$dnsrecord.type}" data-whatever="{$dnsrecord.id}"></span></td>
            </tr>
        {/foreach}
    </table>
</div>

<div class="modal fade" id="addnewdnsmodal" tabindex="-1" role="dialog" aria-labelledby="addnewDNSModalLabel">
    <div class="modal-dialog" role="document">
        <form name="form-adddnsrecord" id="form-adddnsrecord" method="post" action="">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addnewDNSModalLabel">{$wgs_lang.cf_dns_add_dns_record}</h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{$wgs_lang.cf_dns_cancel}</button>
                    <button type="button" class="btn btn-primary" onclick="manageDNS('add')">{$wgs_lang.cf_dns_continue}</button>
                </div>
            </div>
        </form>
    </div>
</div>        

<div class="modal fade" id="editdnsmodal" tabindex="-1" role="dialog" aria-labelledby="editDNSModalLabel">
    <div class="modal-dialog" role="document">
        <form name="form-editdnsrecord" id="form-editdnsrecord" method="post" action="">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editDNSModalLabel">{$wgs_lang.cf_dns_edit_dns_records}</h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{$wgs_lang.cf_dns_cancel}</button>
                    <button type="button" class="btn btn-primary" onclick="manageDNS('edit')">{$wgs_lang.cf_dns_continue}</button>
                </div>
            </div>
        </form>
    </div>
</div> 

<div class="modal fade" id="deletednsmodal" tabindex="-1" role="dialog" aria-labelledby="deleteDNSModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <form name="form-deletedns" id="form-deletedns" method="post" action="">
            <input type="hidden" name="dnsaction" value="deletedns">
            <input type="hidden" name="dnsrecordid" value="">
            <input type="hidden" name="cfdnstype" value="">
            <input type="hidden" name="zone_id" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="deleteDNSModalLabel">{$wgs_lang.cf_dns_delete_dns_records}</h4>
                </div>
                <div class="modal-body">
                    {$wgs_lang.cf_dns_delete_dns_records_desc}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{$wgs_lang.cf_dns_cancel}</button>
                    <button type="button" class="btn btn-primary" onclick="deleteDNS()">{$wgs_lang.cf_dns_continue}</button>
                </div>
            </div>
        </form>
    </div>
</div> 
            {$cffooter}