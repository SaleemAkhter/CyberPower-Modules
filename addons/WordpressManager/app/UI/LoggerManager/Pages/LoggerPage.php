<?php

namespace ModulesGarden\WordpressManager\App\UI\LoggerManager\Pages;

use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Others;
use \ModulesGarden\WordpressManager\Core\Logger\Entity;
use \ModulesGarden\WordpressManager\App\UI\LoggerManager\Buttons\DeleteLoggerModalButton;
use \ModulesGarden\WordpressManager\App\UI\LoggerManager\Buttons\MassDeleteLoggerButton;

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
        return html_entity_decode($row[$key]);
    }

    public function replaceFieldType($key, $row)
    {
        if ($row[$key] === Entity::TYPE_DEBUG)
        {
            $color           = '7b007b';
            $backgroundColor = 'e9ebf0';
        }
        elseif ($row[$key] === Entity::TYPE_ERROR)
        {
            $color           = 'fcffff';
            $backgroundColor = 'ed4040';
        }
        elseif ($row[$key] === Entity::TYPE_INFO)
        {
            $color           = 'e9fff7';
            $backgroundColor = '737980';
        }
        elseif ($row[$key] === Entity::TYPE_SUCCESS)
        {
            $color           = 'e5fff4';
            $backgroundColor = '5bc758';
        }
        elseif ($row[$key] === Entity::TYPE_CRITICAL)
        {
            $color           = 'fcffff';
            $backgroundColor = 'ed4040';
        }
        $label = new Others\Label();
        $label->initIds('label')
                ->setMessage($row['typeLabel'])
                ->setTitle($row['typeLabel'])
                ->setColor($color)
                ->setBackgroundColor($backgroundColor);
        return $label->getHtml();
    }

    public function initContent()
    {
        $this->addActionButton((new DeleteLoggerModalButton()));
        $this->addMassActionButton((new MassDeleteLoggerButton()));
    }

    protected function loadData()
    {
        $collection = Helper\sl('entityLogger')->all();
        $data       = [];
        foreach ($collection as $model)
        {
            $data[] = $model->toArray();
        }

        $dataProv = new ArrayDataProvider();
        $dataProv->setDefaultSorting('id', 'desc')->setData($data);

        $this->setDataProvider($dataProv);
    }
}
