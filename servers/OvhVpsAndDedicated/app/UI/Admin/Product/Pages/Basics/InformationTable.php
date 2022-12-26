<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages\Basics;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataTable;

/**
 * Class InformationTable
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class InformationTable extends DataTable implements ClientArea, AdminArea
{
    protected $id = 'informationTable';
    protected $name = 'informationTable';
    protected $title = 'informationTable';

    protected $searchable = false;
    protected $isViewTopBody = false;
    protected $isViewFooter = false;


    public function loadHtml()
    {
        $this->setTableLengthList([100]);
        $this->setTableLength(100);

        $this->addColumn((new Column('name')))->addColumn((new Column('value')));
    }
}
