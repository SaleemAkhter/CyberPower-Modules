<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 11, 2017)
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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\BackupProvider;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;
/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class BackupCreateForm extends BaseForm implements ClientArea
{

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new BackupProvider);
        $this->initFields();
    }

    protected function initFields()
    {
        //Backup Directory 
        $this->addField(new Fields\Switcher('backupDirectory'));
        //Backup datadir
        $this->addField(new Fields\Switcher('backupDataDir'));
        //Backup Database 
        $this->addField(new Fields\Switcher('backupDatabase'));
        //Backup Location 
        $field = new Fields\Select('backupLocation');
        $field->setAvailableValues(['0' => sl('lang')->absoluteT('Default'), '1' => sl('lang')->absoluteT('Local Folder')]);
        $this->addField($field);
        //backup_note
        $field = new Fields\Textarea('backup_note');
        $field->addHtmlAttribute('maxlength',255);
        $this->addField($field);
    }
}
