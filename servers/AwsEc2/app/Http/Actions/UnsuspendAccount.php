<?php

namespace ModulesGarden\Servers\AwsEc2\App\Http\Actions;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;

/**
 * Class UnsuspendAccount
 *
 * @author <slawomir@modulesgarden.com>
 */
class UnsuspendAccount extends TerminateAccount
{
    public function execute($params = null)
    {
        try
        {
            $this->loadProductConfig($params);

            $apiConnection = new ClientWrapper($this->pid, $this->sid);

            $params = $this->prepareRequestParams();

            $resault = $apiConnection->startInstances($params);

            $stopedInstances = $resault->get('StartingInstances');

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

    protected function prepareRequestParams()
    {
        $params = [
            'InstanceIds' => $this->getInstanceIds()
        ];

        return $params;
    }
}
