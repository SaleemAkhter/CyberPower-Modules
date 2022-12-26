<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Configuration\Addon\Activate;

use \ModulesGarden\OvhVpsAndDedicated\App\Helpers\EmailTemplate;
/**
 * Runs after module activation actions
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class After extends \ModulesGarden\OvhVpsAndDedicated\Core\Configuration\Addon\Activate\After
{

    /**
     * @param array $params
     * @return array
     */
    public function execute(array $params = [])
    {
        $return = parent::execute($params);
        $this->createEmailTemplates();
        return $return;
    }

    public function createEmailTemplates()
    {
        $email = new EmailTemplate();
        $email->create();
    }
}
