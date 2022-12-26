<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 19, 2017)
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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Buttons;

use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstallationUpgradeModal;

/**
 * Description of CloneButton
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class UpgradeButton extends ButtonMassAction implements ClientArea
{
    use \ModulesGarden\WordpressManager\Core\UI\Widget\Sidebar\SidebarTrait;
    protected $id="upgradeButton";
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-upload';

    protected $htmlAttributes = [
        'data-toggle' => 'lu-tooltip',
        'title'       => 'Update',
        'onclick'       => 'return false;',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new InstallationUpgradeModal());
        $this->setShowByColumnValue('actionsupdate', '1');
    }

    protected function initLoadModalAction($modal)
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\')';
        $this->setModal($modal);
    }
}
