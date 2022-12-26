<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\App\UI\Installations\Sidebars\Actions;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\Helper;

use function ModulesGarden\WordpressManager\Core\Helper\sl;

/* * gb
 * Description of InstallationPage
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */

class UserPage extends DataTable implements ClientArea
{
    protected $id    = 'mg-users';
    protected $name  = 'mg-users-name';
    protected $title = 'mg-users-title';

    use BaseClientController;

    protected function loadHtml()
    {
        $this->addColumn(new Column('id'));
        $this->addColumn((new Column('user_login'))->setSearchable(true, Column::TYPE_STRING)
                ->setOrderable(DataProvider::SORT_ASC)
        )
        ->addColumn((new Column('display_name'))->setSearchable(true)->setOrderable())
        ->addColumn((new Column('user_email'))->setSearchable(true)->setOrderable())
        ->addColumn((new Column('roles'))->setSearchable(true)->setOrderable())
        ->addColumn((new Column('user_registered'))->setSearchable(true)->setOrderable());
    }

    public function initContent()
    {
        sl('sidebar')->add( new Actions('actions')); 
        $this->addButton(new Buttons\UserCreateButton);
        $this->addActionButton(new Buttons\UserUpdateButton('userUpdateButton'));
        $this->addActionButton(new Buttons\UserResetPasswordButton('userResetPasswordButton'));
        $this->addActionButton(new Buttons\UserDeleteButton('userDeleteButton'));
    }

    protected function loadData()
    {
        $this->setInstallation(Installation::find($this->request->get('wpid')));
        $username=Helper\loggedinUsername();
        $data = (($this->subModule()->setUsername($username)->getWpCli($this->getInstallation()))->user())->getList();
        $data = array_map(function($row){
            $row['id'] = $row['ID'];
            return $row;
        }, $data);

        $dataProv = new providers\Providers\ArrayDataProvider();
        $dataProv->setDefaultSorting("user_login", 'asc');
        $dataProv->setData($data);
        $this->setDataProvider($dataProv);
    }
}
