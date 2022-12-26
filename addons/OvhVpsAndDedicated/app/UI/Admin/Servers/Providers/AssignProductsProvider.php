<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Providers;

use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Helpers\Machine;
use ModulesGarden\OvhVpsAndDedicated\App\Models\MachineReuseProducts;
use ModulesGarden\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;
use ModulesGarden\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Vps as VpsManager;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\MachineReuseProducts as MachineReuseProductsManager;

/**
 * Class AssignClientProvider
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AssignProductsProvider extends BaseModelDataProvider implements AdminArea
{
    use Lang;
    use RequestObjectHandler;

    const ON = 'on';

    public function __construct()
    {
        parent::__construct(null);
    }

    public function read()
    {
        $this->data['id'] = $this->getActionElementIdValue();
        $this->setValuesToProducts();
    }


    public function update()
    {
        try
        {
            $this->loadLang();
            $vpsName = $this->formData['id'];
            unset($this->formData['id']);
            MachineReuseProductsManager::removeForVps($vpsName);


            foreach ($this->formData as $id => $value)
            {
                if ($value == self::ON)
                {
                    $vpsReuseProduct            = new MachineReuseProducts();
                    $vpsReuseProduct->name      = $vpsName;
                    $vpsReuseProduct->productId = $id;
                    $vpsReuseProduct->save();
                }
            }

            return (new HtmlDataJsonResponse())->setMessage(
                $this->lang->translate('assignProductsProvider', 'update', 'success'));
        }
        catch (\Exception $exception)
        {
            return (new HtmlDataJsonResponse())->setMessage($exception->getMessage())->setStatusError();
        }


    }

    public function setValuesToProducts()
    {
        $vpses = MachineReuseProductsManager::getAllByName($this->getActionElementIdValue());

        foreach ($vpses as $vps)
        {
            $this->data[$vps->productId] = 'on';
        }
    }
}