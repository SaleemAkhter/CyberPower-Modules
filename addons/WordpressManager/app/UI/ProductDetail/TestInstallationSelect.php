<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jul 31, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\Models\Installation as InstallationModel;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;
/**
 * Description of TestInstallationSelect
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class TestInstallationSelect extends \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\AjaxFields\SelectRemoteSearch implements AdminArea
{
    public function prepareAjaxData()
    {
        $searchString = $this->getRequestValue('searchQuery', null);
        $h       = (new Hosting)->getTable();
        $i       = (new InstallationModel)->getTable();
        $query   = (new InstallationModel)->query()->getQuery()
            ->select("{$i}.*")
            ->leftJoin($h, "{$h}.id", "=", "{$i}.hosting_id")
            ->where("{$h}.domainstatus", "Active")
            ->where("{$i}.path", 'LIKE', '%' . $searchString . '%');

        $options = [];
        foreach ($query->get() as $path)
        {
            $options[] = [
                'key'   => $path->id,
                'value' => sprintf("#%s %s", $path->id, $path->path)
            ];
        }
        $this->setAvailableValues($options);
        $this->callBackFunction ='wpProductGeneralAjaxDone';
    }

}
