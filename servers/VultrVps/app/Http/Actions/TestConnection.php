<?php

namespace ModulesGarden\Servers\VultrVps\App\Http\Actions;

use ModulesGarden\Servers\VultrVps\App\Api\ApiClientFactory;
use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\AddonController;

/**
 * Class MetaData
 *
 * @author <slawomir@modulesgarden.com>
 */
class TestConnection extends AddonController
{
    public function execute($params = null)
    {
        try
        {
            $apiClient = ApiClientFactory::formParams($params);
            $apiClient->account();
            return ['success' => true];
        }
        catch (\Exception $ex)
        {
            return ['error' => $ex->getMessage()];
        }
    }
}
