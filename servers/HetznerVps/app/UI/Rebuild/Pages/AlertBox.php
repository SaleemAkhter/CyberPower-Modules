<?php
/**
 * Description of AlertBox.php
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * Date: 27.05.2019
 * Time: 13:24
 */

namespace ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Pages;


use ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;

class AlertBox extends BaseContainer implements ClientArea
{
    protected $id    = 'alertBox';
    protected $name  = 'alertBox';
    protected $title = 'alertBox';

    public function initContent()
    {

    }
}