<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Pages;

use ModulesGarden\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;
use \ModulesGarden\OvhVpsAndDedicated\Core\Helper;
use ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\Hosting;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Others;
use \ModulesGarden\OvhVpsAndDedicated\Core\Logger\Entity;

use \ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Buttons\DeleteLoggerModalButton;
use \ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Buttons\MassDeleteLoggerButton;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Decorators;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use Symfony\Component\DependencyInjection\Tests\Compiler\E;

/**
 * Description of Filters
 *
 * @author inbs
 */
class LoggerPage extends DataTable implements AdminArea
{ 
    protected $id    = 'loggercont';
    protected $name  = 'loggercont';
    protected $title = null;
    
    protected $colorArray = [
        Entity::TYPE_DEBUG => [
            'color' => '7b007b',
            'backgroundColor' => 'e9ebf0'
        ],
        Entity::TYPE_ERROR => [
            'color' => 'fcffff',
            'backgroundColor' => 'ed4040'
        ],
        Entity::TYPE_INFO => [
            'color' => 'e9fff7',
            'backgroundColor' => '737980'
        ],
        Entity::TYPE_SUCCESS => [
            'color' => 'e5fff4',
            'backgroundColor' => '5bc758'
        ],
        Entity::TYPE_CRITICAL => [
            'color' => 'fcffff',
            'backgroundColor' => 'ed4040'
        ]
    ];
    
    protected function loadHtml()
    {
        $this
            ->addColumn((new Column('id'))
                    ->setOrderable(DataProvider::SORT_DESC)
                    ->setSearchable(true, Column::TYPE_INT))
            ->addColumn((new Column('message'))
                    ->setOrderable()
                    ->setSearchable(true))
            ->addColumn((new Column('type'))
                    ->setOrderable()
                    ->setSearchable(true))
            ->addColumn((new Column('date'))
                    ->setSearchable(true, Column::TYPE_DATE)
                    ->setOrderable());
    }

    public function replaceFieldMessage($key, $row)
    {
        switch ($row[$key])
        {
            //Account Actions
            case 'suspendAccount':
                return Decorators\Account\Suspend::decorateSuccess($row);
            case 'suspendAccountError':
                return Decorators\Account\Suspend::decorateError($row);

            case 'createError':
                return Decorators\Account\Create::decorateError($row);

            case 'terminateAccount':
                return Decorators\Account\Terminate::decorateSuccess($row);

            case 'terminateAccountError':
                return Decorators\Account\Terminate::decorateError($row);

            case 'unsuspendAccount':
                return Decorators\Account\Unsuspend::decorateSuccess($row);

            case 'unsuspendAccountError':
                return Decorators\Account\Unsuspend::decorateError($row);

            case 'changePackage':
                return Decorators\Account\ChangePackage::decorateSuccess($row);

            case 'changePackageError':
                return Decorators\Account\ChangePackage::decorateError($row);
            case 'vpsMachineAssignedToReuseWithTerminateAction':
                return Decorators\Vps\Vps::reassinged($row);


            //others
            case 'vpsUpgradeDowngrade':
                return Decorators\Vps\UpgradeDowngrade::decorate($row);
            case 'vpsUpgradeDowngradeOptionError':
                return Decorators\Vps\UpgradeDowngrade::decorateOptionAddingError($row);
            case 'vpsUpgradeDowngradeProductSuccess':
                return Decorators\Vps\UpgradeDowngrade::decorate($row);
            case 'unableToUpgradeDowngradeUsageOptionIsSameAsSelected':
                return Decorators\Vps\UpgradeDowngrade::decorateSameOptionAsUsage($row);
            case 'vpsReusedMachineSuccess':
                return Decorators\Vps\Reused::decorate($row);
            case 'vpsMachineReinstallSuccess':
                return Decorators\Vps\Reinstall::decorate($row);
            case 'vpsCreateMachineSuccess':
                return Decorators\Vps\Create::decorate($row);
            case 'dedicatedMachineCreatedByAutomateAssignedAndReinstalled':
                return Decorators\Dedicated\Create::decorate($row);
            case 'dedicatedMachineTerminateOnSuspendAction':
                return Decorators\Dedicated\Suspend::decorateTerminate($row);
            case 'dedicatedMachineWasBootedToRescueOnSuspendAction':
                return Decorators\Dedicated\Suspend::decorateBootedToRescue($row);
            case 'vpsEmailActionNotFound':
                return Decorators\Vps\EmailActionTemplate::decorateActionNotFound($row);

        }
        return html_entity_decode($row[$key]);
    }  
    
    public function replaceFieldType($key, $row)
    {
        return (new Others\Label())->initIds('label')
                ->setMessage($row['typeLabel'])
                ->setTitle($row['typeLabel'])
                ->setColor($this->colorArray[$row[$key]]['color'])
                ->setBackgroundColor($this->colorArray[$row[$key]]['backgroundColor'])
                ->getHtml(); 
    }

    public function initContent()
    {
        $this->addActionButton((new DeleteLoggerModalButton()));
        $this->addMassActionButton((new MassDeleteLoggerButton()));
    }

    protected function loadData()
    {
        $collection = Helper\sl('entityLogger')->all();
        $data = [];
        foreach ($collection as $model)
        {
            $data[] = $model->toArray();
        }

        $dataProv = new ArrayDataProvider();
        $dataProv->setDefaultSorting('id', 'desc')->setData($data);

        $this->setDataProvider($dataProv);
    }
}
