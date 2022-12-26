<?php
namespace ModulesGarden\Servers\AwsEc2\App\Models;

use ModulesGarden\Servers\AwsEc2\Core\Models\ExtendedEloquentModel;
use \Illuminate\Database\Capsule\Manager as Capsule;

class ElasticIp extends ExtendedEloquentModel
{

    protected $table = 'ElasticIps';


    public static function factory($id = null)
    {
        if ($id !== null)
        {
            $elasticip = ElasticIp::where('id', $id)->first();

            return $elasticip;
        }

        return new self();
    }


    public function getElasticIpByServiceId($sid)
    {
        return Capsule::table('AwsEc2_ElasticIp')
            ->where('service_id', $sid)
            ->get();
    }

}
