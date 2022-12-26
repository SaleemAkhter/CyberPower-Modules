<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Client;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http\AbstractController;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper;
use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Graphs\Pages\GraphPage;

/**
 * Class Graphs
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Graphs extends ClientBaseController
{

    public function index()
    {
        try
        {
            if (!$this->getPageController()->dedicatedClientAreaGraphs()) {

                return $this->redirectToMainServicePage();
            }

            return Helper\view()->addElement(GraphPage::class);
        }
        catch (Exception $ex)
        {
            \logModuleCall('OvhVpsAndDedicated', __CLASS__, var_export($ex, true), []);
        }
    }

}