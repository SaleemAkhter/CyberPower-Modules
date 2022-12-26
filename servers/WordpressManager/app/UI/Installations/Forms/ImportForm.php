<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 5, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use ModulesGarden\WordpressManager\App\Helper\Decorator;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\ImportModal;
use ModulesGarden\WordpressManager\Core\UI\Helpers\AlertTypesConstants;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseTabsForm;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Text;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Select;
use \ModulesGarden\WordpressManager\App\UI\Validators\DomainValidator;
use \ModulesGarden\WordpressManager\App\UI\Installations\Providers\ImportProvider;
use \ModulesGarden\WordpressManager\App\UI\Validators\NumberValidator;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\TabSection;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\RawSection;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\WordpressManager\App\Repositories\HostingRepository;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\HalfPageSection;

/**
 * Description of InstallationStagingForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class ImportForm extends BaseTabsForm implements ClientArea
{
    protected $formData;

    use BaseClientController;
    use RequestObjectHandler;
    /**
     * @var ImportModal
     */
    private $modal;

    protected function getDefaultActions()
    {
        return ['create'];
    }

    /**
     * @param mixed $modal
     */
    public function setModal(ImportModal $modal)
    {
        $this->modal = $modal;
        return $this;
    }

    public function initContent()
    {
        $this->initIds('importForm');
        $this->setInternalAlertMessage('allertMessage');
        if($this->isLimitReached())
        {
            $this->modal->removeActionButtonByIndex('baseAcceptButton');
            return;
        }
        $this->loadRequestObj();
        $this->formData = $this->request->get('formData');
        $this->setFormType('create');
        $this->setProvider(new ImportProvider);
        $this->loadRequestObj();
        $this->tabSource();
        $this->tabDestination();
        $this->loadDataToForm();
    }

    private function isLimitReached()
    {
        $repository = new HostingRepository;
        $hostings   = $repository->findEnabledWithProduct($this->request->getSession('uid'), $this->request->get('hostingId', null));
        if(empty($hostings))
        {
            $this->setConfirmMessage('allHostingsLimited', ['title' => null]);
            return true;
        }

        return false;
    }

    private function tabSource()
    {
        /**
         * Main section
         */
        $mainSection = new RawSection('mainSection');
        $mainSection->setMainContainer($this->mainContainer);
        //domain
        $mainSection->addField((new Text('domain'))->addValidator(new DomainValidator)->setPlaceholder('source.example.com')->setDescription('description'));
        //server_host
        $mainSection->addField((new Text('server_host'))->notEmpty()->setPlaceholder('ftp.source.example.com')->setDescription('description'));
        /**
         * Left side
         */
        $leftSide = new HalfPageSection('leftSide');
        $leftSide->setMainContainer($this->mainContainer);
        //protocol
        $protocol = new Select('protocol');
        $protocol->setAvailableValues(['ftp' => "FTP", "ftps" => "FTPS", "sftp" => "SFTP"]);
        $protocol->setDescription('description');
        $leftSide->addField($protocol);

        //ftp_user
        $leftSide->addField((new Text('ftp_user'))->notEmpty()->setPlaceholder('username')->setDescription('description'));

        /**
         * Right side
         */
        $rightSide = new HalfPageSection('rightSide');
        $rightSide->setMainContainer($this->mainContainer);

        //port
        $rightSide->addField((new Text('port'))->setDefaultValue(21)->addValidator(new NumberValidator('0', '65535', true))->setPlaceholder('21')->setDescription('description'));

        //ftp_pass
        $rightSide->addField((new Text('ftp_pass'))->notEmpty()->setPlaceholder('password')->setDescription('description'));



        /**
         * Bottom section
         */
        $bottomSection = new RawSection('bottomSection');
        $bottomSection->setMainContainer($this->mainContainer);
        //ftp_path
        $bottomSection->addField((new Text('ftp_path'))->notEmpty()->setPlaceholder('/public_html')->setDescription('description'));
        //installed_path
        $bottomSection->addField((new Text('installed_path'))->setPlaceholder('wp')->setDescription('description'));


        //Add sections
        $content = new RawSection();
        $content->setMainContainer($this->mainContainer);
        $content->addSection($mainSection);
        $content->addSection($leftSide);
        $content->addSection($rightSide);
        $content->addSection($bottomSection);

        $section = new TabSection();
        $section->setMainContainer($this->mainContainer);
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setName(sl('lang')->T(__FUNCTION__));
        $section->addSection($content);

        $this->addSection($section);
    }

    private function tabDestination()
    {
        $repository = new HostingRepository;
        $hostings   = $repository->findEnabledWithProduct($this->request->getSession('uid'), $this->request->get('hostingId', null));
        $section = new TabSection();
        $section->setMainContainer($this->mainContainer);
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setName(sl('lang')->T(__FUNCTION__));
        $content = new RawSection();
        $content->setMainContainer($this->mainContainer);
        if (!$this->formData['hostingId']) {
            $this->formData['hostingId'] = key($hostings);
        }
        $this->reset()
            ->setHostingId($this->formData['hostingId'])
            ->setUserId($this->request->getSession('uid'));
        $isProductResseller = $this->getHosting()->product->isTypeReseller();

        $field = new Select('hostingId');
        $field->initIds('hostingId');
        $field->setDescription('description');
        $field->setAvailableValues((array)$hostings);
        $field->addHtmlAttribute('bi-event-change', "reloadVueModal");
        $content->addField($field);

        //username
        if ($isProductResseller) {
            $field = new Select('username');
            $field->notEmpty();
            $options = [];
            foreach ($this->subModule()->reseller()->getAccounts() as $account) {
                $options[$account['username']] = $account['username'];
            }
            if (empty($options))
            {
                $this->addInternalAlert('no_users_under_reseller_account',  AlertTypesConstants::DANGER);
                $this->modal->removeActionButtonByIndex('baseAcceptButton');
            }
            $field->setAvailableValues($options);
            $content->addField($field);
        }
        //softproto
        $field = new Select('softproto');
        $field->setAvailableValues((new Decorator())->getProtocols());
        $content->addField($field);
        //soft 
        $content->addField((new Hidden('soft'))->setDefaultValue(26));
        //softdomain
        $content->addField((new Text('softdomain'))->notEmpty()->setPlaceholder('destination.example.com')->setDescription('description'));
        //dest_directory
        $content->addField((new Text('dest_directory'))->setPlaceholder('wp')->setDescription('description'));
        //softdb 
        $content->addField((new Text('softdb'))->notEmpty()->setPlaceholder('name')->setDescription('description'));
        $section->addSection($content);
        $this->addSection($section);
    }

}