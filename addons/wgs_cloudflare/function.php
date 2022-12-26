<?php

use WHMCS\Database\Capsule;

global $whmcs;
global $CONFIG;

class Manage_Cloudflare
{

    function __construct()
    {
    }

    public function create_database()
    {
        try {
            Capsule::schema()->create(
                'mod_cloudflare__reseller_license',
                function ($table) {
                    $table->increments('id');
                    $table->string('status');
                }
            );
        } catch (\Exception $e) {
            logActivity("Unable to create mod_cloudflare__reseller_license: {$e->getMessage()}");
        }

        try {
            if (!Capsule::schema()->hasTable('mod_cloudflare__reseller_settings')) {
                Capsule::schema()->create(
                    'mod_cloudflare__reseller_settings',
                    function ($table) {
                        $table->increments('id');
                        $table->string('license_key')->nullable();
                        $table->string('api_url')->nullable();
                        $table->string('api_key')->nullable();
                        $table->string('email')->nullable();
                        $table->string('servicetype')->nullable();
                        $table->string('domain_registrar')->nullable();
                        $table->string('hosting_apikey')->nullable();
                        $table->string('pro_plan_price')->nullable();
                        $table->string('biz_plan_price')->nullable();
                    }
                );
            }
            if (!Capsule::schema()->hasTable('mod_cloudflare__reseller_productsettings')) {
                Capsule::schema()->create(
                    'mod_cloudflare__reseller_productsettings',
                    function ($table) {
                        $table->increments('id');
                        $table->integer('product_id')->nullable();
                        $table->string('plan')->nullable();
                        $table->string('member')->nullable();
                        $table->string('user')->nullable();
                        $table->string('accountid')->nullable();
                        $table->string('member_type')->nullable();
                        $table->longText('zone_type')->nullable();
                        $table->longText('dns_ip')->nullable();
                        $table->integer('domains')->nullable();
                        $table->string('status')->nullable();
                        $table->string('proxy')->nullable();
                    }
                );
            }

            if (!Capsule::schema()->hasTable('mod_cloudflare__reseller_features')) {
                Capsule::schema()->create(
                    'mod_cloudflare__reseller_features',
                    function ($table) {
                        $table->integer('product_id')->nullable();
                        $table->string('features')->nullable();
                        //	$table->string('value')->nullable();
                    }
                );
            }
        } catch (\Exception $e) {
            logActivity("Unable to create Table: {$e->getMessage()}");
        }
    }

    public function drop_db()
    {
        try {
            Capsule::schema()->dropIfExists('mod_cloudflare__reseller_settings');
            Capsule::schema()->dropIfExists('mod_cloudflare__reseller_productsettings');
            Capsule::schema()->dropIfExists('mod_cloudflare__reseller_features');
            Capsule::schema()->dropIfExists('mod_cloudflare__reseller_license');
        } catch (\Exception $e) {
            echo "Unable to drop my_table: {$e->getMessage()}";
        }
    }

    public function update_db()
    {
        try {
            Capsule::Schema()->table('mod_cloudflare__reseller_productsettings', function ($table) {
                if (!Capsule::Schema()->hasColumn('mod_cloudflare__reseller_productsettings', 'user'))
                    $table->string('user')->nullable();
            });

            Capsule::Schema()->table('mod_cloudflare__reseller_productsettings', function ($table) {
                if (!Capsule::Schema()->hasColumn('mod_cloudflare__reseller_productsettings', 'accountid'))
                    $table->string('accountid')->nullable();
            });
        } catch (\Exception $e) {
            echo "Unable to drop my_table: {$e->getMessage()}";
        }
    }

    public function updateModuleSettings()
    {
        Capsule::Schema()->table('mod_cloudflare__reseller_settings', function ($table) {
            if (!Capsule::Schema()->hasColumn('mod_cloudflare__reseller_settings', 'servicetype'))
                $table->string('servicetype')->nullable();
            if (!Capsule::Schema()->hasColumn('mod_cloudflare__reseller_settings', 'hosting_apikey'))
                $table->string('hosting_apikey')->nullable();
            if (!Capsule::Schema()->hasColumn('mod_cloudflare__reseller_settings', 'domain_registrar'))
                $table->string('domain_registrar')->nullable();
        });
    }

    public function get_settings()
    {
        $getdata = Capsule::table('mod_cloudflare__reseller_settings')->first();
        return $getdata;
    }

    public function get_account_list()
    {
        $setting = $this->get_settings();
        $api_url = $setting->api_url;
        $api_key = decrypt($setting->api_key);
        $get_email = $setting->email;
        $url = $api_url . 'accounts';

        $action = "get";
        $extra = array("cfusername" => $url, "cfapikey" => $api_key,);

        $result = $this->cloudflare_DoRequest($url, $action, $get_email, $api_key, $extra);

        return $result;
    }

    public function insert_settings()
    {
        global $whmcs;
        $Licensekey = $whmcs->get_req_var('Licensekey');
        $apiurl = $whmcs->get_req_var('apiurl');
        $domain_registrarEnable = $whmcs->get_req_var('domain_registrar');
        $apikey = encrypt($whmcs->get_req_var('apikey'));
        if ($whmcs->get_req_var('hostingapikey') != '')
            $hostingapikey = encrypt($whmcs->get_req_var('hostingapikey'));
        else
            $hostingapikey = '';
        $email = $whmcs->get_req_var('email');
        $servicetype = $whmcs->get_req_var('servicetype');
        $pro_plan_price = $whmcs->get_req_var('pro_plan_price');
        $biz_plan_price = $whmcs->get_req_var('biz_plan_price');

        Capsule::table('mod_cloudflare__reseller_settings')->delete();

        try {
            $insertdata = [
                'license_key' => $Licensekey,
                'api_url' => $apiurl,
                'api_key' => $apikey,
                'email' => $email,
                'servicetype' => $servicetype,
                'hosting_apikey' => $hostingapikey,
                'domain_registrar' => $domain_registrarEnable,
                'pro_plan_price' => $pro_plan_price,
                'biz_plan_price' => $biz_plan_price,
            ];
            Capsule::table('mod_cloudflare__reseller_settings')->insert($insertdata);
            header('location:' . $modulelink . 'addonmodules.php?module=wgs_cloudflare&action=settings&insert=success');
        } catch (\Exception $e) {
            $error = "{$e->getMessage()}";
            header('location:' . $modulelink . 'addonmodules.php?module=wgs_cloudflare&action=settings&insert=error');
            logActivity("Unable to insert: {$e->getMessage()}");
            echo $error;
        }
    }

    public function get_products()
    {
        $getproducts = Capsule::table('tblproducts')->where('hidden', '0')->whereIn('servertype', ['wgs_cloudflare_reseller', 'wgs_cf_partnerapi'])->get();
        foreach ($getproducts as $value) {
            $pidget = $value->id;
            $productDetail = Capsule::table('mod_cloudflare__reseller_productsettings')->where('product_id', $pidget)->first();

            $total_features = Capsule::table('mod_cloudflare__reseller_features')->where('product_id', $pidget)->first();

            $getfeatures = $total_features->features;
            $total_features = "0";
            if ($getfeatures != '') {
                $ft_array = explode(",", $getfeatures);
                $total_features = count($ft_array);
            }

            $arr_details[] = [
                'productid' => $value->id,
                'name' => $value->name,
                'servertype' => $value->servertype,
                'plan' => $productDetail->plan,
                'member' => $productDetail->member,
                'user' => $productDetail->user,
                'accountid' => $productDetail->accountid,
                'usertype' => $productDetail->member_type,
                'ztype' => $productDetail->zone_type,
                'dnsip' => $productDetail->dns_ip,
                'domains' => $productDetail->domains,
                'status' => $productDetail->status,
                'proxy' => $productDetail->proxy,
                'features' => $total_features,
            ];
        }

        return $arr_details;
    }

    public function mergeExistingProducts()
    {

        $getProducts = Capsule::table('tblproducts')->where('hidden', '0')->where('servertype', 'wgs_cf_partnerapi')->get();
        if (Capsule::Schema()->hasTable('mod_cfsettings')) {
            $getExistingFeatures = Capsule::table('mod_cfsettings')->get();

            $featureArr = [];
            foreach ($getExistingFeatures as $feature) {
                if ($feature->value == 1)
                    $featureArr[] = $feature->setting;
            }

            if ($getProducts) {
                foreach ($getProducts as $product) {
                    $pid = $product->id;
                    $plan = $product->configoption9;
                    if ($plan == 'Free')
                        $plan = 'FREE';
                    elseif ($plan == 'PARTNERS_PRO')
                        $plan = 'CF_RESELLER_PRO';
                    elseif ($plan == 'PARTNERS_BIZ')
                        $plan = 'CF_RESELLER_BIZ';
                    elseif ($plan == 'PARTNERS_ENT')
                        $plan = 'CF_RESELLER_ENT';
                    else
                        $plan = '';
                    $zone_type = ($product->configoption8 == 'zone_set') ? 'partial' : 'full';
                    $data = [
                        'product_id' => $pid,
                        'plan' => $plan,
                        'member' => '0',
                        'member_type' => '',
                        'zone_type' => $zone_type,
                        'dns_ip' => '',
                        'domains' => 1,
                        'status' => 1,
                        'proxy' => 0,
                    ];
                    $Exist = Capsule::table('mod_cloudflare__reseller_productsettings')->where('product_id', $pid)->count();

                    try {
                        if ($Exist == '0') {
                            Capsule::table('mod_cloudflare__reseller_productsettings')->insert($data);
                        } else {
                            // Capsule::table('mod_cloudflare__reseller_productsettings')->where('product_id', $pid)->update($data);
                        }
                    } catch (\Exception $e) {
                        $error = "{$e->getMessage()}";
                        logActivity("Unable to insert: {$e->getMessage()}");
                    }
                    $features = implode(',', $featureArr);
                    $Exist = Capsule::table('mod_cloudflare__reseller_features')->where('product_id', $pid)->count();
                    $insertdata = [
                        'product_id' => $pid,
                        'features' => $features,
                    ];
                    try {
                        if ($Exist == '0') {
                            Capsule::table('mod_cloudflare__reseller_features')->insert($insertdata);
                        } else {
                            //                        Capsule::table('mod_cloudflare__reseller_features')->where('product_id', $pid)->update($insertdata);
                        }
                    } catch (\Exception $e) {
                        $error = "{$e->getMessage()}";
                        logActivity("Unable to insert: {$e->getMessage()}");
                    }
                }
            }
        }
    }

    public function get_product_settings()
    {

        $getproductssettings = Capsule::table('mod_cloudflare__reseller_productsettings')->get();
        foreach ($getproductssettings as $value) {
            $pid = $value->product_id;
            $getpname = Capsule::table('tblproducts')->where('id', $pid)->first();
            $pname = $getpname->name;
            $product_arr[] = [
                'id' => $value->id,
                'pid' => $pid,
                'name' => $pname,
                'plan' => $value->plan,
                'member' => $value->member,
                'membertype' => $value->member_type,
                'zonetype' => $value->zone_type,
            ];
        }

        return $product_arr;
    }

    public function getAllZones($page = null)
    {
        $setting = $this->get_settings();
        $api_url = $setting->api_url;
        $api_key = decrypt($setting->api_key);
        $get_email = $setting->email;
        $url = $api_url . "zones?page=" . $page;
        $action = "get";
        $extra = array("cfusername" => $api_key, "cfapikey" => $api_key, "per_page" => "50");
        $result = $this->cloudflare_DoRequest($url, $action, $get_email, $api_key, $extra);
        return $result;
    }

    public function insert_features()
    {
        global $whmcs;
        $pid = $whmcs->get_req_var('pid');
        $getval = $whmcs->get_req_var('featureget');
        $features = implode(',', $getval);
        $Exist = Capsule::table('mod_cloudflare__reseller_features')->where('product_id', $pid)->count();
        $insertdata = [
            'product_id' => $pid,
            'features' => $features,
        ];

        try {
            if ($Exist == '0') {
                Capsule::table('mod_cloudflare__reseller_features')->insert($insertdata);
                header('location:' . $modulelink . 'addonmodules.php?module=wgs_cloudflare&action=product&feature=success');
            } else {
                Capsule::table('mod_cloudflare__reseller_features')->where('product_id', $pid)->update($insertdata);
                header('location:' . $modulelink . 'addonmodules.php?module=wgs_cloudflare&action=product&featureUpdate=success');
            }
        } catch (\Exception $e) {
            $error = "{$e->getMessage()}";
            logActivity("Unable to insert: {$e->getMessage()}");
            header('location:' . $modulelink . 'addonmodules.php?module=wgs_cloudflare&action=product&feature=error');
            exit();
        }
    }

    public function getfeatures($pid)
    {

        $getdetail = Capsule::table('mod_cloudflare__reseller_features')->where('product_id', $pid)->first();
        $features_name = $getdetail->features;

        return $features_name;
    }

    public function insert_products()
    {
        global $whmcs;
        $pid = $whmcs->get_req_var('idpsetting');
        $user_type = $whmcs->get_req_var('mtype');
        $create_member = $whmcs->get_req_var('member');
        $create_user = $whmcs->get_req_var('user');
        $account_id = $whmcs->get_req_var('username');
        $plan = $whmcs->get_req_var('plan');
        $dnsip = $whmcs->get_req_var('dnsip');
        $zone_type = $whmcs->get_req_var('ztype');
        $Status = $whmcs->get_req_var('Status');
        $proxy = $whmcs->get_req_var('proxy');
        $domains = $whmcs->get_req_var('domains');

        if ($proxy == 'on')
            $proxy = "1";
        else
            $proxy = "0";
        if ($create_member) {
            $create_member = "1";
        } else {
            $create_member = "0";
        }
        if ($create_user) {
            $create_user = "1";
        } else {
            $create_user = "0";
        }

        if ($Status) {
            $Status = 1;
        } else {
            $Status = 0;
        }


        $data = [
            'product_id' => $pid,
            'plan' => $plan,
            'member' => $create_member,
            'user' => $create_user,
            'accountid' => $account_id,
            'member_type' => $user_type,
            'zone_type' => $zone_type,
            'dns_ip' => $dnsip,
            'domains' => $domains,
            'status' => $Status,
            'proxy' => $proxy,
        ];
        $Exist = Capsule::table('mod_cloudflare__reseller_productsettings')->where('product_id', $pid)->count();

        try {
            if ($Exist == '0') {
                Capsule::table('mod_cloudflare__reseller_productsettings')->insert($data);
                header('location:' . $modulelink . 'addonmodules.php?module=wgs_cloudflare&action=product&product=success');
            } else {
                Capsule::table('mod_cloudflare__reseller_productsettings')->where('product_id', $pid)->update($data);
                header('location:' . $modulelink . 'addonmodules.php?module=wgs_cloudflare&action=product&product=update');
            }
        } catch (\Exception $e) {
            $error = "{$e->getMessage()}";
            logActivity("Unable to insert: {$e->getMessage()}");
        }
    }

    public function cloudflare_reseller_checkLicense($licensekey, $localkey = "")
    {
        $results['status'] = "Active";
        return $results;
    }

    public function cloudflare_DoRequest($url, $action, $username, $apikey, $extra = NULL, $post = NULL)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        //   if (count($extra) > 0) {
        $cfusername = $username;
        $cfapikey = $apikey;

        $headers = array(
            "Content-Type: application/json",
            "X-Auth-Email: " . $cfusername,
            "X-Auth-Key: " . $cfapikey
        );
        //    }
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

        //print_r($result);

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200 && !$error && $result) {

            return $result;
        } else {
            if ($result['success'] == '') {
                $apierror = $result['errors'][0]['message'];
                $cferrorcode = $result['errors'][0]['code'];
            }
            return array("result" => "error", "data" => array("info" => $info, "error" => $error, "cferrorcode" => $cferrorcode, "apierror" => $apierror));
        }
    }
}
