<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Forms;

use ModulesGarden\Servers\HetznerVps\App\Helpers\Validators\Ipv6Validator;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Helpers\ReverseDNSMenager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\InputGroup;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\FormGroupSection;
use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Providers\ReverseDNSProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\RawSection;
use ModulesGarden\Servers\HetznerVps\App\Helpers\Validators\DomainValidator;

class AddReverseDNSForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id               = 'reverseDNSAddForm';
    protected $name             = 'reverseDNSAddForm';
    protected $title            = 'reverseDNSAddForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new ReverseDNSProvider());

        $prefix = (new Hidden('prefix'))
            ->initIds('prefix')
            ->setDefaultValue($this->getIPv6Parts());

        $ipv6 = (new InputGroup('ipv6'))
            ->addInputAddon('prefix', false, $this->getIPv6Parts())
            ->addTextField('identifier', false, true);

        $identifier = $ipv6->getField('identifier');
        $identifier->addValidator(new Ipv6Validator());

        $dnsInput = (new InputGroup('dns_ptr'))
            ->addField($prefix)
            ->addTextField('dns_ptr', false, true);
        $dnsInput->getField('dns_ptr')->addValidator(new DomainValidator());

//        $dns_ptr = (new Text())
//            ->initIds('dns_ptr')
//            ->notEmpty()
//            ->addValidator(new DomainValidator());

        $rawSection = (new RawSection('rawSection'));

        $rawSection
            ->addSection($ipv6)
            ->addSection($dnsInput);

        $this
            ->addSection($rawSection);

        $this->loadDataToForm();

    }


    public function getIPv6Parts()
    {
        $serverId = $this->getWhmcsParams()['customfields']['serverID'];

        $api = new Api($this->getWhmcsParams());
        $server = $api->servers()->get($serverId);

        $ipv6 = $server->publicNet->ipv6->ip;
        $ipv6Parts = explode('::/', $ipv6);
        $prefix =$ipv6Parts[0];

        return $prefix;
    }
}