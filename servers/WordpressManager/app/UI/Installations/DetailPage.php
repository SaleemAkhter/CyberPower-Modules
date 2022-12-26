<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 7, 2017)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\UI\Installations;

use \ModulesGarden\WordpressManager\App\Helper\WhmcsHelper;
use \ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonBase;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Others\PasswordToggleButton;
use \ModulesGarden\WordpressManager as main;
use \ModulesGarden\WordpressManager\App\Models\InstanceImage;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\RawDataJsonResponse;
use \ModulesGarden\WordpressManager\App\Helper\CheckWpVersion;
use \ModulesGarden\WordpressManager\App\Services\GooglePreviewAPI;
use ModulesGarden\WordpressManager\App\UI\Installations\Forms\InstallationUpdateForm;

use function ModulesGarden\WordpressManager\Core\Helper\sl;

/* * gb
 * Description of InstallationPage
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */

class DetailPage extends BaseContainer implements ClientArea, AjaxElementInterface
{
    use main\App\Http\Client\BaseClientController;
    use \ModulesGarden\WordpressManager\Core\UI\Traits\Forms;

    protected $id    = 'mg-details';
    protected $name  = 'mg-details-name';
    protected $title = 'mg-details-title';
    protected $class=["mt-20"];
    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-wp-details';    
    
    public function initContent()
    {
        $this->initSidebar();
    }
    
    private function initSidebar(){
        //main sidebar 
        $sidebar = sl('sidebar');
        $sidebar->getSidebar('management')->setOrder(1);
        //actions
        $actions = new Sidebars\Actions('actions');      
        $actions->setOrder(2);
        $sidebar->add( $actions);
        $this->addForm(new InstallationUpdateForm);
    }
    
    public function returnAjaxData()
    {
        $data = [];
        $this->loadRequestObj();
        $this->reset();
        $this->setInstallationId($this->request->get('wpid'))
             ->setUserId($this->request->getSession('uid'));
        //Instalation Details
        if($this->getInstallation()->username){
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        $data['details'] =  $this->subModule()->installation($this->getInstallation())->read();
        $data['details']['userins']['site_name'] =  html_entity_decode($data['details']['userins']['site_name'],ENT_QUOTES );
        if(!$data['details']['userins']['site_name'] && !$data['details']['userins']['live_ins']['site_name']  ){
            $data['details']['userins']['site_name']="-";
        }
        try{
            $data['WP_DEBUG']  = $this->subModule()->getWpCli($this->getInstallation())->option()->get('WP_DEBUG');
        } catch (\Exception $ex) { // WP_DEBUG does not exist
        }
        $data['productName']                = $this->getInstallation()->hosting->product->name;
        $data['installation']               = $this->getInstallation()->toArray();

        if((new CheckWpVersion())->checkIfNewer($data['installation']['version']))
        {
            $rd = new ButtonBase;
            $rd->setName('updateWpAlert');
            $rd->setTitle('updateWpAlert');
            $rd->replaceClasses(['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--danger redAlert vertical-align-middle']);
            $rd->setIcon('lu-btn__icon lu-zmdi lu-zmdi-alert-circle');

            $data['newVersionAvailable'] = $data['installation']['version'].' '.$rd->getHtml();
        }

        $data['installation']['created_at'] = WhmcsHelper::fromMySQLDate($this->getInstallation()->created_at, true);
        $this->addButton((new PasswordToggleButton('passwordHidden'))->setPassword($data['details']['userins']['softdbpass']));
        $instanceImage = InstanceImage::ofUserId($this->request->getSession('uid'))->ofInstallationId($this->request->get('wpid'))->first();
        if(!is_null($instanceImage)){
            $data['instanceImage'] =$instanceImage->toArray();
        }
        $data['details']['passwordHidden'] = str_repeat('*', strlen($data['details']['userins']['softdbpass']));

        return (new RawDataJsonResponse(['data' => $data]));
    }
}
