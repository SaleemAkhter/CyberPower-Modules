<?php

$customAction = $_REQUEST["customAction"];

$list = $CF->getPageRule();

$listarr = [];

//echo "<pre>";
 //print_r($list['result']);
foreach ($list['result'] as $key => $value) {

	$get_urlrule = $value['targets'][0]['constraint']['value'];
	$get_rule_id = $value['actions'][0]['id'];
	$get_rule_val = $value['actions'][0]['value'];
	//$pagerule_id = $list['result']['0']['id'];
		$getallrule = [];

	if($value['actions'][0]['value']['status_code'] == 302){

	  	$get_rule_val = 'Forwarding URL (Status Code: 302 - Temporary Redirect, '.$value['actions'][0]['value']['url'].')';

		$getallrule[] =  ['rulename' =>  $get_rule_val];
		$pagerule_id = $value['id'];

	}elseif($value['actions'][0]['value']['status_code'] == 301){

	 	$get_rule_val = 'Forwarding URL (Status Code: 301 - Permanent Redirect, '.$value['actions'][0]['value']['url'].')';

		$getallrule[] = [ 'rulename' =>  $get_rule_val];
		$pagerule_id = $value['id'];

	}else{

		$get_rule_id = [];

		$get_rule_val = [];

		$getallrule = [];

		// $pagerule_id = [];
		foreach ($value['actions'] as $mkey => $mvalue) {

			//$pagerule_id = array();

			$pagerule_id = $value['id'];
			$get_rule_val = array();
			$get_rule_id = getrulename($mvalue['id']);

			if(is_array($mvalue['value'])){
			 	foreach ($mvalue['value'] as $subkey => $val) {
			 		if($val== 'on'){
		 			$get_rule_val[] = $subkey;
		 			}
			 	}
			}else{ 

				if(is_int($mvalue['value'])){

					$getintval = getsubruleval($mvalue['value']);

					$get_rule_val[] =  $getintval; 

				}elseif($get_rule_id == 'Cache Level'){

					$getintval = cache_levelname($mvalue['value']);

					$get_rule_val[] =  $getintval; 

				}elseif($get_rule_id == 'Security Level'){

					$getintval = securitylevel($mvalue['value']);

					$get_rule_val[] =  $getintval; 

				}else{

					$get_rule_val[] = $mvalue['value'];  

				}
			 }

			$getallrule[] = [ 'rulename' => $get_rule_id , 'ruleval' => $get_rule_val]; 

		}

	}

	$listarr[]= array(

					'url'=> $get_urlrule,

   					'pageruleid'=> $pagerule_id,

   					'pageruleall'=> $getallrule,

					'ruleid'=> $get_rule_id,

					'ruleval'=> $get_rule_val

				);

}

/*echo"<pre>";

 print_r($list['result']);
echo "ttttttttttttt";
print_r($listarr);

 die;*/

$pagerulearr = $listarr;

$vars["pagerulelist"] = $pagerulearr;

function getrulename($getrulename){

	$rulename = array("always_online" =>'Always Online',"minify" =>'Auto Minify',"browser_cache_ttl" =>'Browser Cache TTL',"browser_check" =>'Browser Integrity Check',"cache_deception_armor" =>'Cache Deception Armor',"cache_level" =>'Cache Level',"disable_apps" =>'Disable Apps',"disable_performance" =>'Disable Performance',"disable_railgun" =>'Disable Railgun',"disable_security" =>'Disable Security',"edge_cache_ttl" =>'Edge Cache TTL',"email_obfuscation" =>'Email Obfuscation',"forwarding_url" =>'Forwarding URL',"ip_geolocation_header" =>'IP Geolocation Header',"origin_cache_control" =>'Origin Cache Control',"rocket_loader" =>'Rocket Loader',"security_level" =>'Security Level',"server_side_excludes" =>'Server Side Excludes',"ssl" =>'SSL',

		 );

	 foreach ($rulename as $key => $val) {

       if ($key === $getrulename) {

  	 		return $val;

       }

   } 

}



function getsubruleval($getsubruleval){

	$ruleval = array(

		"1800" => "30 minutes",

		"3600" => "1 hours",

		"7200" => "2 hours",

		"10800" => "3 hours",

		"14400" => "4 hours",

		"18000" => "5 hours",

		"28800" => "8 hours",

		"43200" => "12 hours",

		"57600" => "16 hours",

		"72000" => "20 hours",

		"86400" => "1 day",

		"172800" => "2 days",

		"259200" => "3 days",

		"345600" => "4 days",

		"432000" => "5 days",

		"518400" => "6 days",

		"604800" => "7 days",

		"691200" => "8 days",

		"1209600" => "14 days",

		"1382400" => "16 days",

		"2073600" => "24 days",

		"2678400" => "1 month",

		"5356800" => "2 months",

		"16070400" => "6 months",

		"31536000" => "1 year",

	 );

	 foreach ($ruleval as $key => $val) {

       if ($key === $getsubruleval) {

       	return $val;

       }

   }

}

function cache_levelname($getrulename){

	$rulename = array("basic" =>'No Query String',"bypass" =>'By Pass',"simplified" =>'Ignore Query String',"aggressive" =>'Standard',"cache_everything" =>'Cache Everything'

		 );

	 foreach ($rulename as $key => $val) {

       if ($key === $getrulename) {

       	return $val;

       }

   }

}

function securitylevel($getrulename){

	$rulename = array("essentially_off" =>"Essentially Off","low" =>"Low","medium" =>"Medium","high" =>"High" ,"under_attack" =>"I'm Under Attack"

		 );

	 foreach ($rulename as $key => $val) {

       if ($key === $getrulename) {

       	return $val;

       }

   }

}


if(isset($_REQUEST["customAction"])){

	 switch ($_REQUEST["customAction"]) {

        case "insertpageruledata":

          	$rules = $whmcs->get_req_var('pagerulesettings');

		 	$value = $whmcs->get_req_var('subrule');  

		 	$targeturl = $whmcs->get_req_var('targeturl');

		  	$arry = array_combine($rules,$value); 

			$arr=[];

			foreach ($arry as $key => $value) {

				if($key == 'disable_apps'){

					$arr[] =["id"=>$key ];

				}elseif($key == 'disable_performance'){

					$arr[] =["id"=>$key ];

				}elseif($key == 'disable_railgun'){

					$arr[] =["id"=>$key ];

				}elseif($key == 'disable_security'){

					$arr[] =["id"=>$key ];

				}elseif($key == 'forwarding_url'){

					$dataforwardingurl = '{"status":"active","priority":1,"actions":[{"id":"'.$key.'","value":{"status_code":'.$value['status_code'].',"url":"'.$value['url'].'"}}],"targets":[{"target":"url","constraint":{"operator":"matches","value":"'.$targeturl.'"}}]}';

				 

					$response = $CF->create_page_rule($dataforwardingurl);

					if($response['success'] == 1){

						echo $success =  "success";

					}elseif($response['result'] == 'error'){

						echo $error = " Error  ".$response['data']['apierror'] ."(error code".$response['data']['cferrorcode'].")";

					}

					exit();

				}else{ 

					//echo $value; die;

					//$arr[] =["id" => $key,"value" => $value];

					if(is_numeric($value)){

						$val = (int)$value;

						$arr[] =["id" => $key,"value" => $val ];

					}else{

						$arr[] =["id" => $key,"value" => $value ];

					}

				}
			}

			$targetarr[] =  array('target' => 'url', 'constraint'=> ['operator' => 'matches',

					  						'value' => $targeturl]);  

			$dataarry = [ 

				'targets' => $targetarr,

				'actions' => $arr,

				'priority' => 1,

				'status' => 'active'



				];

			$data = json_encode($dataarry);	

			$response = $CF->create_page_rule($data);

			if($response['success'] == 1){

				echo $success =  "success";

			}elseif($response['result'] == 'error'){

				echo $error = " Error  ".$response['data']['apierror'] ."(error code".$response['data']['cferrorcode'].")";

			}

			exit();

            break;

        case "delpagerule":

            $pageid = $_REQUEST["pageid"];

			$response = $CF->page_rule_del($pageid);

			if($response['success']== 1){

				echo $success =  "deleted";

			}elseif($response['result'] == 'error'){

				echo $error = " Error  ".$response['data']['apierror'] ."(error code".$response['data']['cferrorcode'].")";

			} 

			exit();

            break;

        case "updatepagerule":

				$rules = $whmcs->get_req_var('pagerulesettings');

				$value = $whmcs->get_req_var('subrule');  

				$ruleid = $whmcs->get_req_var('ruleid');  

				$targeturl = $whmcs->get_req_var('targeturl');

				$forwarding_url_destination = $whmcs->get_req_var('forwarding_url_destination');

			  	$arry = array_combine($rules,$value);

				$arr=[];

				foreach ($arry as $key => $value) {

					if($key == 'disable_apps'){

						$arr[] =["id"=>$key ];

					}elseif($key == 'disable_performance'){

						$arr[] =["id"=>$key ];

					}elseif($key == 'disable_railgun'){

						$arr[] =["id"=>$key ];

					}elseif($key == 'disable_security'){

						$arr[] =["id"=>$key ];

					}elseif($key == 'forwarding_url'){

					 

					$dataforwardingurl = '{"status":"active","priority":1,"actions":[{"id":"'.$key.'","value":{"status_code":'.$value['status_code'].',"url":"'.$value['url'].'"}}],"targets":[{"target":"url","constraint":{"operator":"matches","value":"'.$targeturl.'"}}]}';

					
					$response = $CF->update_page_rule($ruleid,$dataforwardingurl);

					if($response['success'] == 1){

						echo $success =  "success";

					}elseif($response['result'] == 'error'){

						echo $error = " Error  ".$response['data']['apierror'] ."(error code".$response['data']['cferrorcode'].")";

					}

					exit();

					}else{

						if(is_numeric($value)){

						$val = (int)$value;

							$arr[] =["id" => $key,"value" => $val ];

						}else{

							$arr[] =["id" => $key,"value" => $value ];

						}

					}

				}

				$targetarr[] =  array('target' => 'url', 'constraint'=> ['operator' => 'matches',

						  						'value' => $targeturl]);  

				$dataarry = [ 

					'targets' => $targetarr,

					'actions' => $arr,

					'priority' => 1,

					'status' => 'active'

					];


				$data = json_encode($dataarry);	

				$update_response = $CF->update_page_rule($ruleid,$data);

			 

				if($update_response['success'] == 1){

					echo $success =  "success";

				}elseif($update_response['result'] == 'error'){

					echo $error = " Error  ".$update_response['data']['apierror'] ."(error code".$update_response['data']['cferrorcode'].")";

				}

				exit();

            break;

         
         case "getruledetail":

         	$ruleid = $whmcs->get_req_var('ruleid');  

            $ruledetail = $CF->page_rule_detail($ruleid);

            $gettargeturl = $ruledetail['result']['targets'][0]['constraint']['value'];
         
           	$getdetailrulearr =  array('targeturl' => $gettargeturl, 'rules' => $ruledetail['result']['actions']);
            echo json_encode($getdetailrulearr);

             exit();

            break;

    }

}


$templateFile = "template/pagerulestab/pagerules";

 

?>