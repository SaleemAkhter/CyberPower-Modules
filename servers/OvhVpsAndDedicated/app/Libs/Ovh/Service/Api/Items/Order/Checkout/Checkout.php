<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Checkout;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\AutomationForm;

/**
 * Class Checkout
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Checkout extends AbstractApiItem
{
    public function validate()
    {
        $fieldsProvider = new FieldsProvider($this->client->getProductID());

        $autoPayWithPreferredMethod = $fieldsProvider->getField(AutomationForm::AUTO_PAY_WITH_PREFERRED_METHOD);
        $waiveRetractationPeriod    = $fieldsProvider->getField(AutomationForm::WAIVE_RETRACTATION_PERIOD);

        $params = [
            'autoPayWithPreferredPaymentMethod' => $autoPayWithPreferredMethod == 'on',
            'waiveRetractationPeriod'           => $waiveRetractationPeriod == 'on',
        ];
        return $this->post(false, $params);
    }
}
