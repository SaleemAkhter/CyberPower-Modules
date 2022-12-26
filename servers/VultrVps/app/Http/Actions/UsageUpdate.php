<?php


namespace ModulesGarden\Servers\VultrVps\App\Http\Actions;


use ModulesGarden\Servers\VultrVps\App\Api\ApiClient;
use ModulesGarden\Servers\VultrVps\App\Api\ApiClientFactory;
use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Helpers\Format;
use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\VultrVps\Core\Models\Whmcs\Hosting;

class UsageUpdate extends AddonController
{
    /**
     * @var ApiClient
     */
    protected $apiClient;
    /*
     *
     * $parms["whmcsVersion"] => "8.2.0-rc.1"
* $parms[server"] => true
* $parms[serverid"] => 2
* $parms[serverip"] => ""
* $parms[serverhostname"] => "api.vultr.com"
* $parms[serverusername"] => ""
* $parms[serverpassword"] => "xxxxxxxxxxxxxx"
* $parms[serveraccesshash"] => ""
* $parms[serversecure"] => true
* $parms[serverhttpprefix"] => "https"
* $parms[serverport"] => ""
* $parms[action"] => "UsageUpdat
     */
    public function execute($params = null)
    {
        try {
            $this->apiClient = ApiClientFactory::formParams($params);
            $query = Hosting::where('server',$params['serverid'])->where('domainstatus','Active');
            foreach ($query->get()  as $hosting){
                try {
                    $this->hostingProccess($hosting);
                }catch (\Exception $ex) {
                        logActivity(sprintf('Vultr Vps Cron Job Error: %s, Hosting ID: #%s', $ex->getMessage(), $hosting->id));
                }
            }
        } catch (\Exception $ex) {
            logActivity(sprintf('Vultr Vps Cron Job Error: %s, Server ID: #%s', $ex->getMessage(), $params['serverid']));
        }
    }

    private function hostingProccess(Hosting  $hosting){
        $instance = InstanceFactory::fromHosting($hosting);
        $instance->setApiClient($this->apiClient);
        $details = $instance->details()->instance;
        //bw limit
        $allowedBandwidthBytes = $details->allowed_bandwidth * pow(1000,3);//GB => Bytes
        //disklimit
        $diskBytes = $details->disk * pow(1000,3);//GB => Bytes
        //bw usage
        $hosting->bwusage = 0;
        $total = 0;
        foreach ($instance->bandwidth()->bandwidth as $data => $bandwidth ){
            $total += $bandwidth->incoming_bytes + $bandwidth->outgoing_bytes;
        }
        //save
        $hosting->bwusage = $total / pow(1000,2);//MB
        $hosting->bwlimit = $allowedBandwidthBytes / pow(1000,2);//MB
        $hosting->disklimit = $diskBytes / pow(1000,2);//MB
        $hosting->diskusage = $diskBytes / pow(1000,2);//MB
        $hosting->lastupdate = date("Y-m-d H:i:s");
        $hosting->save();
    }
}