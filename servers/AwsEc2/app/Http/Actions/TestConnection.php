<?php

namespace ModulesGarden\Servers\AwsEc2\App\Http\Actions;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Instances\AddonController;

/**
 * Class MetaData
 *
 * @author <slawomir@modulesgarden.com>
 */
class TestConnection extends AddonController
{
    public function execute($params = null)
    {
        $status = true;
        $errMessage = null;

        try
        {
            $list = ClientWrapper::testConnection($params['serverusername'], $params['serverpassword']);
            if (!$list || !is_array($list) || count($list) === 0)
            {
                $errMessage = 'Connection Failed';
                $status = false;
            }
        }
        catch (\Aws\Exception\AwsException $exc)
        {
            $errMessage = $exc->getAwsErrorMessage();
            $status = false;
        }
        catch (\Exception $exc)
        {
            $errMessage = $exc->getMessage();
            $status = false;
        }
        finally
        {
            return [
                'success' => $status,
                'error' => $errMessage,
            ];
        }
    }
}
