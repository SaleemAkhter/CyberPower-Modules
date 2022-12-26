<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Fields;

use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Client;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\AjaxFields\SelectRemoteSearch;

/**
 * Class ClientRemoteSearch
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ClientRemoteSearch extends SelectRemoteSearch implements AdminArea
{
    protected $id   = 'clientRemoteSearch';
    protected $name = 'clientRemoteSearch';

    public function prepareAjaxData()
    {
        $clients = Client::searchClientQuery($this->getRequestValue('searchQuery'));
        foreach ($clients->get()->toArray() as $client){
            $client['value'] =   html_entity_decode($client['value'], ENT_QUOTES);
            $this->availableValues[]= $client;
        }
    }
}