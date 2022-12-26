<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Modals;

use ModulesGarden\Servers\AwsEc2\App\Models\SSHKey\SSHKeysRepository;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons\DownloadButton;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Forms\GetKey;
use ModulesGarden\Servers\AwsEc2\Core\Helper\BuildUrl;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ModalActionButtons\BaseCancelButton;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\BaseEditModal;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\isAdmin;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\sl;


class GetKeyModal extends BaseEditModal implements AdminArea, ClientArea

{
    protected $id = 'getKeyModal';
    protected $name = 'getKeyModal';
    protected $title = 'getKeyModalTitle';

    public function initContent()
    {
        $this->addForm(new GetKey());
        $params = sl('request')->query->all();
        $sshRepo = new SSHKeysRepository();
        if(!isAdmin()) {
            $download = new DownloadButton('download');
            $params['download'] = 'file';
            $download->setRawUrl(BuildUrl::getUrl('', '', $params))->setRedirectParams($params);

            if ($sshRepo->get($this->getWhmcsParamByKey('serviceid'))['private_key']) {
                $this->addActionButton($download);
            }
        }

        $this->addActionButton((new BaseCancelButton()));
    }
}
