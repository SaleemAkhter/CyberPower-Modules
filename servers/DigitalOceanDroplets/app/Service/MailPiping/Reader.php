<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Service\MailPiping;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Service\MailPiping\Abstracts\AbstractImapConnection;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Product;
use stdClass;

class Reader extends AbstractImapConnection
{

    use Traits\ImapReader, Traits\ContentParser;

    public function __construct($product)
    {
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

        $config                             = new stdClass();
        $config->hostname                   = $fields->getField('hostname');
        $config->port                       = $fields->getField('port');
        $config->sslType                    = strtolower($fields->getField('sslType'));
        $config->username                   = $fields->getField('username');
        $config->password                   = $fields->getField('password');
        $config->noValidateCertificate      = $fields->getField('noValidateCertificate');
        $config->folder                     = $fields->getField('folder');
        return $config;
    }

    private function getProduct($productID)
    {
        $product = Product::where('id', $productID)->first();
        if ($product->servertype != "DigitalOceanDroplets")
        {
            throw new Exception('Wrong product type. Correct product type is a`DigitalOceanDroplets`');
        }
        return $product;
    }

    public function read()
    {
        $this->setImap();
        $this->imapReadHeders()->readMails()->imapClose();
    }



}
