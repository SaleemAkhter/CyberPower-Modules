<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Product\Pages;

use ModulesGarden\Servers\HetznerVps\App\UI\Product\Elements\PasswordElement;
use ModulesGarden\Servers\HetznerVps\App\UI\Product\Helpers\ServerManager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Others\Label;

/**
 * Description of Product
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ServerInformation extends DataTable implements ClientArea, AdminArea
{
    use RequestObjectHandler;

    protected $id = 'serverinformationTable';
    protected $name = 'serverinformationTable';
    protected $title = 'serverinformationTable';
    protected $searchable = false;
    protected $tableLength = "100";

    protected function loadHtml()
    {
        $this->addColumn((new Column('name')))->addColumn((new Column('value')));
    }

    public function replaceFieldValue($key, $row)
    {
        if ($row['noLangName'] == "status") {
            $label = (new Label('status'))
                ->addClass('lu-label--status')
                ->setTitle($row[$key])
                ->setBackgroundColor('')
                ->addHtmlAttribute('data-function', 'checkServerStatus')
                ->setColor('');

            switch (strtolower($row[$key])) {
                case 'running':
                    return $label->addClass('lu-label--success')->getHtml();
                case 'off':
                    return $label->addClass('lu-label--danger')->getHtml();
                default:
                    return $label->addClass('lu-label--warning')->getHtml();
            }
        }

        if ($row['noLangName'] == "password") {
            return (new PasswordElement())->setValue($row[$key])->getHtml();
        }

        return $row[$key];
    }

    protected function loadData()
    {
        $dataProvider = new ArrayDataProvider();
        $data = new ServerManager($this->getWhmcsParams());
        $data = $data->getInformation();
        foreach ($data as $key => &$value) {
            if ($value['noLangName'] == "password" && empty($value['value'])) unset($data[$key]);
        }
        $dataProvider->setData($data);
        $this->setDataProvider($dataProvider);
    }

    public function initContent()
    {
        $this->loadRequestObj();
        if ($this->request->query->get('mgFormAction') == "checkServerStatus") {
            $data = new ServerManager($this->getWhmcsParams());
            switch (strtolower($data->getServerStatus())) {
                case 'running':
                    $data = ['status' => 'Running', 'labelClass' => 'lu-label--status lu-label--success'];
                    break;
                case 'off':
                    $data = ['status' => 'Off', 'labelClass' => 'lu-label--status lu-label--danger'];
                    break;
                default:
                    $data = ['status' => $data->getServerStatus(), 'labelClass' => 'lu-label--status lu-label--warning'];
                    break;
            }

            $this->cleanOutputBuffer();
            echo json_encode($data);
            die();
        }
        $this->loadData();
    }

    protected function cleanOutputBuffer()
    {
        $outputBuffering = ob_get_contents();
        if ($outputBuffering !== FALSE) {
            if (!empty($outputBuffering)) {
                ob_clean();
            } else {
                ob_start();
            }
        }
        return $this;
    }

    public function getTableLength()
    {
        return $this->tableLength;
    }

    public function prepareAjaxData()
    {
    }
}
