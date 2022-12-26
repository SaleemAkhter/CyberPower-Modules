<?php
use WHMCS\Database\Capsule;

global $whmcs;
if (isset($_REQUEST["upgardeSub"])) {
    $checkExist = Capsule::table('mod_cf_upgarde_plans')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->where('status', '0')->count();
    if ($checkExist > 0) {
        print json_encode(["status" => 'error', "msg" => '<div class="alert alert-danger" role="alert">Upgrade plan request already in pending state. Pay previous upgrade plan invoice or contact with support!</div>']);
        exit();
    }
    $plan_name = $whmcs->get_req_var('plan_name');
    $zone_id = $whmcs->get_req_var('zone_id');
    if ($zone_id)
        $zone_id = encrypt($zone_id);
    $zone = $whmcs->get_req_var('zone');
    $plan_price = $whmcs->get_req_var('plan_price');

    $getPaymentMethod = Capsule::table('tblhosting')->where('id', $params['serviceid'])->first();
    $getCurrencyDetail = Capsule::table('tblclients')
        ->join('tblcurrencies', 'tblclients.currency', '=', 'tblcurrencies.id')
        ->select('tblcurrencies.*')
        ->where('tblclients.id', $params['userid'])
        ->first();
    if ($plan_name == 'biz')
        $plan_name = "Buisness Plan";
    elseif ($plan_name == 'pro')
        $plan_name = "Pro Plan";
    $paymentmethod = $getPaymentMethod->paymentmethod;
    $price = $plan_price;
    $currencyCode = (string) $getCurrencyDetail->code;
    if ($currencyCode != 'USD') {

        $req_url = 'https://api.exchangerate-api.com/v4/latest/USD';
        $response_json = file_get_contents($req_url);
        if (false !== $response_json) {
            try {
                $response_object = json_decode($response_json);
                $base_price = $plan_price; // Your price in USD
                $price = round(($base_price * $response_object->rates->$currencyCode), 2);
            } catch (Exception $e) {
            }
        }
    }
    $productTaxStatus = Capsule::table('tblhosting')
        ->join('tblproducts', 'tblhosting.packageid', '=', 'tblproducts.id')
        ->select('tblproducts.tax')
        ->where('tblhosting.id', '=', $params['serviceid'])
        ->first();
    $regdate = date('Y-m-d');
    $duedate = date('Y-m-d', strtotime($regdate . " +1 month"));
    $command = 'CreateInvoice';
    $postData = array(
        'userid' => $params['userid'],
        'status' => 'Unpaid',
        'paymentmethod' => $paymentmethod,
        'sendinvoice' => '1',
        'itemdescription1' => 'Upgrade plan to ' . $plan_name,
        'itemamount1' => $price,
        'itemtaxed1' => $productTaxStatus->tax,
        'date' => $regdate,
        'duedate' => $duedate,
    );
    $adminUsername = '';
    $results = localAPI($command, $postData, $adminUsername);
    if ($results['result'] == "success") {
        $desc = 'Upgraded plan - ' . $plan_name;
        $insertData = array('hostingid' => $params['serviceid'], 'userid' => $params['userid'], 'billingcycle' => 'monthly', 'recurring' => $price, 'paymentmethod' => $paymentmethod, 'regdate' => $regdate, 'nextduedate' => $duedate, 'nextinvoicedate' => $duedate, 'name' => $desc, 'status' => 'Pending');
        $addonId = Capsule::table('tblhostingaddons')->insertGetId($insertData);
        $invoiceQuery = Capsule::table('tblinvoiceitems')->where('invoiceid', $results['invoiceid'])->update(array('type' => 'Addon', 'relid' => $addonId));

        try {
            $insertData = ['uid' => $params['userid'], 'sid' => $params['serviceid'], 'invoiceid' => $results['invoiceid'], 'status' => '0', 'plan' => $plan_name, 'zone' => $zone, 'zone_id' => $zone_id];
            Capsule::table('mod_cf_upgarde_plans')->insert($insertData);
        } catch (Exception $ex) {
            logActivity("Unable to insert mod_cf_upgarde_plans: {$ex->getMessage()}");
        }
        print json_encode(["status" => 'success', "msg" => '<div class="alert alert-success" role="alert">Invoice has been sent on your register email address. Please pay to upgrade plan.</div>']);
    } else {
        print json_encode(["status" => 'error', "msg" => '<div class="alert alert-danger" role="alert">' . $results['error'] . '!</div>']);
    }
}

exit();
