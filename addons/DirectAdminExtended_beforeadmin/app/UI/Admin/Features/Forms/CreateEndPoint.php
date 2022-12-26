<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Providers;

class CreateEndPoint extends BaseForm implements AdminArea
{
    protected $id    = 'createEndPoint';
    protected $name  = 'createEndPoint';
    protected $title = 'createEndPoint';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\FtpEndPoint())
                ->loadFields()
                ->loadDataToForm();
    }

    protected function loadFields()
    {
        $server         = (new Fields\Select('server_id'))->notEmpty();
        $name           = (new Fields\Text('name'))->notEmpty();
        $host           = (new Fields\Text('host'))->notEmpty();
        $port           = (new Fields\Text('port'))->notEmpty();
        $user           = (new Fields\Text('user'))->notEmpty();
        $password       = (new Fields\Password('password'))->notEmpty();
        $path           = (new Fields\Text('path'))->notEmpty();
        $adminAccess    = new Fields\Switcher('admin_access');
        $enableRestore  = new Fields\Switcher('enable_restore');

        $this->addField($server)
            ->addField($name)
            ->addField($host)
            ->addField($port)
            ->addField($user)
            ->addField($password)
            ->addField($path)
            ->addField($adminAccess)
            ->addField($enableRestore);

        return $this;
    }
}
