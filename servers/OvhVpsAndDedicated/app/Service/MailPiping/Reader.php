<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\MailPiping;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\MailPiping\Abstracts\AbstractImapConnection;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Product;
use stdClass;

class Reader extends AbstractImapConnection
{

    use Traits\ImapReader, Traits\ContentParser;

    protected $currentProduct;

    public function __construct($product)
    {
        $this->currentProduct = $product;
        $config = $this->prepareConfig($product);

        parent::__construct($config);
    }

    private function prepareConfig($product)
    {
        if (is_integer($product))
        {
            $product = $this->getProduct($product);
        }
        $fields           = new FieldsProvider($product->id);
        $config           = new stdClass();
        $config->hostname = $fields->getField('hostname');
        $config->port     = $fields->getField('port');
        $config->sslType  = strtolower($fields->getField('sslType'));
        $config->username = $fields->getField('username');
        $config->password = $fields->getField('password');
        $config->folder  = $fields->getField('folder');

        return $config;
    }

    private function getProduct($productID)
    {
        $product = Product::where('id', $productID)->first();
        if ($product->servertype != "Ovh")
        {
            throw new Exception('Wrong product type. Correct product type is a`Ovh`');
        }
        return $product;
    }

    public function read()
    {
        $this->setImap();
        $this
            ->imapReadHeders()
            ->readMails()
            ->imapClose();
    }



}
