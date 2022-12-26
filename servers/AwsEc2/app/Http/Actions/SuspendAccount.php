<?php

namespace ModulesGarden\Servers\AwsEc2\App\Http\Actions;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;

/**
 * Class SuspendAccount
 *
 * @author <slawomir@modulesgarden.com>
 */
class SuspendAccount extends TerminateAccount
{

    public function execute($params = null)
    {
        try
        {
            $this->loadProductConfig($params);

            $apiConnection = new ClientWrapper($this->pid, $this->sid);

            $params = $this->prepareRequestParams();

            $resault = $apiConnection->stopInstances($params);

            $stopedInstances = $resault->get('StoppingInstances');

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
            'InstanceIds' => $this->getInstanceIds(),
            'Hibernate' => ($this->productSettings['hibernationOptions'] === 'on' ? true : false)
        ];

        return $params;
    }
}
