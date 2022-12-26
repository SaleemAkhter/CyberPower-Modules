<?php
namespace ModulesGarden\Servers\AwsEc2\App\Models;

use ModulesGarden\Servers\AwsEc2\Core\Models\ExtendedEloquentModel;
use \Illuminate\Database\Capsule\Manager as Capsule;

class Job extends ExtendedEloquentModel
{

    protected $table = 'AwsEc2_Job';


    public static function factory($id = null)
    {
        if ($id !== null)
        {
            $job = Job::where('id', $id)->first();

            return $job;
        }

        return new self();
    }


    public function getJobsByServiceId($sid)
    {
        return Capsule::table('AwsEc2_Job')
            ->where('rel_id', $sid)
            ->get();
    }

    public function modifyChangePackage($jobId, $params)
    {
        return Capsule::table('AwsEc2_Job')
            ->where('id', $jobId)
            ->update(['data' => $params, 'status' => 'waiting']);
    }

}