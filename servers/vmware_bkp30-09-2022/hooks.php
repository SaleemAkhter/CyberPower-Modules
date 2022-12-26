<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

function hook_vmware_includes_files()
{

    require_once __DIR__ . '/class/class.php';
}
add_hook('ProductEdit', 1, function($vars) {

    if($vars['servertype']=='vmware' && isset($_POST['packageconfigoption']['25'])){
        // debug($vars);die();

        $disablenvme=true;
        if(isset($_POST['packageconfigoption']['25'])){
            if(strpos($_POST['packageconfigoption']['25'], 'nvme')!==false){
                $disablenvme=false;
            }
        }
        $pid=$_POST['id'];
        $configoptid=Capsule::table('tblproductconfigoptions')->leftJoin("tblproductconfiglinks","tblproductconfigoptions.gid","=","tblproductconfiglinks.gid")->where(['tblproductconfigoptions.optionname'=>'storagetype','tblproductconfiglinks.pid'=>$pid])->value("tblproductconfigoptions.id");
        if($configoptid){
           $addssd=$addnvme=true;
           $opts= Capsule::table('tblproductconfigoptionssub')->where(['configid'=>$configoptid])->get();
           if(count($opts)){
                foreach ($opts as $key => $opt) {
                    if(stripos($opt->optionname, "NVMe")!==false){
                        if($disablenvme){
                            Capsule::table('tblproductconfigoptionssub')->where(['id'=>$opt->id])->delete();
                        }
                        $addnvme=false;
                    }
                    if(stripos($opt->optionname, "SSD")!==false){
                         $addssd=false;
                    }
                }
            }
            if($addssd){
                Capsule::table('tblproductconfigoptionssub')->insert([
                    'configid'=>$configoptid,
                    'optionname'=>'Standard SSD',
                    'sortorder'=>0,
                    'hidden'=>0
                ]);
            }
            if($addnvme){
                Capsule::table('tblproductconfigoptionssub')->insert([
                    'configid'=>$configoptid,
                    'optionname'=>'Premium NVMe',
                    'sortorder'=>0,
                    'hidden'=>0
                ]);
            }
        }
/**/



    require_once dirname(__DIR__) . '/vmware/class/class.php';
    global $whmcs;
    try {

        $WgsVmwareObj = new WgsVmware();

        $WgsVmwareObj->vmware_includes_files();

        $server_name = $vars['configoption3'];
        $defaultHost=$vars['configoption15'];

        $serverData = Capsule::table('mod_vmware_server')->where('server_name', $server_name)->get();
        if (count($serverData) == 0)
            $serverData = Capsule::table('mod_vmware_server')->where('id', $server_name)->get();


        if (count($serverData) > 0 && !empty($defaultHost)) {

            $productid =$pid;

            $getProductQuery = Capsule::table('tblproducts')->where('id', $productid)->get();

            $getProductDetail = (array) $getProductQuery[0];

            $ExistHostsName = $getProductDetail['configoption15'];

            $getip = explode('://', $serverData[0]->vsphereip);

            $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

            $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

            $hasssd=$hasnvme=false;
            $ssdpriority=0;
            $nvmepriority=1;
            $configoptid=Capsule::table('tblproductconfigoptions')->leftJoin("tblproductconfiglinks","tblproductconfigoptions.gid","=","tblproductconfiglinks.gid")->where(['tblproductconfigoptions.optionname'=>'storagetype','tblproductconfiglinks.pid'=>$productid])->value("tblproductconfigoptions.id");
                if($configoptid){
                   $hasssd=$hasnvme=false;
                   $opts= Capsule::table('tblproductconfigoptionssub')->where(['configid'=>$configoptid])->get();
                   if(count($opts)){
                        foreach ($opts as $key => $opt) {
                            if(stripos($opt->optionname, "NVMe")!==false){
                                $hasnvme=true;
                                $nvmepriority=0;
                                $ssdpriority=1;
                            }
                            if(stripos($opt->optionname, "SSD")!==false){
                                 $hasssd=true;
                            }
                        }
                    }

                }

            $hostOption = '';


            if ($defaultHost != 'none') {
                if ($vms->get_host_network($defaultHost)) {

                    $GetDsSetting = Capsule::table("mod_vmware_ds_setting")->where('host_id', $defaultHost)->where('disable', '0')->orderBy('priority', 'ASC')->get();
                    foreach ($GetDsSetting as $k=>$DS) {
                        $DsInfo = $vms->datastoreDetail($DS->ds_id);
                        $dsFreeDisk = round($DsInfo->getSummary()->freeSpace / 1073741824, 2);
                        if ((float) $hardDisk < (float) $dsFreeDisk) {
                            $datastore_id = $DS->ds_id;

                            if($hasnvme && stripos($DsInfo->getSummary()->name, "NVMe")!==false){
                                 Capsule::table("mod_vmware_ds_setting")->where('id', $DS->id)->update([
                                    'priority'=>$nvmepriority
                                 ]);
                            }else{
                                Capsule::table("mod_vmware_ds_setting")->where('id', $DS->id)->update([
                                    'priority'=>$ssdpriority
                                 ]);
                            }

                        }
                    }
                }
            }


        } else {


        }
    } catch (Exception $ex) {

    }
    }
});
add_hook('PreCalculateCartTotals', 1, function ($vars) {

    global $smarty;

    $pid = $vars['cartsummarypid'];

    $productDetail = Capsule::table('tblproducts')->where('id', $pid)->first();

    $includedItems = [];

    if ($productDetail->servertype == 'vmware') {

        if (!empty($productDetail->configoption1) && !empty($productDetail->configoption20)) {

            $ram = $productDetail->configoption4 / 1024 . ' GB';

            if ($productDetail->configoption4 < 1024)
                $ram = $productDetail->configoption4 . ' MB';

            $cpu = $productDetail->configoption5 . ' Core';

            $additionalIps = $productDetail->configoption6 > 1 ? $productDetail->configoption6 . ' IP\'s' : $productDetail->configoption6 . ' IP';

            $hdd = $productDetail->configoption7 + $productDetail->configoption8 + $productDetail->configoption9 + $productDetail->configoption10;

            $bwLimit = $productDetail->configoption11;

            $noSps = $productDetail->configoption19;

            $includedItems[] = ['itemname' => "RAM: $ram", 'itemtext' => 'Included'];

            $includedItems[] = ['itemname' => "CPU: $cpu", 'itemtext' => 'Included'];

            if (!empty($productDetail->configoption6))
                $includedItems[] = ['itemname' => "IP's: $additionalIps", 'itemtext' => 'Included'];

            $includedItems[] = ['itemname' => "HDD: $hdd GB", 'itemtext' => 'Included'];

            if (!empty($bwLimit))
                $includedItems[] = ['itemname' => "Bandwidth: $bwLimit GB", 'itemtext' => 'Included'];

            if (!empty($noSps))
                $includedItems[] = ['itemname' => "Snapshots: $noSps", 'itemtext' => 'Included'];

            if ($includedItems)
                $smarty->assign('vmware_included_items', $includedItems);
        }
    }
});

function vmware_client_area_head_output($vars)
{

    global $whmcs;

    $a = $whmcs->get_req_var("a");

    $i = $whmcs->get_req_var("i");

    if ($a == 'confproduct') {



        $head_return = '';

        if (isset($_SESSION['cart']['products'][$i]['pid'])) {

            $pid = $_SESSION['cart']['products'][$i]['pid'];

            $productDetail = Capsule::table('tblproducts')->where('id', $pid)->get();

            if ($productDetail[0]->servertype == 'vmware') {

                hook_vmware_includes_files();
                $WgsVmwareObj = new WgsVmware();
                $WgsVmwareObj->vmware_includes_files();
                $params = (array) $productDetail[0];
                $customFieldVal = vmwareGetCustomFiledVal($params, 'hook');
                $customosfamilyid = getWhmcsProductCustomFieldsDetail($pid, $customFieldVal['os_type_field']);
                $customosversionid = getWhmcsProductCustomFieldsDetail($pid, $customFieldVal['os_version_field']);
                $datacenterCfId = getWhmcsProductCustomFieldsDetail($pid, $customFieldVal['datacenter_field']);
                $datacenterVal = getWhmcsProductCustomFieldsDetail($pid, $customFieldVal['datacenter_field'], TRUE);
                $dcOptionsArr = explode(',', $datacenterVal->fieldoptions);
                $countDc = count($dcOptionsArr);
                $productDetail[0]->configoption1;
                $dcRequired = false;
                if ($params['configoption15'] != "none") {
                    $dcRequired = true;
                }
                if (empty($productDetail[0]->configoption1) && "" == $productDetail[0]->configoption20) {

                    $head_return = '<style>.dcreq{color:#ff0000;}</style><script type="text/javascript">

                    jQuery(document).ready(function(){

                        var countdc = "' . $countDc . '";
                        var dcRequired = "' . $dcRequired . '";

                            if(countdc == "1" && jQuery("#customfield' . $datacenterCfId . '").html()){

                                jQuery("#customfield' . $datacenterCfId . '").val("' . $dcOptionsArr[0] . '");

                                jQuery("#customfield' . $datacenterCfId . '").css("background", "#ccc");

                                jQuery("#customfield' . $datacenterCfId . '").css("pointer-events","none");

                            }
                        getOsVersion(dcRequired);
                        jQuery("#customfield' . $customosfamilyid . '").change(function(){
                            getOsVersion(dcRequired);

                        });

                        jQuery("#customfield' . $datacenterCfId . '").change(function(){
                          // if(dcRequired)
                            getOsVersion(dcRequired);

                        });


                        jQuery("select[name=\"billingcycle\"]").change(function(){
                            setTimeout(function(){
                                //if(dcRequired)
                                    getOsVersion(dcRequired);
                            }, 2000);

                         });
                        jQuery("#customfield' . $customosversionid . '").find("option").remove();';

                    foreach (Capsule::table('mod_vmware_os_list')->select('os_version')->where('os_family', 'Windows')->get() as $os_list) {

                        $head_return .= 'jQuery("#customfield' . $customosversionid . '").append("<option>' . $os_list->os_version . '</option>");';
                    }

                    $head_return .= '});

                        function getOsVersion(dcRequired){

                            jQuery(".dcreq").remove();

                            var osValue = jQuery("#customfield' . $customosfamilyid . '").val();

                            var datacenter = jQuery("#customfield' . $datacenterCfId . '").val();

                            if(datacenter == ""){
                                jQuery("#customfield' . $datacenterCfId . '").focus();
                                jQuery("#customfield' . $datacenterCfId . '").after("<span class=\"dcreq\">Datacenter is required.</span>");
                                return false;
                            }

                            jQuery(".dcreq").remove();
                            jQuery("#customfield' . $customosversionid . '").find("option").remove();
                            jQuery("#customfield' . $customosversionid . '").append("<option>Loading...</option>");
                            jQuery.ajax({

                                type: "post",

                                url: "modules/servers/vmware/ajax/ajaxpost.php",

                                data: "custom=ajax&os_list="+jQuery.trim(osValue)+"&dc="+datacenter+"&pid=' . $pid . '",

                                success: function (response){

                                    if(response != ""){

                                        jQuery("#customfield' . $customosversionid . '").find("option").remove();

                                        jQuery("#customfield' . $customosversionid . '").append("<option value>Select</option>"+response);

                                    }else{

                                        jQuery("#customfield' . $customosversionid . '").find("option").remove();

                                        jQuery("#customfield' . $customosversionid . '").append("<option value>Not Found</option>");

                                    }

                                }

                            });

                        }

                    </script>';
                } else {

                    $getConfigurableGroupId = Capsule::table('tblproductconfiglinks')->where('pid', $pid)->first();

                    $getConfigurableGroupId = $getConfigurableGroupId->gid;



                    $configurableOsFamilyId = vmwareGetConfigurableOptionId($getConfigurableGroupId, 'guest_os_family');

                    $configurableOsVesrionId = vmwareGetConfigurableOptionId($getConfigurableGroupId, 'guest_os_version');

                    $getOptionId = Capsule::table('tblproductconfigoptions')->where('gid', $getConfigurableGroupId)->where('optionname', 'like', '%datacenter%')->first();

                    $configurableDcId = $getOptionId->id;
                    $configurableDcHidden = $getOptionId->hidden;

                    $head_return = '<style>.cf_error{color:#ff0000 !important;}</style><script type="text/javascript">

                    jQuery(document).ready(function(){

                        var countdc = "' . $countDc . '";
                        var dcRequired = "' . $dcRequired . '";
                        var DcConfigOpt = "'.$configurableDcHidden.'";

                        /*if(DcConfigOpt == 0 && jQuery("#inputConfigOption' . $configurableDcId . '").html()){
                            jQuery("#inputConfigOption' . $configurableDcId . '").css("background", "#ccc");

                            jQuery("#inputConfigOption' . $configurableDcId . '").css("pointer-events","none");
                        }else */
                        if(countdc == "1" && jQuery("#customfield' . $datacenterCfId . '").html()){

                            jQuery("#customfield' . $datacenterCfId . '").val("' . $dcOptionsArr[0] . '");

                            jQuery("#customfield' . $datacenterCfId . '").css("background", "#ccc");

                            jQuery("#customfield' . $datacenterCfId . '").css("pointer-events","none");

                        }
                        getOsVersion(dcRequired);
                        jQuery("body").on("change", "select[name=\'configoption[' . $configurableOsFamilyId . ']\']", function(){
                            getOsVersion(dcRequired);
                        });
                        //jQuery("select[name=\'configoption[' . $configurableOsFamilyId . ']\']").change(function(){
                        //    getOsVersion(dcRequired);
                       // });
                        if(DcConfigOpt == 0){
                            jQuery("#inputConfigOption' . $configurableDcId . '").change(function(){
                                //if(dcRequired)
                                    getOsVersion(dcRequired);

                            });
                        }else{
                            jQuery("#customfield' . $datacenterCfId . '").change(function(){
                                //if(dcRequired)
                                    getOsVersion(dcRequired);

                            });
                        }


                        jQuery("select[name=\"billingcycle\"]").change(function(){
                            setTimeout(function(){
                                //if(dcRequired)
                                    getOsVersion(dcRequired);
                            }, 2000);

                         });
                        jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").find("option").remove();';

                    $head_return .= '});

                        function getOsVersion(dcRequired){

                            var osValue = jQuery("select[name=\'configoption[' . $configurableOsFamilyId . ']\'] :selected").val();

                            if(jQuery("#inputConfigOption' . $configurableDcId . '").html())
                                var datacenter = jQuery.trim(jQuery("#inputConfigOption' . $configurableDcId . ' option:selected").text());
                            else
                                var datacenter = jQuery("#customfield' . $datacenterCfId . '").val();
                            if(datacenter == ""){

                                jQuery(".cf_error").remove();
                                jQuery("#customfield' . $datacenterCfId . '").focus();
                                jQuery("#customfield' . $datacenterCfId . '").addClass("cf_req");
                                jQuery("#customfield' . $datacenterCfId . '").after("<span class=\"cf_error\">Datacenter is required.</span>");
                                return false;

                            }

                            jQuery(".cf_error").remove();
                            //if(dcRequired)
                                jQuery("#customfield' . $datacenterCfId . '").removeClass("cf_req");



                            jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").find("option").remove();

                            jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").append("<option>Loading...</option>");

                            jQuery.ajax({

                                type: "post",

                                url: "modules/servers/vmware/ajax/ajaxpost.php",

                                data: "custom=ajax&configurableoption=true&os_list="+jQuery.trim(osValue)+"&dc="+datacenter+"&pid=' . $pid . '&configoptionid=' . $configurableOsVesrionId . '&i=' . $i . '",

                                success: function (response){

                                    if(response != ""){

                                        jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").find("option").remove();

                                        jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").append(response);

                                        jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").trigger("change");

                                    }else{

                                        jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").find("option").remove();

                                        jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").append("<option value>Not Found</option>");

                                    }

                                }

                            });

                        }

                    </script>';
                }
            }

            return $head_return;
        }
    }
}

function vmwareAdminAreaHeadOutput($vars)
{

    global $whmcs;
    $reqPage = $_SERVER['SCRIPT_NAME'];
    $arr = explode('/', $reqPage);
    $count = count($arr) - 1;
    $page = $arr[$count];
    $action = $whmcs->get_req_var("action");
    if ($page == 'configproducts.php' && $action == 'edit') {
        $pid = $whmcs->get_req_var("id");
    } elseif ($page == 'clientsservices.php') {
        $id = $whmcs->get_req_var("id");
        $productQuery = Capsule::table('tblhosting')->select('packageid')->where('id', $id)->get();
        $pid = $productQuery[0]->packageid;
        $sid = $productQuery[0]->id;
    }

    if ($pid) {
        $query = Capsule::table('tblproducts')->where('id', $pid)->get();

        if ($page == 'clientsservices.php' && trim($query[0]->servertype) == 'vmware') {

            hook_vmware_includes_files();

            $WgsVmwareObj = new WgsVmware();
            $WgsVmwareObj->vmware_includes_files();
            $params = (array) $query[0];
            $customFieldVal = vmwareGetCustomFiledVal($params, 'hook');
            $customosfamilyid = getWhmcsProductCustomFieldsDetail($pid, $customFieldVal['os_type_field']);
            $customosversionid = getWhmcsProductCustomFieldsDetail($pid, $customFieldVal['os_version_field']);

            # datacenter custom field id

            $datacenterCfId = getWhmcsProductCustomFieldsDetail($pid, $customFieldVal['datacenter_field']);
            $sid = $whmcs->get_req_var("id");
            $i = $whmcs->get_req_var("i");
            $dcRequired = false;
            if ($params['configoption15'] != "none") {
                $dcRequired = true;
            }
            if (empty($query[0]->configoption1) && "" == $query[0]->configoption20) {

                $head_return = '<script type="text/javascript">

                            jQuery(document).ready(function(){
                                var dcRequired = "' . $dcRequired . '";

                            getOsVersion();
                            jQuery("#customfield' . $customosfamilyid . '").change(function(){
                                getOsVersion(dcRequired);
                            });

                            jQuery("#customfield' . $datacenterCfId . '").change(function(){
                                //if(dcRequired)
                                    getOsVersion(dcRequired);

                            });

                            jQuery("#customfield' . $customosversionid . '").find("option").remove();';

                foreach (Capsule::table('mod_vmware_os_list')->select('os_version')->where('os_family', 'Windows')->get() as $os_list) {

                    $head_return .= 'jQuery("#customfield' . $customosversionid . '").append("<option>' . $os_list->os_version . '</option>");';
                }

                $head_return .= '});

                    function getOsVersion(dcRequired){

                        var osValue = jQuery("#customfield' . $customosfamilyid . '").val();

                        var datacenter = jQuery("#customfield' . $datacenterCfId . '").val();

                        jQuery("#customfield' . $customosversionid . '").find("option").remove();
                        jQuery("#customfield' . $customosversionid . '").append("<option>Loading...</option>");
                        jQuery.ajax({
                            type: "post",
                            url: "../modules/servers/vmware/ajax/ajaxpost.php",
                            data: "custom=ajax&os_list="+jQuery.trim(osValue)+"&dc="+datacenter+"&pid=' . $pid . '&serviceid=' . $sid . '",

                            success: function (response){

                                if(response != ""){

                                    jQuery("#customfield' . $customosversionid . '").find("option").remove();

                                    jQuery("#customfield' . $customosversionid . '").append(response);

                                }else{

                                    jQuery("#customfield' . $customosversionid . '").find("option").remove();

                                    jQuery("#customfield' . $customosversionid . '").append("<option value>Not Found</option>");

                                }

                            }

                        });

                    }

                    </script>';
            } else {

                $getConfigurableGroupId = Capsule::table('tblproductconfiglinks')->where('pid', $pid)->first();

                $getConfigurableGroupId = $getConfigurableGroupId->gid;



                $configurableOsFamilyId = vmwareGetConfigurableOptionId($getConfigurableGroupId, 'guest_os_family');

                $configurableOsVesrionId = vmwareGetConfigurableOptionId($getConfigurableGroupId, 'guest_os_version');
                $getOptionId = Capsule::table('tblproductconfigoptions')->where('gid', $getConfigurableGroupId)->where('optionname', 'like', '%datacenter%')->first();

                $configurableDcId = $getOptionId->id;
                $configurableDcHidden = $getOptionId->hidden;
                $head_return = '<script type="text/javascript">

                    jQuery(document).ready(function(){

                        var dcRequired = "' . $dcRequired . '";
                        var DcConfigOpt = "'.$configurableDcHidden.'";
                        getOsVersion(dcRequired);

                        jQuery("select[name=\'configoption[' . $configurableOsFamilyId . ']\']").change(function(){

                            getOsVersion(dcRequired);

                        });
                        if(DcConfigOpt == 0){
                            jQuery("select[name=\'configoption[' . $configurableDcId . ']\']").change(function(){
                                //if(dcRequired)
                                    getOsVersion(dcRequired);

                            });
                        }else{
                            jQuery("#customfield' . $datacenterCfId . '").change(function(){
                                //if(dcRequired)
                                    getOsVersion(dcRequired);

                            });
                        }

                        jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").find("option").remove();';

                $head_return .= '});

                        function getOsVersion(dcRequired){

                            var osValue = jQuery("select[name=\'configoption[' . $configurableOsFamilyId . ']\'] :selected").val();
                            // :selected
                            if(jQuery("select[name=\'configoption[' . $configurableDcId . ']\']").html())
                                var datacenter = jQuery.trim(jQuery("select[name=\'configoption[' . $configurableDcId . ']\']").val());
                            else
                                var datacenter = jQuery("#customfield' . $datacenterCfId . '").val();

                            jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").find("option").remove();

                            jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").append("<option>Loading...</option>");

                            jQuery.ajax({

                                type: "post",

                                url: "../modules/servers/vmware/ajax/ajaxpost.php",

                                data: "custom=ajax&configurableoption=true&os_list="+jQuery.trim(osValue)+"&sid=' . $sid . '&pid=' . $pid . '&dc="+datacenter+"&configoptionid=' . $configurableOsVesrionId . '&i=' . $i . '",

                                success: function (response){

                                    if(response != ""){
                                        jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").find("option").remove();

                                        jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").append(response);

                                        jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").trigger("change");

                                    }else{

                                        jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").find("option").remove();

                                        jQuery("select[name=\'configoption[' . $configurableOsVesrionId . ']\']").append("<option value>Not Found</option>");

                                    }

                                }

                            });

                        }

                    </script>';
            }
        }

        return $head_return;
    }
}

function manageVmProductHook($vars)
{

    global $whmcs;

    $i = $whmcs->get_req_var("i");

    try {
        $pid = $_SESSION['cart']['products'][$i]['pid'];
        $productDetail = Capsule::table('tblproducts')->where('id', $pid)->get();

        if ($productDetail[0]->servertype == 'vmware') {

            hook_vmware_includes_files();

            $WgsVmwareObj = new WgsVmware();

            $WgsVmwareObj->vmware_includes_files();

            $params = (array) $productDetail[0];

            $dhcp = $params['configoption18'];

            $customFieldVal = vmwareGetCustomFiledVal($params, 'hook');

            $customvmid = getWhmcsProductCustomFieldsDetail($pid, $customFieldVal['vm_name_field']);

            $osTypeFieldId = getWhmcsProductCustomFieldsDetail($pid, $customFieldVal['os_type_field']);

            $customGuestOsVersion = getWhmcsProductCustomFieldsDetail($pid, $customFieldVal['os_version_field']);

            $datacenterCfId = getWhmcsProductCustomFieldsDetail($pid, $customFieldVal['datacenter_field']);

            $configFieldName = $customFieldVal['additional_ip_field'];
            $datacenterConfigFieldName=$customFieldVal['datacenter_field'];
            $serverName = $productDetail[0]->configoption3; # API Connection Server Name

            $serverData = Capsule::table('mod_vmware_server')->where('server_name', $serverName)->get();
            if (count($serverData) == 0)
                $serverData = Capsule::table('mod_vmware_server')->where('id', $serverName)->get();

            $getip = explode('://', $serverData[0]->vsphereip);

            $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

            $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

            if (!empty($vars['customfield'][$customvmid])) {

                $info = $vms->get_vm_info($vars['customfield'][$customvmid]);

                $vmslist = $WgsVmwareObj->vmware_object_to_array($info);

                if (count($vmslist) > 0) {
                    return 'VmName "' . $vars['customfield'][$customvmid] . '" already exist!';
                }
            }


            if (!empty($customGuestOsVersion)) {
                if (isset($vars['customfield'][$customGuestOsVersion]) && empty($vars['customfield'][$customGuestOsVersion])) {
                    return 'Guest OS Version is required.';
                }
            }
            $getConfigGid = Capsule::table("tblproductconfiggroups")->where('name', 'Vmware Resources')->get();

            if ($productDetail[0]->configoption1 == 'on') {

                $getConfigurableFieldOsVirsonName = $customFieldVal['os_version_field'];

                $getConfigurableFieldId = Capsule::table("tblproductconfigoptions")->where('optionname', 'like', '%' . trim($getConfigurableFieldOsVirsonName) . '%')->where('gid', $getConfigGid[0]->id)->get();

                $getConfigurableFieldIdArr = (array) $getConfigurableFieldId[0];

                $getConfigurableFieldId = $getConfigurableFieldIdArr['id'];

                $explodeArr = explode('|', $getConfigurableFieldIdArr['optionname']);

                if ($vars['configoption'][$getConfigurableFieldId['id']] == '') {

                    //return "The option selected for " . $explodeArr[1] . " is not valid";
                }
            }
            $getConfigFieldId = Capsule::table("tblproductconfigoptions")->where('optionname', 'like', '%' . trim($configFieldName) . '%')->where('gid', $getConfigGid[0]->id)->get();

            $getConfigFieldId = (array) $getConfigFieldId[0];

            if ($productDetail[0]->configoption1 == 'on') {

                $additionalIps = $productDetail[0]->configoption6 + $vars['configoption'][$getConfigFieldId['id']];
            } else{
                $additionalIps = $productDetail[0]->configoption6;
            }
            if ($productDetail[0]->configoption1 == 'on') {
                $guest_os_versionFieldName='guest_os_version';
                $guestOsFieldId = Capsule::table("tblproductconfigoptions")->where('optionname', 'like', '%' . trim($guest_os_versionFieldName) . '%')->where('gid', $getConfigGid[0]->id)->value('id');

                $guestOsOptionId=$vars['configoption'][$guestOsFieldId];
                $customGuestOsVersion= Capsule::table("tblproductconfigoptionssub")->where('id', $guestOsOptionId)->value('optionname');


                $guest_os_familyFieldName='guest_os_family';
                $guestOsFieldId = Capsule::table("tblproductconfigoptions")->where('optionname', 'like', '%' . trim($guest_os_familyFieldName) . '%')->where('gid', $getConfigGid[0]->id)->value('id');

                $guestOsOptionId=$vars['configoption'][$guestOsFieldId];
                $os_family= Capsule::table("tblproductconfigoptionssub")->where('id', $guestOsOptionId)->value('optionname');




                $datacenterCfId = Capsule::table("tblproductconfigoptions")->where('optionname', 'like', '%' . trim($datacenterConfigFieldName) . '%')->where('gid', $getConfigGid[0]->id)->value('id');
                $datacenterOptionId=$vars['configoption'][$datacenterCfId];

                $datacenterval= Capsule::table("tblproductconfigoptionssub")->where('id', $datacenterOptionId)->value('optionname');
            }else{
                if (!empty($datacenterCfId)) {
                    if (empty($vars['customfield'][$datacenterCfId])) {
                        return 'Datacenter is required.11';
                    }
                }
                $datacenterval=$vars['customfield'][$datacenterCfId];
                $os_family=$vars['customfield'][$osTypeFieldId];
                $customGuestOsVersion=$vars['customfield'][$customGuestOsVersion];
            }

            $additionalIp = $additionalIps + 1;

            $server = $productDetail[0]->configoption3;

            $serverData = Capsule::table('mod_vmware_server')->where('server_name', $server)->get();
            if (count($serverData) == 0)
                $serverData = Capsule::table('mod_vmware_server')->where('id', $server)->get();

            $serverId = $serverData[0]->id;
            $getResources = Capsule::table('mod_vmware_temp_list')->where('server_id', $serverId)->where('customname', $customGuestOsVersion)->where('os_family', $os_family)->where('datacenter', $datacenterval)->get();

            if (count($getResources) == 0)
                $getResources = Capsule::table('mod_vmware_os_list')->where('server_id', $serverId)->where('os_version', $customGuestOsVersion)->where('os_family', $os_family)->where('datacenter', $datacenterval)->get();




            $getResources = (array) $getResources[0];

            $hostsystem_name = $getResources['hostname'];

            if (!empty($additionalIp) && $additionalIp > 0) {

                $ipListArr = array();
                 if ($productDetail[0]->configoption1 == 'on') {
                    $dcname=$datacenterval;
                 }else{
                     $dcArr = explode('|', $vars['customfield'][$datacenterCfId]);
                     $dcname=$dcArr[0];
                 }




                if (Capsule::table('mod_vmware_ip_list')->where('server_id', $serverId)->where('hostname', $hostsystem_name)->where('datacenter', $dcname)->where('status', '0')->count() > 0) {

                    $getIpQuery = Capsule::table('mod_vmware_ip_list')->where('server_id', $serverId)->where('hostname', $hostsystem_name)->where('datacenter', $dcname)->where('status', '0')->orderBy('id', 'asc')->inRandomOrder()->limit($additionalIp)->get();
                } else {

                    $getIpQuery = Capsule::table('mod_vmware_ip_list')->where('server_id', $serverId)->where('datacenter', $dcname)->where('status', '0')->orderBy('id', 'asc')->inRandomOrder()->limit($additionalIp)->get();
                }

                foreach ($getIpQuery as $ipList) {

                    $ipList = (array) $ipList;

                    $ipListArr[] = $ipList;
                }

                if (empty($dhcp)) {

                    //                if ($params['configoption2'] == 'on') {

                    if (count($ipListArr) == 0) {

                        return 'Error: No IP found in datacenter, "' . $dcArr[0] . '"';
                    }

                    //                }

                    if (count($ipListArr) > 0) {

                        if ($additionalIp > count($ipListArr)) {

                            return 'Only ' . count($ipListArr) . ' IP\'s is available, you can not purchase more than this limit!';
                        }
                    }
                }
            }
        }
    } catch (Exception $ex) {

        return "Error: {$ex->getMessage()}";
    }
}

function getWhmcsProductCustomFieldsDetail($pid, $fieldName, $detail = null)
{

    $dbResult = Capsule::table('tblcustomfields')->where('type', 'product')->where('relid', $pid)->where('fieldname', 'like', '%' . $fieldName . '%')->get();

    if ($detail)
        return $dbResult[0];
    else
        return $dbResult[0]->id;
}

function vmwareUpdateCustomFiledOnModuleUnsuspend($vars)
{

    $pid = $vars['params']['pid'];

    $query = Capsule::table('tblproducts')->where('id', $pid)->get();

    $params = (array) $query[0];

    if ($params['servertype'] == 'vmware') {

        $result = Capsule::table('tbladmins')->select('id')->get();



        $adminuser = $result[0]->id;

        $command = "updateclientproduct";

        $val["serviceid"] = $vars['params']['serviceid'];

        $val["customfields"] = base64_encode(serialize(array(getWhmcsProductCustomFieldsDetail($vars['params']['pid'], 'mail_status') => '')));



        $results = localAPI($command, $val, $adminuser);
    }
}

function updateDataCentersCF($vars)
{

    global $whmcs;

    try {

        hook_vmware_includes_files();

        $WgsVmwareObj = new WgsVmware();

        $WgsVmwareObj->vmware_includes_files();

        $pid = $vars['pid'];

        $query = Capsule::table('tblproducts')->where('id', $pid)->get();

        $params = (array) $query[0];

        if ($params['servertype'] == 'vmware') {

            # get all cutsomfield value

            $customFieldVal = vmwareGetCustomFiledVal($params, 'hook');

            $serverName = filter_var($_POST['packageconfigoption'][3], FILTER_SANITIZE_STRING);

            $serverData = Capsule::table('mod_vmware_server')->where('server_name', $serverName)->get();
            if (count($serverData) == 0)
                $serverData = Capsule::table('mod_vmware_server')->where('id', $serverName)->get();

            $serverid = $serverData[0]->id;

            $getip = explode('://', $serverData[0]->vsphereip);

            $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

            $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

            $datacenter = $vms->list_datacenters();

            $dCenters = $WgsVmwareObj->vmware_object_to_array($datacenter[0]);

            if (isset($dCenters['RetrievePropertiesResponse']['returnval'])) {
                $dCentersName = '';
                foreach ($dCenters['RetrievePropertiesResponse']['returnval'] as $key => $dCenterValue) {
                    if ($key == 'obj' && $key != '0') {
                        $dCentersName .= $dCenters['RetrievePropertiesResponse']['returnval']['propSet'][0]['val'] . ',';
                    } else if ($key == 'propSet' && $key != '0') {
                    } else {

                        $dCentersName .= $dCenterValue['propSet'][0]['val'] . ',';
                    }
                }

                $dCentersName = rtrim($dCentersName, ',');

                $updatedDatacenterName = Capsule::table('tblcustomfields')
                    ->where('relid', $pid)
                    ->where('type', 'product')
                    ->where('fieldname', 'like', '%' . $customFieldVal['datacenter_field'] . '%')
                    ->where('fieldoptions', '=', '')
                    ->update(
                        [
                            'fieldoptions' => $dCentersName,
                        ]
                    );
            }
        }
    } catch (Exception $ex) {

        logActivity("couldn't update datacenter to pid: $pid . {$ex->getMessage()}");
    }
}

function productUpgradeValidate($vars)
{
    global $whmcs;

    $fileArr = explode('/', $_SERVER['PHP_SELF']);

    $count = count($fileArr) - 1;

    $path = $fileArr[$count];

    $sid = $whmcs->get_req_var("id");

    $type = $whmcs->get_req_var("type");

    if (isset($sid)) {

        $serviceId = $sid;
    } else {

        $serviceId = $vars['id'];
    }

    $getProductId = Capsule::table('tblhosting')->select('packageid')->where('id', $serviceId)->get();

    $getProductId = (array) $getProductId[0];

    $productId = $getProductId['packageid'];

    $getModuleName = Capsule::table('tblproducts')->select('servertype')->where('id', $productId)->get();

    $module = $getModuleName[0]->servertype;

    if ($module == 'vmware') {

        if ($path == 'upgrade.php' || $type == 'configoptions') {

            $jquery = '<script type="text/javascript">

                    jQuery(document).ready(function(){

                        if(!jQuery("input[name=\'promocode\']").attr("type")){

                            jQuery("input[value=\'configoptions\']").parent().attr("onsubmit","return false");

                            jQuery("input[value=\'configoptions\']").parent().find("input[type=\'submit\']").click(function(){

                                jQuery.ajax({

                                    type: "post",

                                    url: "modules/servers/vmware/ajax/ajaxpost.php",

                                    data: "custom=ajax&get=additionalipstatus&pid=' . $productId . '&serviceid=' . $serviceId . '&"+jQuery("input[value=\'configoptions\']").parent().serialize(),

                                    success: function (response){

                                        if(response != "success"){

                                            jQuery("#errordiv").remove();

                                            var responseHtml = "<div id=\'errordiv\' class=\'alert alert-danger\'> <strong>Error!</strong> "+response+".</div>";

                                            jQuery("input[value=\'configoptions\']").parent().before(responseHtml);

                                        }else{

                                            jQuery("input[value=\'configoptions\']").parent().attr("onsubmit","return true");

                                            jQuery("input[value=\'configoptions\']").parent().submit();

                                        }

                                    }

                                });

                            });

                        }

                    });

                    </script>

                ';

            return $jquery;
        }
    }
}

function vmwareGetCfIds($fieldName = null, $vars)
{

    $getCfIdQuery = Capsule::table('tblcustomfields')->where('type', 'product')->where('relid', $vars['pid'])->where('fieldname', 'like', '%' . $fieldName . '%')->get();

    $getCfId = $getCfIdQuery[0]->id;

    $getCfValQuery = Capsule::table('tblcustomfieldsvalues')->where('fieldid', $getCfId)->where('relid', $vars['serviceid'])->get();

    return $getCfValQuery[0]->value;
}

function vmwareGetConfigurableOptionId($gid, $optionName = null)
{

    $getOptionId = Capsule::table('tblproductconfigoptions')->where('gid', $gid)->where('optionname', 'like', '%' . $optionName . '%')->first();

    return $getOptionId->id;
}

add_hook('AdminAreaHeadOutput', 1, 'vmwareAdminAreaHeadOutput');

add_hook('ClientAreaHeadOutput', 0, 'vmware_client_area_head_output');

add_hook('ShoppingCartValidateProductUpdate', 1, 'manageVmProductHook');

add_hook('AfterModuleUnsuspend', 1, 'vmwareUpdateCustomFiledOnModuleUnsuspend');

add_hook('AdminProductConfigFieldsSave', 2, 'updateDataCentersCF');

add_hook('ClientAreaHeadOutput', 2, 'productUpgradeValidate');
