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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Modals;

use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalTabsEdit;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\App\UI\Installations\Forms\ImportForm;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;

/**
 * Description of ImportModal
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class ImportModal extends ModalTabsEdit implements ClientArea
{

    public function initContent()
    {
        $this->initIds('importModal');
        $this->addForm((new ImportForm())->setModal($this));
        
    }

    /**
     * Remove action button using index
     * @param $index
     * @return bool
     */
    public function removeActionButtonByIndex($index)
    {
        $this->getActionButtons();
        if(array_key_exists($index, $this->actionButtons))
        {
            unset($this->actionButtons[$index]);

            return true;
        }

        return false;
    }

}
