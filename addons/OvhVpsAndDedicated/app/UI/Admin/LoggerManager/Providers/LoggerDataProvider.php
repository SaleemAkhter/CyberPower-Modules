<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Providers;

use ModulesGarden\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;
use ModulesGarden\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\ResponseTemplates;

/**
 * CategoryDataProvider
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class LoggerDataProvider extends BaseModelDataProvider implements AdminArea
{
    use Lang;
    
    public function __construct()
    {
        parent::__construct('\ModulesGarden\OvhVpsAndDedicated\Core\Models\Logger\Model');
        $this->loadLang();
    }

    public function delete()
    {
        try
        {
            if ($this->formData['id'])
            {
                parent::delete();

                return (new ResponseTemplates\HtmlDataJsonResponse())->setMessage($this->lang->translate('log', 'delete', 'success'));
            }

            if ($this->getRequestValue('massActions', []))
            {
                foreach ($this->getRequestValue('massActions', []) as $tldId)
                {
                    $this->model->where('id', $tldId)->delete();
                }

                return (new ResponseTemplates\HtmlDataJsonResponse())->setMessage($this->lang->translate('logs', 'delete', 'success'));
            }
        }
        catch (\Exception $exception)
        {
            return (new ResponseTemplates\HtmlDataJsonResponse())->setMessage($exception->getMessage());
        }

    }
    
    public function deleteall()
    {
        try
        {
            $this->model->truncate();

            return (new ResponseTemplates\HtmlDataJsonResponse())->setMessage($this->lang->translate('logs', 'delete', 'success'));

        }
        catch (\Exception $exception)
        {
            return (new ResponseTemplates\HtmlDataJsonResponse())->setMessage($exception->getMessage());
        }
    }
}
