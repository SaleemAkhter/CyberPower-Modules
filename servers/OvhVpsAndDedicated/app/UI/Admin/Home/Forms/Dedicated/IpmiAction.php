<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\Fields\RemoteWorkLoaderWidget;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers\Dedicated\Ipmi;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class IpmiAction extends BaseForm implements ClientArea, AdminArea
{
    use WhmcsParamsApp;

    protected $id    = 'ipmiActionForm';
    protected $name  = 'ipmiActionForm';
    protected $title = 'ipmiActionForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Ipmi());
        $this->initFields();
        $this->loadDataToForm();
    }

    public function initFields()
    {
        $this->addField((new Select('type'))->setDescription('typeDesc'));
        $this->addField((new Select('ttl'))->setDescription('ttlDesc'));
    }

    public function reloadFormStructure()
    {
        $this->removeField('type');
        $this->removeField('ttl');

        $type = $this->getRequestValue('formData')['type'];
        $ttl = $this->getRequestValue('formData')['ttl'];
        $ip = $_SERVER['HTTP_X_REAL_IP'] ?: $_SERVER['REMOTE_ADDR'];

        $repo         = new Dedicated\Repository($this->getWhmcsEssentialParams());
        $server = $repo->get();
        $taskId = null;
        try
        {

            $response = $server->features()->ipmi()->addAccess($type, $ttl, $ip);

            $taskId = $response['taskId'];
        }
        catch (Exception $exception)
        {
            //do nothing
        }



        $this->addField((new Hidden('type'))->setDefaultValue($type));
        $this->addField((new Hidden('ttl'))->setDefaultValue($ttl));
        $this->addField((new Hidden('task'))->setDefaultValue($taskId));

        $loader = new RemoteWorkLoaderWidget();
        $this->addField($loader);

        $this->loadDataToForm();
    }
}
