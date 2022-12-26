<?php

namespace WGS\MODULES\CLOUDFLARE;

use WHMCS\Database\Capsule;

class wgs_cloudflare
{

    private $ApiUrl;
    public $ApiEmail;
    public $ApiKey;
    public $zoneidentifier;
    public $salt;

    public function __construct()
    {
        $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();
        $this->ApiUrl = $getSetting->api_url;
        $this->ApiKey = decrypt($getSetting->api_key);
        $this->ApiEmail = $getSetting->email;
        $this->salt = "cloudflare sensitve(data) encrypt/decrypt";
    }

    public function createProductCustomFields($pid)
    {
        $fieldArr = [
            "cloudflare_domain" => ["type" => "product", "relid" => $pid, "fieldname" => "cloudflare_domain|Domain", "fieldtype" => "text", "description" => "Enter registered domain for create zone.", "adminonly" => "", "required" => "on", "showorder" => "on", "sortorder" => "0"],
            "zone_id" => ["type" => "product", "relid" => $pid, "fieldname" => "zone_id|Zone ID", "fieldtype" => "text", "description" => "Admin only", "adminonly" => "on", "required" => "", "showorder" => "", "sortorder" => "1"],
            "member_id" => ["type" => "product", "relid" => $pid, "fieldname" => "member_id|Member ID", "fieldtype" => "text", "description" => "Admin only", "adminonly" => "on", "required" => "", "showorder" => "", "sortorder" => "2"],
            "zone_sub_id" => ["type" => "product", "relid" => $pid, "fieldname" => "zone_sub_id|Zone Subscription ID", "fieldtype" => "text", "description" => "Admin only", "adminonly" => "on", "required" => "", "showorder" => "", "sortorder" => "3"],
            "dns_ip" => ["type" => "product", "relid" => $pid, "fieldname" => "dns_ip|DNS IP", "fieldtype" => "text", "description" => "Enter IP for create (A) type DNS records when domain is adding on Cloudflare.", "adminonly" => "", "required" => "", "showorder" => "on", "sortorder" => "4"],
            "cf_email" => ["type" => "product", "relid" => $pid, "fieldname" => "cf_email|CloudFlare Email Address", "fieldtype" => "text", "description" => "Enter a valid Email Address. You cannot change this email address later. You can use your existing CF account detail.", "adminonly" => "", "required" => "", "showorder" => "on", "sortorder" => "5"],
            "cf_username" => ["type" => "product", "relid" => $pid, "fieldname" => "cf_username|CloudFlare Username", "fieldtype" => "text", "description" => "If you do not enter username then Cloudflare will auto-generate username.", "adminonly" => "", "required" => "", "showorder" => "on", "sortorder" => "6"],
            "cf_password" => ["type" => "product", "relid" => $pid, "fieldname" => "cf_password|CloudFlare Password", "fieldtype" => "password", "description" => "Enter Password. For existing CF account please enter your correct password.", "adminonly" => "", "required" => "", "showorder" => "on", "sortorder" => "7"],
            "cf_user_key" => ["type" => "product", "relid" => $pid, "fieldname" => "cf_user_key|Cloudflare User Key", "fieldtype" => "text", "description" => "Cloudflare User Key, For developer use only", "adminonly" => "on", "required" => "", "showorder" => "", "sortorder" => "8"],
            "cf_user_api_key" => ["type" => "product", "relid" => $pid, "fieldname" => "cf_user_api_key|Cloudflare User API Key", "fieldtype" => "text", "description" => "Cloudflare User API Key, For developer use only", "adminonly" => "on", "required" => "", "showorder" => "", "sortorder" => "9"],
        ];
        foreach ($fieldArr as $key => $field) {
            $fieldArr = explode('|', $field['fieldname']);
            if (Capsule::table("tblcustomfields")->where('type', 'product')->where('relid', $field['relid'])->where('fieldname', 'like', '%' . $fieldArr[1] . '%')->count() == 1) {
                try {
                    Capsule::table("tblcustomfields")->where('type', 'product')->where('relid', $field['relid'])->where('fieldname', 'like', '%' . $fieldArr[1] . '%')->update(['type' => $field['type'], 'fieldname' => $field['fieldname']]);
                } catch (Exception $ex) {
                    logActivity("update failed tblcustomfields. Error: {$ex->getMessage()}");
                }
            } elseif (Capsule::table("tblcustomfields")->where('type', 'product')->where('relid', $field['relid'])->where('fieldname', 'like', '%' . $key . '%')->count() == 0) {
                try {
                    Capsule::table("tblcustomfields")->insert($field);
                } catch (Exception $ex) {
                    logActivity("insert failed tblcustomfields. Error: {$ex->getMessage()}");
                }
            }
        }
    }

    public function createProductConfigurableOption($pid)
    {
        $configurableOptionGroupName = 'Cloudflare' . $pid;
        $configurableOptionArr = array(
            array(
                'optionName' => 'additional_domains|Additional Domains',
                'optiontype' => '4',
                'min' => '0',
                'max' => '10',
                'order' => '0',
                "hidden" => ''
            )
        );
        $configurableGroupId = '';
        $getExistingConfigId = Capsule::table('tblproductconfiggroups')->where('name', $configurableOptionGroupName)->first();
        if (count($getExistingConfigId) > 0) {
            $configurableGroupId = $getExistingConfigId->id;
        }
        if ($configurableGroupId != '') {
            if (Capsule::table('tblproductconfiglinks')->where('gid', $configurableGroupId)->where('pid', $pid)->count() == 0)
                Capsule::table('tblproductconfiglinks')->insert(['gid' => $configurableGroupId, 'pid' => $pid]);
        } else {
            $configurableGroupId = Capsule::table('tblproductconfiggroups')->insertGetId(
                [
                    'name' => $configurableOptionGroupName,
                    'description' => ''
                ]
            );
            Capsule::table('tblproductconfiglinks')->insertGetId(
                [
                    'gid' => $configurableGroupId,
                    'pid' => $pid,
                ]
            );
            foreach ($configurableOptionArr as $options) {
                $configId = Capsule::table('tblproductconfigoptions')->insertGetId(
                    [
                        'gid' => $configurableGroupId,
                        'optionname' => $options['optionName'],
                        'optiontype' => $options['optiontype'],
                        'qtyminimum' => $options['min'],
                        'qtymaximum' => $options['max'],
                        'order' => $options['order'],
                        'hidden' => $options['hidden'],
                    ]
                );
                $this->cfAddConfigoptionsSub($configId);
            }
        }
    }

    public function cfAddConfigoptionsSub($configId, $optionName = null)
    {
        if ($optionName == '')
            $optionName = '';
        $tblpricing_rel_id = Capsule::table('tblproductconfigoptionssub')->insertGetId(
            [
                'configid' => $configId,
                'optionname' => $optionName,
                'sortorder' => '',
                'hidden' => ''
            ]
        );
        $this->cfAddConfigurablePrice($tblpricing_rel_id);
    }

    public function cfAddConfigurablePrice($tblpricing_rel_id)
    {
        $result = Capsule::table('tblcurrencies')->get();
        foreach ($result as $data) {
            $curr_id = $data->id;
            $curr_code = $data->code;
            $currenciesarray[$curr_id] = $curr_code;
        }

        foreach ($currenciesarray as $curr_id => $currency) {
            Capsule::table('tblpricing')->insertGetId(
                [
                    'type' => 'configoptions',
                    'currency' => $curr_id,
                    'relid' => $tblpricing_rel_id,
                    'msetupfee' => '',
                    'qsetupfee' => '',
                    'ssetupfee' => '',
                    'asetupfee' => '',
                    'bsetupfee' => '',
                    'tsetupfee' => '',
                    'monthly' => '',
                    'quarterly' => '',
                    'semiannually' => '',
                    'annually' => '',
                    'biennially' => '',
                    'triennially' => '',
                ]
            );
        }
    }

    public function checkLicense()
    {
        $result = Capsule::table('mod_cloudflare__reseller_license')->first();
        return $result->status;
    }

    public function updateDBTable()
    {
        Capsule::Schema()->table('mod_cf_manage_domains', function ($table) {
            if (!Capsule::Schema()->hasColumn('mod_cf_manage_domains', 'sub_id'))
                $table->string('sub_id')->nullable();
            if (!Capsule::Schema()->hasColumn('mod_cf_manage_domains', 'sub_label'))
                $table->string('sub_label')->nullable();
        });
    }

    public function CreateDbTable()
    {
        try {

            if (!Capsule::Schema()->hasTable('mod_cf_reseller_services')) {
                Capsule::schema()->create(
                    'mod_cf_reseller_services',
                    function ($table) {
                        $table->increments('id');
                        $table->integer('sid');
                        $table->integer('uid');
                        $table->string('domainname');
                        $table->integer('status');
                    }
                );
            }

            Capsule::Schema()->table('mod_cf_reseller_services', function ($table) {
                if (!Capsule::Schema()->hasColumn('mod_cf_reseller_services', 'status'))
                    $table->integer('status');
            });
        } catch (\Exception $e) {
            logActivity("Unable to create mod_cf_reseller_services: {$e->getMessage()}");
        }
        try {
            if (!Capsule::Schema()->hasTable('mod_cf_p_addons')) {
                Capsule::schema()->create(
                    'mod_cf_p_addons',
                    function ($table) {
                        $table->increments('id');
                        $table->integer('addonid');
                        $table->string('cf_plan');
                        $table->string('cf_plan_name');
                    }
                );
            }
        } catch (\Exception $e) {
            logActivity("Unable to create mod_cf_p_addons: {$e->getMessage()}");
        }
        try {
            if (!Capsule::Schema()->hasTable('mod_cf_manage_service')) {
                Capsule::schema()->create(
                    'mod_cf_manage_service',
                    function ($table) {
                        $table->increments('id');
                        $table->integer('sid');
                        $table->integer('addonid');
                    }
                );
            }
        } catch (\Exception $e) {
            logActivity("Unable to create mod_cf_manage_service: {$e->getMessage()}");
        }

        try {
            if (!Capsule::Schema()->hasTable('mod_cf_manage_users')) {
                Capsule::schema()->create(
                    'mod_cf_manage_users',
                    function ($table) {
                        $table->increments('id');
                        $table->integer('uid');
                        $table->string('cf_uid');
                    }
                );
            }
        } catch (\Exception $e) {
            logActivity("Unable to create mod_cf_manage_users: {$e->getMessage()}");
        }
        try {
            if (!Capsule::Schema()->hasTable('mod_cf_manage_domains')) {
                Capsule::schema()->create(
                    'mod_cf_manage_domains',
                    function ($table) {
                        $table->increments('id');
                        $table->integer('uid');
                        $table->integer('sid');
                        $table->string('zone');
                        $table->string('zoneid');
                        $table->string('sub_id')->nullable();
                        $table->string('sub_label')->nullable();
                    }
                );
            }
        } catch (\Exception $e) {
            logActivity("Unable to create mod_cf_manage_domains: {$e->getMessage()}");
        }
        try {
            if (!Capsule::Schema()->hasTable('mod_cf_zone')) {
                Capsule::schema()->create(
                    'mod_cf_zone',
                    function ($table) {
                        $table->increments('id');
                        $table->integer('uid');
                        $table->string('email');
                        $table->string('password');
                        $table->string('username');
                    }
                );
            }
        } catch (\Exception $e) {
            logActivity("Unable to create mod_cf_zone: {$e->getMessage()}");
        }
        try {
            if (!Capsule::Schema()->hasTable('mod_cf_upgarde_plans')) {
                Capsule::schema()->create(
                    'mod_cf_upgarde_plans',
                    function ($table) {
                        $table->integer('uid');
                        $table->integer('sid');
                        $table->integer('invoiceid');
                        $table->integer('status');
                        $table->string('plan');
                        $table->string('zone');
                        $table->string('zone_id');
                        $table->string('reason')->nullable();
                    }
                );
            }
        } catch (\Exception $e) {
            logActivity("Unable to create mod_cf_upgarde_plans: {$e->getMessage()}");
        }
    }

    public function create_user($name, $type)
    {

        $url = $this->ApiUrl . 'accounts';
        $action = "post";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $post = ['name' => $name, 'type' => $type, 'settings' => ['enforce_twofactor' => false]];
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey,);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'create user', $post, $result);
        return $result;
    }

    public function saveUserId($data)
    {
        if (Capsule::table('mod_cf_manage_users')->where('uid', $data['uid'])->count() == 0)
            Capsule::table('mod_cf_manage_users')->insert($data);
        else
            Capsule::table('mod_cf_manage_users')->where('uid', $data['uid'])->update($data);
    }

    public function create_zone($domainname, $accountId, $type, $sid = null)
    {
        $url = $this->ApiUrl . 'zones';

        $action = "post";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $post = ['name' => $domainname, 'account' => ['id' => $accountId], 'jump_start' => true, 'type' => $type];
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey,);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        if ($result['result'] == 'error') {
            $getCFProducts = Capsule::table('tblproducts')->where('servertype', 'wgs_cf_partnerapi')->get();
            $pidArr = [];
            foreach ($getCFProducts as $product) {
                $pidArr[] = $product->id;
            }
            $getHostingData = Capsule::table('tblhosting')->whereIn('packageid', $pidArr)->where('domain', $domainname)->first();
            if ($getHostingData->id == '')
                $getHostingData->id = $sid;
            if (Capsule::table('mod_cf_reseller_services')->where('sid', $getHostingData->id)->count() == '0') {
                Capsule::table('mod_cf_reseller_services')->insert(['sid' => $getHostingData->id, 'uid' => '0', 'domainname' => $domainname, 'status' => '0']);
            }
        }
        logModuleCall('WGS CF  Reseller Module', 'create zone', $post, $result);
        return $result;
    }

    public function saveZone($data, $update = NULL)
    {
        if (Capsule::table('mod_cf_manage_domains')->where('uid', $data['uid'])->where('sid', $data['sid'])->where('zone', $data['zone'])->count() == 0)
            Capsule::table('mod_cf_manage_domains')->insert($data);
        else
            Capsule::table('mod_cf_manage_domains')->where('uid', $data['uid'])->where('sid', $data['sid'])->where('zone', $data['zone'])->update($data);
    }

    public function deleteZone($data)
    {
        Capsule::table('mod_cf_manage_domains')->where('uid', $data['uid'])->where('sid', $data['sid'])->where('zone', $data['zone'])->delete();
    }

    public function create_zone_subscription($domain, $zoneIdentifier, $plan)
    {
        $url = $this->ApiUrl . 'zones/' . $zoneIdentifier . '/subscription';
        $action = "post";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $ratePlanId = 'free';
        $ratePublic_name = "FREE";
        if ($plan == 'PARTNERS_PRO') {
            $ratePlanId = 'PARTNERS_PRO';
            $ratePublic_name = "PRO";
        } elseif ($plan == 'PARTNERS_BIZ') {
            $ratePlanId = 'PARTNERS_BIZ';
            $ratePublic_name = "Business";
        } elseif ($plan == 'PARTNERS_ENT') {
            $ratePlanId = 'PARTNERS_ENT';
            $ratePublic_name = "Enterprise";
        }
        $post = [
            "app" => ["install_id" => null],
            "id" => $zoneIdentifier,
            "zone" => ["id" => $zoneIdentifier, "name" => $domain],
            "rate_plan" => ["id" => $ratePlanId, "public_name" => $ratePublic_name, "currency" => "USD", "scope" => "zone"],
            "state" => "Paid"
        ];
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey,);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'create zone subscription', $post, $result);
        return $result;
    }

    public function create_zone_subscription_onhook($domain, $zoneIdentifier, $ratePlanId, $ratePublic_name)
    {
        $url = $this->ApiUrl . 'zones/' . $zoneIdentifier . '/subscription';
        $action = "post";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $post = [
            "app" => ["install_id" => null],
            "id" => $zoneIdentifier,
            "zone" => ["id" => $zoneIdentifier, "name" => $domain],
            "rate_plan" => ["id" => $ratePlanId, "public_name" => $ratePublic_name, "currency" => "USD", "scope" => "zone"],
            "state" => "Paid"
        ];
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey,);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'create zone subscription', $post, $result);
        return $result;
    }

    public function cancel_zone_subscription($accountid, $subscriptionsid, $params)
    {

        $url = $this->ApiUrl . 'accounts/' . $accountid . '/subscriptions/' . $subscriptionsid;
        $action = "post";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;

        $post = [
            "reason" => "change package",
        ];
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey,);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'cancel zone subscription', $post, $result);
        return $result;
    }

    public function update_zone_subscription($zoneid, $domain, $plan)
    {
        $url = $this->ApiUrl . 'zones/' . $zoneid . '/subscription';
        $action = "put";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $ratePlanId = 'free';
        $ratePublic_name = "FREE";
        if ($plan == 'PARTNERS_PRO') {
            $ratePlanId = 'PARTNERS_PRO';
            $ratePublic_name = "PRO";
        } elseif ($plan == 'PARTNERS_BIZ') {
            $ratePlanId = 'PARTNERS_BIZ';
            $ratePublic_name = "Business";
        } elseif ($plan == 'PARTNERS_ENT') {
            $ratePlanId = 'PARTNERS_ENT';
            $ratePublic_name = "Enterprise";
        }
        $post = [
            "zone" => ["id" => $zoneid, "name" => $domain],
            "rate_plan" => ["id" => $ratePlanId, "public_name" => $ratePublic_name, "currency" => "USD", "scope" => "zone", "is_contract" => false, "externally_managed" => false],
            "state" => "Cancelled",
        ];
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'update zone subscription', $post, $result);
        return $result;
    }

    public function getCustomFieldId($fieldName, $pid)
    {
        $fieldNameArr = explode('|', $fieldName);

        $getFieldId = Capsule::table("tblcustomfields")->where('type', 'product')->where('relid', $pid)->where('fieldname', 'like', '%' . $fieldNameArr[1] . '%')->first();
        if (count($getFieldId) == 0)
            $getFieldId = Capsule::table("tblcustomfields")->where('type', 'product')->where('relid', $pid)->where('fieldname', 'like', '%' . $fieldName . '%')->first();
        return $getFieldId->id;
    }

    public function updateCustomFieldValues($fieldName, $relid, $value = NULL, $pid)
    {
        $getFieldId = Capsule::table("tblcustomfields")->where('type', 'product')->where('relid', $pid)->where('fieldname', 'like', '%' . $fieldName . '%')->first();
        if (Capsule::table("tblcustomfieldsvalues")->where('relid', $relid)->where('fieldid', $getFieldId->id)->count() == 0) {
            try {
                Capsule::table("tblcustomfieldsvalues")->insert(['fieldid' => $getFieldId->id, 'relid' => $relid, 'value' => $value]);
            } catch (Exception $ex) {
                logActivity("insert failed tblcustomfieldsvalues. Error: {$ex->getMessage()}");
            }
        } else {
            try {
                Capsule::table("tblcustomfieldsvalues")->where('relid', $relid)->where('fieldid', $getFieldId->id)->update(['value' => $value]);
            } catch (Exception $ex) {
                logActivity("Update failed tblcustomfieldsvalues. Error: {$ex->getMessage()}");
            }
        }
    }

    public function getAllZones($aId = NULL, $pageNumber = NULL)
    {  
        if ($aId)
            $url = $this->ApiUrl . "zones?account.id=" . $aId."&per_page=50&page=".$pageNumber;
        else
            $url = $this->ApiUrl . "zones";

        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey, "per_page" => "50");
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        return $result;
    }

    public function getSingleZone($zonename)
    {
        $url = $this->ApiUrl . "zones?name=" . $zonename;
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey, "per_page" => "50");
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        return $result;
    }

    public function get_roles($accountId)
    {
        $url = $this->ApiUrl . 'accounts/' . $accountId . '/roles';
        $action = "get";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;

        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey,);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Reseller Module', 'get role', $url, $result);

        return $result;
    }

    public function add_member($accountId, $roleid, $params)
    {
        $url = $this->ApiUrl . 'accounts/' . $accountId . '/members';
        $action = "post";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $post = ['email' => $params['clientsdetails']['email'], 'roles' => [$roleid]];
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey,);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'add member', $post, $result);
        return $result;
    }

    public function remove_member($accountId, $memberId)
    {
        $url = $this->ApiUrl . 'accounts/' . $accountId . '/members/' . $memberId;
        $action = "delete";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey,);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Reseller Module', 'remove member', $post, $result);
        return $result;
    }

    public function delete_zone($zoneid)
    {

        $url = $this->ApiUrl . 'zones/' . $zoneid;
        $action = "DELETE";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey,);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Reseller Module', 'delete zone', $url, $result);
        return $result;
    }

    public function getZoneDetail()
    {

        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier;
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey, "per_page" => "50");
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Reseller Module', 'get Zone Detail  ', $url, $result);
        return $result;
    }

    public function getDevelopmentModeSettings()
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/development_mode";
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Reseller Module', 'get Developmentmode settings ', $url, $result);
        return $result;
    }

    public function pauseUnpauseSite($status)
    { # $files: should be of array type
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier;
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("paused" => $status);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function purgeAllFiles()
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/purge_cache";
        $action = "delete";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $data = array("purge_everything" => true);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($data));
        return $result;
    }

    public function wgsCfGetDashboard($time)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/analytics/dashboard?since=" . $time;
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        return $result;
    } 
    public function getDomainInfo($domain)
    {
        $url = $this->ApiUrl . "/zones?name=".$domain;
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        return $result;
    }

    public function purgeIndividualFiles($files)
    { # $files: should be of array type
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/purge_cache";
        $action = "delete";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $data = array("files" => $files);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($data));
        return $result;
    }

    /*
     * DNS Records for Zone
     */

    public function listDNSRecords()
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/dns_records?per_page=100";
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        return $result;
    }

    public function dnsRecordDetails($zoneidentifier)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/dns_records/" . $zoneidentifier;
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        return $result;
    }

    public function createDNSRecord($dnsdata)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/dns_records";
        $action = "post";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $type = $dnsdata["cfdnstype"];
        $dnsdata["cfdnsttl"] = intval($dnsdata["cfdnsttl"]);
        if ($dnsdata["cfdnsttl"] == '')
            $dnsdata["cfdnsttl"] = 1;
        if ($type == "A" || $type == "AAAA" || $type == "CNAME" || $type == "SPF" || $type == "TXT" || $type == "NS") {
            $post = array("type" => $dnsdata["cfdnstype"], "name" => $dnsdata["cfdnsname"], "content" => $dnsdata["cfdnsvalue"], "ttl" => $dnsdata["cfdnsttl"]);
        }
        if ($type == "MX") {
            $dnsdata["cfmxpriority"] = intval($dnsdata["cfmxpriority"]);
            $post = array("type" => $dnsdata["cfdnstype"], "name" => $dnsdata["cfdnsname"], "content" => $dnsdata["cfdnsvalue"], "priority" => $dnsdata["cfmxpriority"], "ttl" => $dnsdata["cfdnsttl"]);
        }
        if ($type == "A" || $type == "AAAA" || $type == "CNAME") {
            switch ($dnsdata["proxied"]) {
                case "false":
                    $post = array_merge($post, array('proxied' => false));
                    break;
                case "true":
                    $post = array_merge($post, array('proxied' => true));
                    break;
            }
        }
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function editDNSRecord($dnsdata)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/dns_records/" . $dnsdata["dnsrecordid"];
        $action = "put";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $type = $dnsdata["cfdnstype"];

        switch ($dnsdata["proxiable"]) {
            case "false":
                $dnsdata["proxiable"] = false;
                break;
            case "true":
                $dnsdata["proxiable"] = true;
                break;
        }

        switch ($dnsdata["proxied"]) {
            case "false":
                $dnsdata["proxied"] = false;
                break;
            case "true":
                $dnsdata["proxied"] = true;
                break;
        }

        switch ($dnsdata["locked"]) {
            case "false":
                $dnsdata["locked"] = false;
                break;
            case "true":
                $dnsdata["locked"] = true;
                break;
        }

        if ($type == "A" || $type == "AAAA" || $type == "CNAME" || $type == "SPF" || $type == "TXT" || $type == "NS") {
            $post = array(
                "id" => $dnsdata["dnsrecordid"],
                "type" => $dnsdata["cfdnstype"],
                "name" => $dnsdata["cfdnsname"],
                "content" => $dnsdata["cfdnsvalue"],
                "ttl" => intval($dnsdata["cfdnsttl"]),
                "proxiable" => $dnsdata["proxiable"],
                "proxied" => $dnsdata["proxied"],
                "locked" => $dnsdata["locked"],
                "zone_id" => $dnsdata["zone_id"],
                "zone_name" => $dnsdata["zone_name"]
            );
        }
        if ($type == "MX") {
            $post = array(
                "id" => $dnsdata["dnsrecordid"],
                "type" => $dnsdata["cfdnstype"],
                "name" => $dnsdata["cfdnsname"],
                "content" => $dnsdata["cfdnsvalue"],
                "priority" => $dnsdata["cfmxpriority"],
                "ttl" => intval($dnsdata["cfdnsttl"]),
                "proxiable" => $dnsdata["proxiable"],
                "proxied" => $dnsdata["proxied"],
                "locked" => $dnsdata["locked"],
                "zone_id" => $dnsdata["zone_id"],
                "zone_name" => $dnsdata["zone_name"]
            );
        }
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function enableDisableDnsRecord($dnsdata)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/dns_records/" . $dnsdata["dnsrecordid"];
        $action = "put";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $type = $dnsdata["cfdnstype"];

        switch ($dnsdata["proxiable"]) {
            case "false":
                $dnsdata["proxiable"] = false;
                break;
            case "true":
                $dnsdata["proxiable"] = true;
                break;
        }

        switch ($dnsdata["proxied"]) {
            case "false":
                $dnsdata["proxied"] = false;
                break;
            case "true":
                $dnsdata["proxied"] = true;
                break;
        }

        switch ($dnsdata["locked"]) {
            case "false":
                $dnsdata["locked"] = false;
                break;
            case "true":
                $dnsdata["locked"] = true;
                break;
        }

        if ($type == "A" || $type == "AAAA" || $type == "CNAME" || $type == "SPF" || $type == "TXT" || $type == "NS") {
            $post = array(
                "id" => $dnsdata["dnsrecordid"],
                "type" => $dnsdata["cfdnstype"],
                "name" => $dnsdata["cfdnsname"],
                "content" => $dnsdata["cfdnsvalue"],
                "ttl" => intval($dnsdata["cfdnsttl"]),
                "proxiable" => $dnsdata["proxiable"],
                "proxied" => $dnsdata["proxied"],
                "locked" => $dnsdata["locked"],
                "zone_id" => $dnsdata["zone_id"],
                "zone_name" => $dnsdata["zone_name"]
            );
        }
        if ($type == "MX") {
            $post = array(
                "id" => $dnsdata["dnsrecordid"],
                "type" => $dnsdata["cfdnstype"],
                "name" => $dnsdata["cfdnsname"],
                "content" => $dnsdata["cfdnsvalue"],
                "priority" => $dnsdata["cfmxpriority"],
                "ttl" => intval($dnsdata["cfdnsttl"]),
                "proxiable" => $dnsdata["proxiable"],
                "proxied" => $dnsdata["proxied"],
                "locked" => $dnsdata["locked"],
                "zone_id" => $dnsdata["zone_id"],
                "zone_name" => $dnsdata["zone_name"]
            );
        }

        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function uploadFileSizes()
    {
        $sizes = array();
        $plan = $this->getZoneDetails();
        $legacyid = $plan["result"]["plan"]["legacy_id"];

        if ($legacyid == "enterprise") {
            $sizes["500"] = "500 MB";
            $sizes["475"] = "475 MB";
            $sizes["450"] = "450 MB";
            $sizes["425"] = "425 MB";
            $sizes["400"] = "400 MB";
            $sizes["375"] = "375 MB";
            $sizes["350"] = "350 MB";
            $sizes["325"] = "325 MB";
            $sizes["300"] = "300 MB";
            $sizes["275"] = "275 MB";
            $sizes["250"] = "250 MB";
            $sizes["225"] = "225 MB";
        }
        if ($legacyid == "business" && $legacyid == "enterprise") {
            $sizes["200"] = "200 MB";
            $sizes["175"] = "175 MB";
            $sizes["150"] = "150 MB";
            $sizes["125"] = "125 MB";
        }
        $sizes["100"] = "100 MB";
        return $sizes;
    }

    public function getZoneDetails()
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier;
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Reseller Module', 'get zone detail', $url, $result);
        return $result;
    }

    public function zonePlans()
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/available_plans";
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Reseller Module', 'get zone plands', $url, $result);
        return $result;
    }

    public function wgsCf_formatSizeUnits($bytes)
    {
        if ($bytes >= 1000000000000) {
            $bytes = number_format($bytes / 1000000000000, 2) . ' TB';
        } else if ($bytes >= 1000000000) {
            $bytes = number_format($bytes / 1000000000, 2) . ' GB';
        } elseif ($bytes >= 1000000) {
            $bytes = number_format($bytes / 1000000, 2) . ' MB';
        } elseif ($bytes >= 1000) {
            $bytes = number_format($bytes / 1000, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0';
        }

        return $bytes;
    }

    public function deleteDNSRecord($dnsdata)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/dns_records/" . $dnsdata["dnsrecordid"];
        $action = "delete";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Reseller Module', 'get dns record', $url, $result);
        return $result;
    }

    public function browserCacheTTLSettingValues()
    {
        $ttl = array();
        $plan = $this->getZoneDetails();
        $legacyid = $plan["result"]["plan"]["legacy_id"];

        if ($legacyid == "enterprise") {
            $ttl["30"] = "30 seconds";
            $ttl["60"] = "1 minute";
            $ttl["300"] = "5 minutes";
            $ttl["1200"] = "20 minutes";
        }

        if ($legacyid == "business" || $legacyid == "pro" || $legacyid == "enterprise") {
            $ttl["1800"] = "30 minutes";
        }

        if ($legacyid == "free") {
            $ttl["0"] = "Respect Existing Headers";
            $ttl["1800"] = "30 minutes";
            $ttl["3600"] = "1 hours";
        }


        $ttl["7200"] = "2 hours";
        $ttl["10800"] = "3 hours";
        $ttl["14400"] = "4 hours";
        $ttl["18000"] = "5 hours";
        $ttl["28800"] = "8 hours";
        $ttl["43200"] = "12 hours";
        $ttl["57600"] = "16 hours";
        $ttl["72000"] = "20 hours";
        $ttl["86400"] = "1 day";
        $ttl["172800"] = "2 days";
        $ttl["259200"] = "3 days";
        $ttl["345600"] = "4 days";
        $ttl["432000"] = "5 days";
        $ttl["691200"] = "8 days";
        $ttl["1382400"] = "16 days";
        $ttl["2073600"] = "24 days";
        $ttl["2678400"] = "1 month";
        $ttl["5356800"] = "2 months";
        $ttl["16070400"] = "6 months";
        $ttl["31536000"] = "1 year";

        return $ttl;
    }

    public function changeAlwaysOnlineSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/always_online";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'allways on', $post, $result);
        return $result;
    }

    public function changeBrowserCacheTTLSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/browser_cache_ttl";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => (int) $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change browser cache ttl', $post, $result);
        return $result;
    }

    public function changeMaxUploadSize($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/max_upload";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change max uploads size', $post, $result);
        return $result;
    }

    public function getAllZoneSettings()
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings";
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Reseller Module', 'get all zones', $url, $result);
        return $result;
    }

    public function changeIPv6Setting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/ipv6";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change ipv6', $post, $result);
        return $result;
    }

    public function changePseudoIPv4($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/pseudo_ipv4";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change pseudo ipv4', $post, $result);
        return $result;
    }

    public function changeMinifySetting($css, $html, $js)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/minify";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => array("css" => $css, "html" => $html, "js" => $js));
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change minify setting', $post, $result);
        return $result;
    }

    public function changeMobileRedirectSetting($mode, $subdomain, $stripuri)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/mobile_redirect";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => array("status" => $mode, "mobile_subdomain" => $subdomain, "strip_uri" => $stripuri));
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change redirect setting', $post, $result);
        return $result;
    }

    public function changeMirageSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/mirage";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function changePolishSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/polish";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function changeRocketLoaderSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/rocket_loader";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change rocket loader setting', $post, $result);
        return $result;
    }

    public function changeSecurityHeaderSetting($enabled, $maxage, $includesubdomains, $preload)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/security_header";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => array("strict_transport_security" => array("enabled" => $enabled, "max_age" => $maxage, "include_subdomains" => $includesubdomains, "preload" => $preload)));
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change security header setting', $post, $result);
        return $result;
    }

    public function changeAlwaysUseTttpsSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/always_use_https";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change always use https', $post, $result);
        return $result;
    }

    public function changeTLSclientAuthSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/server_side_exclude";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change tls auth setting', $post, $result);
        return $result;
    }

    public function wgsCfEnableDnsSec($status)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/dnssec";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("status" => $status);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'enable dns sec', $post, $result);
        return $result;
    }

    public function wgsCfManageCnameFlattern($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/cname_flattening";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'manage cname flattern', $post, $result);
        return $result;
    }

    public function changeSecurityLevelSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/security_level";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change security level setting', $post, $result);
        return $result;
    }

    public function wgsCfGetCnameFlattern()
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/cname_flattening";
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Reseller Module', 'get cname flattern', $url, $result);
        return $result;
    }

    public function wgsCfGetDnsSec()
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/dnssec";
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Reseller Module', 'get dnssec', $url, $result);
        return $result;
    }

    public function changeSSLSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/ssl";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change ssl setting', $post, $result);
        return $result;
    }

    public function changeCacheLevelSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/cache_level";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'change cache level setting', $post, $result);
        return $result;
    }

    public function changeWebApplicationFirewallSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/waf";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'Firewall setting', $post, $result);
        return $result;
    }

    public function changeAdvancedDdosProtection($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/advanced_ddos";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'Ddos protection', $post, $result);
        return $result;
    }

    public function changeIpFirewallNotes($id, $notes)
    {
        //        $url = $this->ApiUrl . "user/firewall/access_rules/rules/" . $id;
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/firewall/access_rules/rules/" . $id;
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("notes" => $notes);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'Change Firewall Note', $post, $result);
        return $result;
    }

    public function changeIpFirewalMode($id, $mode)
    {
        //        $url = $this->ApiUrl . "user/firewall/access_rules/rules/" . $id;
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/firewall/access_rules/rules/" . $id;

        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("mode" => $mode);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'Change Firewall Mode', $post, $result);
        return $result;
    }

    public function deleteFirewallIp($id)
    {
        //        $url = $this->ApiUrl . "user/firewall/access_rules/rules/" . $id;
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/firewall/access_rules/rules/" . $id;
        $action = "delete";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Reseller Module', 'Delete Firewall IP', $url, $result);
        return $result;
    }

    public function addFirewallIp($ip, $mode, $notes)
    {
        //        $url = $this->ApiUrl . "user/firewall/access_rules/rules";
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/firewall/access_rules/rules";
        $action = "post";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array(
            "allowed_modes" => array("block", "challenge", "whitelist"),
            "configuration" => array("value" => $ip, "target" => "ip"),
            "group" => array("id" => "owner"),
            "mode" => $mode, "notes" => $notes, "package_id" => "", "triggered_count" => 0,
            "scope" => ['type' => 'zone']
        );
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        logModuleCall('WGS CF  Reseller Module', 'Add Firewall IP', $post, $result);

        return $result;
    }

    public function challengettlvalues($language)
    {
        $ttl = array();
        $ttl["300"] = $language['cf_firewall_challenge_time_5_mnts'];
        $ttl["900"] = $language['cf_firewall_challenge_time_15_mnts'];
        $ttl["1800"] = $language['cf_firewall_challenge_time_30_mnts'];
        $ttl["2700"] = $language['cf_firewall_challenge_time_45_mnts'];
        $ttl["3600"] = $language['cf_firewall_challenge_time_1_hr'];
        $ttl["7200"] = $language['cf_firewall_challenge_time_2_hrs'];
        $ttl["10800"] = $language['cf_firewall_challenge_time_3_hrs'];
        $ttl["14400"] = $language['cf_firewall_challenge_time_4_hrs'];
        $ttl["28800"] = $language['cf_firewall_challenge_time_8_hrs'];
        $ttl["57600"] = $language['cf_firewall_challenge_time_16_hrs'];
        $ttl["86400"] = $language['cf_firewall_challenge_time_1_day'];
        $ttl["604800"] = $language['cf_firewall_challenge_time_1_week'];
        $ttl["2678400"] = $language['cf_firewall_challenge_time_1_mnth'];
        $ttl["31536000"] = $language['cf_firewall_challenge_time_1_year'];
        return $ttl;
    }

    public function getIpFirewallList()
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/firewall/access_rules/rules?per_page=100";
        $action = "get";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        return $result;
    }

    public function securitylevelvalues($language)
    {
        $securityvalues = array();
        $securityvalues["essentially_off"] = $language['cf_firewall_security_level_essentially_off'];
        $securityvalues["low"] = $language['cf_firewall_security_level_low'];
        $securityvalues["medium"] = $language['cf_firewall_security_level_medium'];
        $securityvalues["high"] = $language['cf_firewall_security_level_high'];
        $securityvalues["under_attack"] = $language['cf_firewall_security_level_under_attack'];
        return $securityvalues;
    }

    public function changeBrowserCheckSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/browser_check";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function changeChallengeTTLSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/challenge_ttl";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function changeDevelopmentModeSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/development_mode";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function changeEmailObfuscationSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/email_obfuscation";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function changeServerSideExclude($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/server_side_exclude";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function changeHotlinkProtectionSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/hotlink_protection";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function changeIPGeolocationSetting($value)
    {
        $url = $this->ApiUrl . "zones/" . $this->zoneidentifier . "/settings/ip_geolocation";
        $action = "patch";
        $extra = array("cfusername" => $this->ApiEmail, "cfapikey" => $this->ApiKey);
        $post = array("value" => $value);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, json_encode($post));
        return $result;
    }

    public function IPv4SettingValues($language)
    {
        $ipv4 = array();
        $ipv4["off"] = $language['cf_dns_pseudo_ipv4_off'];
        $ipv4["add_header"] = $language['cf_dns_pseudo_ipv4_add_header'];
        $ipv4["overwrite_header"] = $language['cf_dns_pseudo_ipv4_ow_header'];
        return $ipv4;
    }

    public function dnsrecordtypes()
    {
        $dnsrecordtypes = array();
        $dnsrecordtypes["A"] = "A";
        $dnsrecordtypes["AAAA"] = "AAAA";
        $dnsrecordtypes["CNAME"] = "CNAME";
        $dnsrecordtypes["MX"] = "MX";
        # $dnsrecordtypes["LOC"] = "LOC"; Implement Later on; currently API is not available 
        # $dnsrecordtypes["SRV"] = "SRV"; Implement Later on; currently API is not available
        $dnsrecordtypes["SPF"] = "SPF";
        $dnsrecordtypes["TXT"] = "TXT";
        $dnsrecordtypes["NS"] = "NS";
        return $dnsrecordtypes;
    }

    public function dnsttlvalues($language)
    {
        $ttl = array();
        $ttl["1"] = $language['cf_dns_automatic'];
        $ttl["120"] = $language['cf_dns_2_mnts'];
        $ttl["300"] = $language['cf_dns_5_mnts'];
        $ttl["600"] = $language['cf_dns_10_mnts'];
        $ttl["900"] = $language['cf_dns_15_mnts'];
        $ttl["1800"] = $language['cf_dns_30_mnts'];
        $ttl["3600"] = $language['cf_dns_1_hr'];
        $ttl["7200"] = $language['cf_dns_2_hrs'];
        $ttl["18000"] = $language['cf_dns_5_hr'];
        $ttl["43200"] = $language['cf_dns_12_hr'];
        $ttl["86400"] = $language['cf_dns_1_day'];
        return $ttl;
    }

    public function wgsCfGetClientLanguage($params)
    {
        global $CONFIG;
        if ($_SESSION['Language'] != '')
            $language = strtolower($_SESSION['Language']);
        else if (strtolower($params['clientsdetails']['language']) != '')
            $language = strtolower($params['clientsdetails']['language']);
        else
            $language = $CONFIG['Language'];
        return $language;
    }

    public function wgsCfGetLang($language = null)
    {

        $langfilename = dirname(dirname(__FILE__)) . '/lang/' . $language . '.php';
        if (file_exists($langfilename))
            require_once($langfilename);
        else
            require_once(dirname(dirname(__FILE__)) . '/lang/english.php');

        $lang = wgsCfLanuage();

        if (isset($lang))
            return $lang;
    }

    function websiteSSLvalues($language)
    {
        $ssl = array();
        $ssl["full"] = $language['cf_crypto_ssl_full'];
        $ssl["off"] = $language['cf_crypto_ssl_off'];
        $ssl["flexible"] = $language['cf_crypto_ssl_flexible'];
        $ssl["strict"] = $language['cf_crypto_ssl_strict'];
        return $ssl;
    }

    public function hstsmaxageheader($language)
    {
        $maxage = array();
        $maxage["0"] = $language['cf_crypto_max_age_header_disable'];
        $maxage["2678400"] = $language['cf_crypto_max_age_header_1_mnth'];
        $maxage["5356800"] = $language['cf_crypto_max_age_header_2_mnths'];
        $maxage["8035200"] = $language['cf_crypto_max_age_header_3_mnths'];
        $maxage["10713600"] = $language['cf_crypto_max_age_header_4_mnths'];
        $maxage["13392000"] = $language['cf_crypto_max_age_header_5_mnths'];
        $maxage["16070400"] = $language['cf_crypto_max_age_header_6_mnths'];
        return $maxage;
    }

    /*
     * Encrypt Decrypt Data
     */

    public function cfencrypt($string)
    {
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($string, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        $encrypted = base64_encode($iv . $hmac . $ciphertext_raw);
        return $encrypted;
    }

    public function cfdecrypt($encrypted)
    {
        $data = base64_decode($encrypted);
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = substr($data, 0, $ivlen);
        $hmac = substr($data, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($data, $ivlen + $sha2len);
        $decrypted = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        if (hash_equals($hmac, $calcmac)) { //PHP 5.6+ timing attack safe comparison
            return $decrypted;
        }
    }

    public function updateNS($name_servers = null, $domainId = null, $domain)
    {
        if ($name_servers && $domainId) {
            $updateNs = $this->wgsCfWhmcsLocalApi('DomainUpdateNameservers', array('domainid' => $domainId, 'ns1' => $name_servers[0], 'ns2' => $name_servers[1]));
            if ($updateNs['result'] == 'success') {
                logActivity("NS successfully updated with registrar domain: {$domain}");
            } elseif ($updateNs['result'] == 'error') {
                logActivity("Unable to update nameservers with registrar domain: {$domain} error: {$updateNs['message']}");
            }
        }
    }

    function wgsCfWhmcsLocalApi($command, $data)
    {
        $adminUsername = '';
        $command = $command;
        $postData = $data;

        $results = localAPI($command, $postData, $adminUsername);
        return $results;
    }

    public function __createProductAddons($pid)
    {
        $planArr = [];

        $planArr['Free--0'] = array(
            "name" => 'Free CF Setup Plan',
            "description" => "<br>Make your website faster and safer with CloudFlare<br><i>By selecting this addon</i>",
            "billingcycle" => "Free Account",
            "showorder" => "1",
            "welcomeemail" => 0,
            "weight" => 1,
            "autoactivate" => "1"
        );
        $planArr['PARTNERS_PRO--20'] = array(
            "name" => 'Pro CF Setup Plan',
            "description" => "<br>Make your website faster and safer with CloudFlare<br><i>By selecting this addon</i>",
            "billingcycle" => "Monthly",
            "showorder" => "1",
            "welcomeemail" => 0,
            "weight" => 1,
            "autoactivate" => "1"
        );
        $planArr['PARTNERS_BIZ--200'] = array(
            "name" => 'Business CF Setup Plan',
            "description" => "<br>Make your website faster and safer with CloudFlare<br><i>By selecting this addon</i>",
            "billingcycle" => "Monthly",
            "showorder" => "1",
            "welcomeemail" => 0,
            "weight" => 1,
            "autoactivate" => "1"
        );
        $planArr['PARTNERS_ENT--500'] = array(
            "name" => 'Enterprise CF Setup Plan',
            "description" => "<br>Make your website faster and safer with CloudFlare<br><i>By selecting this addon</i>",
            "billingcycle" => "Monthly",
            "showorder" => "1",
            "welcomeemail" => 0,
            "weight" => 1,
            "autoactivate" => "1"
        );
        foreach ($planArr as $planKey => $pAddonArr) {
            if (Capsule::table('tbladdons')->where('name', $pAddonArr['name'])->count() == 0) {
                $priceArr = explode('--', $planKey);
                try {
                    $addonId = Capsule::table('tbladdons')->insertGetId($pAddonArr);
                } catch (Exception $ex) {
                    logActivity("couldn't insert data in 'tbladdons' error: {$ex->getMessage()}");
                }
                if ($addonId) {
                    if (Capsule::table('mod_cf_p_addons')->where('cf_plan_name', $pAddonArr['name'])->count() == 0) {
                        try {
                            Capsule::table('mod_cf_p_addons')->insert(['addonid' => $addonId, 'cf_plan' => $priceArr[0], 'cf_plan_name' => $pAddonArr['name']]);
                        } catch (Exception $ex) {
                            logActivity("couldn't insert data in 'mod_cf_p_addons' error: {$ex->getMessage()}");
                        }
                    }
                    try {
                        $getCurrency = Capsule::table('tblcurrencies')->get();
                        foreach ($getCurrency as $key => $currency) {
                            Capsule::table('tblpricing')->insert(['type' => 'addon', 'relid' => $addonId, 'currency' => $currency->id, 'monthly' => ($priceArr[1] * $currency->rate)]);
                        }
                    } catch (Exception $ex) {
                        logActivity("couldn't insert data in 'tblpricing' error: {$ex->getMessage()}");
                    }
                }
            }
        }
    }

    public function wgs_cf_get_serviceDomain($serviceId)
    {
        $getDomain = Capsule::table('tblhosting')->where('id', $serviceId)->first();
        return (array) $getDomain;
    }

    function wgs_cf_getProductAddonId($name)
    {
        $result = Capsule::table('tbladdons')->where('name', $name)->first();
        return $result->id;
    }

    public function getPageRule()
    {
        $url = $this->ApiUrl . 'zones/' . $this->zoneidentifier . '/pagerules';
        $action = "get";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Provisioning Module', 'create page rules', $post, $result);
        return $result;
    }

    public function create_page_rule($data)
    {
        $url = $this->ApiUrl . 'zones/' . $this->zoneidentifier . '/pagerules';
        $action = "post";
        $post = $data;
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, $post);
        logModuleCall('WGS CF  Provisioning Module', 'create page rules', $post, $result);
        return $result;
    }

    public function update_page_rule($id, $data)
    {
        $url = $this->ApiUrl . 'zones/' . $this->zoneidentifier . '/pagerules/' . $id;
        $action = "put";
        $post = $data;
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra, $post);
        logModuleCall('WGS CF  Provisioning Module', 'update page rules', $post, $result);
        return $result;
    }

    public function page_rule_del($delid)
    {
        $url = $this->ApiUrl . 'zones/' . $this->zoneidentifier . '/pagerules/' . $delid;
        $action = "delete";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Provisioning Module', 'delete page rules', $url, $result);
        return $result;
    }

    public function page_rule_detail($ruleid)
    {

        $url = $this->ApiUrl . 'zones/' . $this->zoneidentifier . '/pagerules/' . $ruleid;
        $action = "get";
        $cfusername = $this->ApiEmail;
        $cfapikey = $this->ApiKey;
        $extra = array("cfusername" => $cfusername, "cfapikey" => $cfapikey);
        $result = $this->cloudflare_DoRequest($url, $action, $extra);
        logModuleCall('WGS CF  Provisioning Module', 'get  page rule detail', $url, $result);
        return $result;
    }

    public function cloudflare_DoRequest($url, $action, $extra = NULL, $post = NULL)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if (count($extra) > 0) {

            $cfusername = $this->ApiEmail;
            $cfapikey = $this->ApiKey;

            $headers = array(
                "Content-Type: application/json",
                "X-Auth-Email: " . $cfusername,
                "X-Auth-Key: " . $cfapikey
            );
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (strtolower($action) == "get") {
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        }

        if (strtolower($action) == "post") {

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        if (strtolower($action) == "put") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        if (strtolower($action) == "patch") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        if (strtolower($action) == "delete") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $json = curl_exec($ch);

        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $error = curl_error($ch);

        $result = json_decode($json, true);
        // print_r($result['result']);
        // die;
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200 && $error == '' && $result['result'] != '') {
            return $result;
        } else {
            if ($result['success'] == '') {
                $apierror = $result['errors'][0]['message'];
                $cferrorcode = $result['errors'][0]['code'];
            }
            return array("result" => "error", "data" => array("info" => $info, "error" => $error, "cferrorcode" => $cferrorcode, "apierror" => $apierror));
        }
    }

    public function wgs_cf_generateRandomString($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz1234567890';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function wgs_cf_reseller_doCheckLicense($license)
    {
        if ($license) {
            $localkey = '';
            $result = $this->wgs_cf_reseller_checkLicense($license, $localkey);

            $result['licensekey'] = $license;
        } else {
            $result['status'] = 'licensekeynotfound';
        }

        return $result;
    }

    public function wgs_cf_reseller_checkLicense($licensekey, $localkey = "")
    {
        $results['status'] = "Active";
        return $results;
    }
}
