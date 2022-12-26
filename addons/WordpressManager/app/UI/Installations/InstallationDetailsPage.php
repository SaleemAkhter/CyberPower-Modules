<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations;

use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Helper\CheckWpVersion;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\ModuleSettings;
use ModulesGarden\WordpressManager\App\Models\ProductSetting;
use ModulesGarden\WordpressManager\App\Models\WebsiteDetails;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\InstanceImageButton;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\PushToLiveButton;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\SslButton;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\StagingButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\ChangeDomainButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\CloneButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\InstallationUpdateButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\UpgradeButton;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonBase;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use function ModulesGarden\WordpressManager\Core\Helper\sl;

class InstallationDetailsPage extends BaseContainer implements ClientArea, AjaxElementInterface
{
    use RequestObjectHandler;
    use main\Core\UI\Traits\Buttons;
    use main\App\Http\Client\BaseClientController;

    protected $id = 'mg-wp-installation-details';
    protected $name = 'mg-wp-installation-details-name';
    protected $title = 'mg-wp-installation-details-title';

    protected $vueComponent = true;
    protected $defaultVueComponentName = 'mg-wp-installation-details';

    private $settings;

    public function initContent()
    {
        $this->returnAjaxData();
    }

    public function returnAjaxData()
    {
        $this->loadRequestObj();
        $wpLastVersion = ((new ModuleSettings())->getSettings())['wordpressVersion'];
        $request       = sl('request');

        $uid = $this->request->getSession('uid');

        $i     = (new Installation)->getTable();
        $p     = (new Product())->getTable();
        $h     = (new Hosting())->getTable();
        $wd    = (new WebsiteDetails)->getTable();
        $ps    = (new ProductSetting())->getTable();
        $query = (new Installation)
            ->query()
            ->getQuery()
            ->rightJoin($h, "{$i}.hosting_id", '=', "{$h}.id")
            ->rightJoin($p, "{$h}.packageid", '=', "{$p}.id")
            ->leftJoin($wd, "{$i}.id", '=', "{$wd}.wpid")
            ->leftJoin($ps, "{$h}.packageid", '=', "{$ps}.product_id")
            ->select(
                "{$i}.id",
                "{$i}.domain",
                "{$i}.hosting_id",
                "{$i}.url",
                "{$i}.path",
                "{$i}.version",
                "{$i}.staging",
                "{$i}.created_at",
                "{$i}.username",
                "{$i}.auto",
                "{$p}.name",
                "{$h}.packageid",
                "{$wd}.screenshot",
                "{$ps}.settings"
            )
            ->where("{$i}.user_id", $request->getSession('uid'))
            ->where("{$h}.domainstatus", "Active")
            ->orderBy("$i.domain");

        if ($this->request->get('hostingId'))
        {
            $data = [
                'installations' => $query->where("{$i}.user_id", $uid)
                    ->where("{$i}.hosting_id", $this->request->get('hostingId'))->get(),
            ];
        }
        else
        {
            $data = [
                'installations' => $query->where("{$i}.user_id", $uid)->get(),
            ];
        }

        foreach ($data['installations'] as $installation)
        {
            $installation->staging = $installation->staging ? 1 : 0;
            $installation->isOld   = version_compare($wpLastVersion, $installation->version);
            $decoded               = json_decode($installation->settings);
            foreach ($decoded as $fieldName => $value)
            {
                if (stripos($fieldName, 'actions-') !== 0)
                {
                    continue;
                }

                $jsField                = str_replace('-', '', $fieldName);
                $installation->$jsField = $value;
            }
        }

        //website details
        $rd      = new ButtonRedirect;
        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
            'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=details'
            : BuildUrl::getUrl('home', 'detail');
        $rd->setRawUrl($baseUrl);
        $rd->setIcon('lu-zmdi lu-zmdi-edit');
        $this->addButton(($rd)->setMainContainer($this->mainContainer));


        //ControlPanel
        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
            'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=controlPanel'
            : BuildUrl::getUrl('home', 'controlPanel');

        $cp = new ButtonRedirect('controlPanel');
        $cp->setIcon('lu-zmdi lu-zmdi-shield-security');
        $cp->setRawUrl($baseUrl);
        $cp->setShowByColumnValue('actionscontrolpanel', '1');

        $this->addButton(($cp)->setMainContainer($this->mainContainer));

        $this->addButton((new main\App\UI\Installations\Buttons\CacheButton('cache'))
            ->setMainContainer($this->mainContainer));

        $this->addButton((new CloneButton('clone'))->setMainContainer($this->mainContainer));

        $this->addButton((new UpgradeButton())->setMainContainer($this->mainContainer));

        $this->addButton((new ChangeDomainButton(''))->setMainContainer($this->mainContainer));

        $this->addButton((new InstallationUpdateButton(''))->setMainContainer($this->mainContainer));

        $this->addButton((new StagingButton())->setMainContainer($this->mainContainer));

        $pushToLive = $this->addButton((new PushToLiveButton(''))->setMainContainer($this->mainContainer));

        $this->addButton((new SslButton())->setMainContainer($this->mainContainer));

        $this->addButton((new main\App\UI\Installations\Buttons\MaintenanceModeButton('maintenanceMode'))->setMainContainer($this->mainContainer));

        $this->addButton((new InstanceImageButton())->setMainContainer($this->mainContainer));

        $this->addButton((new Buttons\InstallationDeleteButton())
            ->setMainContainer($this->mainContainer));

        return (new RawDataJsonResponse(['data' => $data]));
    }


    public function replaceFieldVersion($key, $row)
    {
        $result = (new CheckWpVersion())->checkIfNewer($row->version);

        if ($result)
        {
            $rd = new ButtonBase;
            $rd->setName('updateWpAlert');
            $rd->setTitle('updateWpAlert');
            $rd->replaceClasses(['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--danger redAlert vertical-align-middle']);
            $rd->setIcon('lu-btn__icon lu-zmdi lu-zmdi-alert-circle');

            return $row->version . $rd;
        }

        return $row->version;
    }
}
