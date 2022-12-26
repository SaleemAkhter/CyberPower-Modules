<?php

namespace ModulesGarden\Servers\AwsEc2\App\Http\Actions;

use ModulesGarden\Servers\AwsEc2\App\Events\VmChangedPackage;
use ModulesGarden\Servers\AwsEc2\App\Models\Job;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\queue;

/**
 * Class ChangePackage
 *
 * @author <slawomir@modulesgarden.com>
 */
class ChangePackage extends CreateAccount
{
    protected $newInstanceType = null;
    protected $newUserData = null;

    public function execute($params = null)
    {
        try
        {
            $this->loadProductConfig($params);

            $this->loadApiConnection();

            $instancesData = $this->apiConnection->describeInstances($this->prepareRequestParams());
            $reservations = $instancesData->get('Reservations');

            $instanceDetails = $reservations[0]['Instances'][0];

            $this->updateInstanceType($params, $instanceDetails);
            $this->updateUserData($params, $instanceDetails);

            //$this->updateSecurityGroups($params, $instanceDetails);

            $this->runInstanceChangePackageEvent($instanceDetails);

            return 'success';
        }
        catch (\Aws\Exception\AwsException $exc)
        {
            return $exc->getAwsErrorMessage();
        }
        catch (\Exception $exc)
        {
            return $exc->getMessage();
        }
    }

    protected function runInstanceChangePackageEvent($instanceDetails = null)
    {
        $job = Job::factory();

        $previousJobs = $job->getJobsByServiceId($this->params['serviceid']);

        $jobData = $this->checkChangePackageJob($previousJobs, $instanceDetails);

        if($jobData)
        {
            $this->modifyChangePackageParams($jobData, $instanceDetails);
            return true;
        }

                queue(
            \ModulesGarden\Servers\AwsEc2\App\Jobs\VmChangedPackage::class, $this->setChangePackageParams($instanceDetails),
            null,
            'Hosting',
            $this->sid,
            $instanceDetails['InstanceId']);

//
//        $ipCount = ($this->isProperIpCount($this->params['configoptions']['ipv4']) ?
//            (int)$this->params['configoptions']['ipv4'] : (int)$this->productSettings['ipv4']);
//
//        queue(
//            \ModulesGarden\Servers\AwsEc2\App\Jobs\VmChangedPackage::class,
//            [
//                'pid' => $this->pid,
//                'sid' => $this->sid,
//                'instanceId' => $instanceDetails['InstanceId'],
//                'ipv4Count' => $ipCount,
//                'tagParams' => $this->getTagsParams(),
//                'securityGroupIds' => (is_array($this->productSettings['securityGroups']) ? $this->productSettings['securityGroups'] : null),
//                'newInstanceType' => $this->newInstanceType,
//                'newUserData' => $this->newUserData,
//                'volumeSize' => $this->params['configoptions']['volumeSize'],
//                'volumeType' => $this->params['configoptions']['volumeType']
//            ],
//            null,
//            'Hosting',
//            $this->sid,
//            $instanceDetails['InstanceId']
//        );
    }

    public function checkChangePackageJob($previousJobs)
    {
        if(!empty($previousJobs))
        {
            foreach($previousJobs as $elem => $key)
            {
                if(strpos($key->job, 'VmChangedPackage') != false && $key->status != 'finished')
                {
                    $prevData = unserialize($key->data);

                    return [
                        'jobid' => $key->id,
                        'prevData' => $prevData];
                }
            }
        }
        return false;
    }

    public function setChangePackageParams($instanceDetails, $previousData = null)
    {
        $ipCount = ($this->isProperIpCount($this->params['configoptions']['ipv4']) ?
            (int)$this->params['configoptions']['ipv4'] : (int)$this->productSettings['ipv4']);

        $params = [
            'pid' => $this->pid,
            'sid' => $this->sid,
            'instanceId' => $instanceDetails['InstanceId'],
            'ipv4Count' => $ipCount != $previousData['ipv4Count'] ? $ipCount : $previousData['ipv4Count'],
            'tagParams' => $this->getTagsParams(),
            'securityGroupIds' => (is_array($this->productSettings['securityGroups']) ? $this->productSettings['securityGroups'] : null),
            'newInstanceType' => $this->newInstanceType,
            'newUserData' => $this->newUserData,
            'volumeSize' => $this->params['configoptions']['volumeSize'] != $previousData['volumeSize'] ? $this->params['configoptions']['volumeSize'] : $previousData['volumeSize'] ,
            'volumeType' => $this->params['configoptions']['volumeType'] != $previousData['volumeType'] ? $this->params['configoptions']['volumeType'] : $previousData['volumeType']
        ];

        return $params;
    }

    public function modifyChangePackageParams($jobData, $instanceDetails )
    {
        $newParams = $this->setChangePackageParams($instanceDetails, $jobData);

        $job = Job::factory();

        $job->modifyChangePackage($jobData['jobid'], serialize($newParams));

    }

    public function updateUserData($whmcsParams, $instanceDetails)
    {
        $this->newUserData = $this->productSettings['userData'];
    }

    public function updateInstanceType($whmcsParams, $instanceDetails)
    {
        $configType = $this->isParamValid($whmcsParams['configoptions']['instanceType']) ?
            $whmcsParams['configoptions']['instanceType'] : $this->productSettings['instanceType'];

        if ($instanceDetails['InstanceType'] === $configType)
        {
            return;
        }

        $this->newInstanceType = $configType;
    }

    public function updateSecurityGroups($whmcsParams, $instanceDetails)
    {
        $currentGroups = [];
        foreach ($instanceDetails['SecurityGroups'] as $currentGroup)
        {
            $currentGroups[] = $currentGroup['GroupId'];
        }

        if (count(array_diff($currentGroups, $this->productSettings['securityGroups'])) === 0
            && count(array_diff($this->productSettings['securityGroups'], $currentGroups)) === 0)
        {
            return;
        }

        $this->apiConnection->modifyInstanceAttribute([
            'InstanceId' => $this->params['customfields']['InstanceId'],
            'Groups' => $this->productSettings['securityGroups']
        ]);
    }

    protected function prepareRequestParams()
    {
        $params = [
            'InstanceIds' => $this->getInstanceIds()
        ];

        return $params;
    }
}
