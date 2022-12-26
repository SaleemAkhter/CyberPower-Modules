<?php
namespace ModulesGarden\Servers\AwsEc2\App\Models;

use ModulesGarden\Servers\AwsEc2\Core\Models\ExtendedEloquentModel;
use \Illuminate\Database\Capsule\Manager as Capsule;

class NetworkInterface extends ExtendedEloquentModel
{

    protected $table = 'NetworkInterfaces';


    public static function factory($id = null)
    {
        if ($id !== null)
        {
            $elasticip = NetworkInterface::where('id', $id)->first();

            return $elasticip;
        }

        return new self();
    }


    public function getElasticIpByServiceId($sid)
    {
        return Capsule::table('AwsEc2_NetworkInterfaces')
            ->where('service_id', $sid)
            ->get();
    }

}
