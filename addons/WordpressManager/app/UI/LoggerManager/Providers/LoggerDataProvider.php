<?php

namespace ModulesGarden\WordpressManager\App\UI\LoggerManager\Providers;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;

/**
 * CategoryDataProvider
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class LoggerDataProvider extends BaseModelDataProvider implements AdminArea
{

    public function __construct()
    {
        parent::__construct('\ModulesGarden\WordpressManager\Core\Models\Logger\Model');
    }

    public function delete()
    {
        if ($this->formData['id'])
        {
            parent::delete();

            return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('loggerDeletedSuccesfully');
        }
        if ($this->request->get('massActions', []))
        {
            foreach ($this->request->get('massActions', []) as $tldId)
            {
                $this->model->where('id', $tldId)->delete();
            }

            return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('loggersDeletedSuccesfully');
        }
    }

    public function deleteall()
    {
        $this->model->truncate();

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('loggersDeletedSuccesfully');
    }
}
