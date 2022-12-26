<?php

namespace ModulesGarden\Servers\VultrVps\Core\UI\Traits;

use ModulesGarden\Servers\VultrVps\Core\Http\Request;

/**
 * Search field related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait Searchable
{
    protected $searchable = false;

    public function isSearchable()
    {
        return $this->searchable;
    }

    protected function getSearchKey()
    {
        $request = Request::build();
        if ($request->get('sSearch', false))
        {
            return $request->get('sSearch');
        }

        return false;
    }

    public function insertSearchForm()
    {
        if ($this->searchable)
        {
            $searchForm = new \ModulesGarden\Servers\VultrVps\Core\UI\Searchable();

            return $searchForm->getHtml();
        }

        return '';
    }
    
    public function insertHiddenSearchForm()
    {
        if ($this->searchable)
        {
            $searchForm = new \ModulesGarden\Servers\VultrVps\Core\UI\HiddenSearchable();

            return $searchForm->getHtml();
        }

        return '';
    }    
}
