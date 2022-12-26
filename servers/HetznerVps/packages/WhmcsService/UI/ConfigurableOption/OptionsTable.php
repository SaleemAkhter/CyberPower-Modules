<?php

namespace ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\UI\ConfigurableOption;

use ModulesGarden\Servers\HetznerVps\Core\App\Packages\PackageManager;
use ModulesGarden\Servers\HetznerVps\Core\Traits\Lang;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\Config\Enum;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\Config\PackageConfiguration;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\Product;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\UI\ConfigurableOption\Buttons\AddOption;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\UI\ConfigurableOption\Buttons\OptionDetails;

class OptionsTable extends DataTable
{
    use Lang;

    protected $id = 'configOptionsTable';
    protected $name = 'configOptionsTable';
    protected $title = 'configOptionsTableTitle';

    protected $searchable = false;
    protected $isViewTopBody = false;
    protected $isViewFooter = false;

    protected $actionIdColumnName = 'optionname';

    public function initContent()
    {
        $this->addColumn(new Column(Enum::OPTION_NAME));
        $this->addColumn(new Column('status'));

        $this->addActionButton(AddOption::class);
        $this->addActionButton(OptionDetails::class);
    }

    public function loadData()
    {
        $packageManager = new PackageManager();
        $config = $packageManager->getPackageConfiguration(PackageConfiguration::getPackageName());

        $optionsList = $config->{Enum::CONFIGURABLE_OPTIONS};

        $product = new Product($this->getRequestValue('id'));
        foreach ($optionsList as $key => $option)
        {
            $exists = $product->doesConfigurableOptionExist($option['optionname']);
            $optionsList[$key]['exists'] = $exists;
            $optionsList[$key]['gid'] = $product->getOptionGroupId($option['optionname']);
            $optionsList[$key]['status'] = $exists ? '<span class="lu-label lu-label--success lu-label--status">' . $this->translate('active') . '</span>'
                : '<span class="lu-label lu-label--default lu-label--status">' . $this->translate('inactive') . '</span>';
        }

        $dataProvieder = new ArrayDataProvider();
        $dataProvieder->setData($optionsList);
        $this->setDataProvider($dataProvieder);
    }

    protected function translate($phrase)
    {
        $this->loadLang();

        return $this->lang->absoluteTranslate($this->id, 'status', $phrase);
    }
}
