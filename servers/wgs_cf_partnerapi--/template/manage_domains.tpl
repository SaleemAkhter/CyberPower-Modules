
<link type="text/css" rel="stylesheet" href="/modules/servers/wgs_cf_partnerapi/css/cloudflare.css">
<script rel="text/javascript" src="/modules/servers/wgs_cf_partnerapi/js/cloudflare.js"></script>
{if $error}

    <div class="alert alert-danger">

        {$error}

    </div>

{/if}

{if $successmessage}

    <div class="alert alert-success">

        {$successmessage}

    </div>

{/if}


<div class="cf_d_mangr">
    {if !$nodomain}

        <!-- <div style="width: 100%;text-align: right;">

            <button onclick="jQuery('#addDomainFrm').toggle();" class="btn btn-default">{$wgs_lang.cf_domains_addmore}</button>

        </div> -->

        <div class="panel panel-default text-left" id="addDomainFrm">
            <div class="panel-heading">{$wgs_lang.cf_domains_manage_domain} <span class="text-right" style="
                                                                                  float: right;
                                                                                  ">Domains {$totalUsedDomain} of {$totalAssignDomains}</span></div>

            <div class="panel-body cf-frm">

                <form action="clientarea.php?action=productdetails&id={$serviceid}" method="post">



                    <input type="hidden" value="true" name="adddomain">

                    <div class="form-group col-md-6">

                        <label for="Domain Name">{$wgs_lang.cf_domains_name}:</label>

                        <input type="text" name="domainname" class="form-control" id="domainname" required="required" value="" placeholder="domainname.com">

                    </div>

                    <button type="submit" class="btn btn-default ad-btn">{$wgs_lang.cf_domains_add}</button>

                </form>

            </div>

        </div>  

    {/if}

    <div class="panel panel-default text-left">

        <div class="panel-heading">{$wgs_lang.cf_domains_heading}</div>

        <div class="panel-body">

            <table class="table">

                <thead>

                    <tr>

                        <th scope="col">{$wgs_lang.cf_domains_name}</th>

                        <th scope="col">{$wgs_lang.cf_domains_status}</th>
                        <th scope="col">{$wgs_lang.cf_plan}</th>

                        <th scope="col">{$wgs_lang.cf_domains_action}</th>

                    </tr>

                </thead>

                <tbody>

                    {foreach from=$zones item=zone key=num}

                        <tr>

                            <td scope="row">{$zone.zonename|upper}</td>

                            <td>{$zone.status|upper}</td>
                            <td>{$zone.plan}</td>

                            <td>
                                <button class='btn manage-btn' onclick="manageDomain(this, '{$zone.zonename}');" title="{$wgs_lang.cf_domains_manage}"></button>
                                {if !$zone.default} &nbsp; <button class='btn manage-btn delete' onclick="deleteDomain(this, '{$zone.zonename}');" title="{$wgs_lang.cf_domains_delete}"></button>{/if}
                                &nbsp; <button class='btn manage-btn upgrade' onclick="upgradeDomain(this, '{$zone.zonename}', '{$zone.plan}', '{$zone.zone_id}');" title="{$wgs_lang.cf_domains_upgrade}"><i class="fas fa-level-up-alt"></i></button>
                            </td>

                        </tr>

                    {foreachelse}

                        <tr>

                            <td colspan="100%">{$wgs_lang.cf_domains_notfound}</td>    

                        </tr>

                    {/foreach}

                </tbody>

            </table>

        </div>

    </div>
</div>


<form method="post" id="cloudflareform" action="clientarea.php?action=productdetails&id={$serviceid}" style="display:none">

    <input type="hidden" name='modop' value='custom'>

    <input type="hidden" name="a" value="ManageCf">

    <input type="hidden" name="cf_action" id="cf_action" value="manageWebsite">

    <input type="hidden" name="website" id="website" value=''>

    <input type="submit" id="manage_cf" value="{$wgs_lang.cf_manage_cf}">

</form>

<form method="post" id="deletedomainform" action="clientarea.php?action=productdetails&id={$serviceid}" style="display:none">

    <input type="hidden" value="true" name="deletedomain">
    <input type="hidden" name="deletewebsite" id="deletewebsite" value=''>
    <input type="submit" id="manage_cf" value="{$wgs_lang.cf_manage_cf}">

</form>

<div id="upgradeModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{$wgs_lang.cf_domains_upgrade}</h4>
            </div>
            <div class="modal-body">
                <div id="upgarderes"></div>
                <form id="upgradeForm" method="post" action="">
                    <div class="form-group text-left">
                        <label for="current_plan">{$wgs_lang.cf_current_plan}:</label>
                        <input type="text" class="form-control" id="current_plan" readonly="readonly">
                    </div>
                    <div class="form-group text-left">
                        <label for="choose_plan">{$wgs_lang.cf_domains_choose_plan}:</label>
                        <select id="choose_plan" class="form-control" name="plan_name">
                            <option value="">{$wgs_lang.cf_domains_choose_plan}</option>
                            <option value="pro" price="${$pro_plan_price}">{$wgs_lang.cf_domains_pro_plan}</option>
                            <option value="biz" price="${$biz_plan_price}">{$wgs_lang.cf_domains_biz_plan}</option>
                        </select>
                    </div>
                    <div class="form-group text-left">
                        <div class="plan_detail">
                            <label>{$wgs_lang.cf_domains_plan}:</label> <span id="planText"></span>
                            <br/>
                            <label>{$wgs_lang.cf_domains_price}:</label> <span id="planPrice">$0.00</span>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button onclick="upgradeSubScription(this)" type="button" class="btn btn-success">{$wgs_lang.cf_domains_upgrade_btn}</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{$wgs_lang.cf_domains_close}</button>
            </div>
        </div>

    </div>
</div>
