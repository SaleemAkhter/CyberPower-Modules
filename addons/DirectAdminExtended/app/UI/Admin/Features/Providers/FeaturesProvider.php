<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Providers;

use ModulesGarden\DirectAdminExtended\App\Models\BackupPath;
use ModulesGarden\DirectAdminExtended\App\Models\FTPEndPoints;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\DirectAdminExtended\App\Models\FunctionsSettings;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs\Product;
use ModulesGarden\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\DirectAdminExtended\App\Services\InstallScriptsService;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ServerParams;

class FeaturesProvider extends BaseDataProvider
{

    public function read()
    {
        if($this->getRequestValue('download') == 'applist')
        {
            return $this->downloadAppList();
        }
        $productModel = new Product();
        $products     = $productModel->join('tblproductgroups', 'tblproductgroups.id', '=', 'tblproducts.gid')
                ->where('tblproducts.servertype', '=', 'directadminExtended')
                ->get(['tblproducts.id as pid', 'tblproducts.name as name', 'tblproductgroups.name as group'])
                ->toArray();

        $productsSelect = [];
        foreach ($products as $product)
        {
            $productsSelect[$product['pid']] = $product['group'] . ' - ' . $product['name'];
        }
        $this->data['selectedProduct']                  = $this->actionElementId;
        $this->data['fromProduct']                      = [];
        $this->availableValues['fromProduct']  = $productsSelect;
    }

    public function create()
    {
        
    }

    public function update()
    {
        $copyFrom = $this->formData['fromProduct'];
        $copyTo   = $this->formData['selectedProduct'];

        $featuresModel = new FunctionsSettings();
        $ftpModel = new FTPEndPoints();
        $backupsModel = new BackupPath();

        if($copyFrom == $copyTo){
            return (new ResponseTemplates\RawDataJsonResponse())->setStatusError()->setMessageAndTranslate('cannotCopiedToSelf');
        }
        if (!$featuresModel->where('product_id', '=', $copyFrom)->first())
        {
            return (new ResponseTemplates\RawDataJsonResponse())->setStatusError()->setMessageAndTranslate('emptySettingsCannotBeCopied');
        }
        $fromSettings = $featuresModel->where('product_id', '=', $copyFrom)->first()->toArray();
        unset($fromSettings['product_id']);

        if($ftpModel->where('product_id', '=', $copyFrom)->first()){
            $fromFTPSettings = $ftpModel->where('product_id', '=', $copyFrom)->get()->toArray();
        }

        if($backupsModel->where('product_id', '=', $copyFrom)->first()){
            $fromBackupsSettings = $backupsModel->where('product_id', '=', $copyFrom)->get()->toArray();
        }

        if ($this->getRequestValue('massActions', []))
        {

            foreach ($this->getRequestValue('massActions', []) as $product) {
                $this->copyFeaturesSettings($featuresModel, $fromSettings, $product);

                //clean configuration before update
                $ftpModel->where('product_id', $product)->delete();
                $backupsModel->where('product_id', $product)->delete();

                if (isset($fromFTPSettings)) {
                    $this->copyBackupsSettings($ftpModel, $fromFTPSettings, $product);
                }
                if (isset($fromBackupsSettings)) {
                    $this->copyBackupsSettings($backupsModel, $fromBackupsSettings, $product);
                }
            }
        }
        else
        {
            $this->copyFeaturesSettings($featuresModel, $fromSettings, $copyTo);

            //clean configuration before update
            $ftpModel->where('product_id', $copyTo)->delete();
            $backupsModel->where('product_id', $copyTo)->delete();

            if (isset($fromFTPSettings)) {
                $this->copyBackupsSettings($ftpModel, $fromFTPSettings, $copyTo);
            }
            if (isset($fromBackupsSettings)) {
                $this->copyBackupsSettings($backupsModel, $fromBackupsSettings, $copyTo);
            }
        }

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('configurationHasBeenCopied');
    }

    private function copyFeaturesSettings($model, $fromSettings, $copyTo){
        if (!$model->where('product_id', '=', $copyTo)->first())
        {
            $model->insert(array_merge(['product_id' => $copyTo], $fromSettings));
        }
        else
        {
            $model->where('product_id', '=', $copyTo)->update($fromSettings);
        }
    }

    private function copyBackupsSettings($model, $fromBackupsSettings, $copyTo){


        foreach($fromBackupsSettings as $backupSettings){
            unset($backupSettings['id']);
            unset($backupSettings['product_id']);

            $model->insert(array_merge(['product_id' => $copyTo], $backupSettings));
        }
    }


    
    private function downloadAppList()
    {
        $tempDir = sys_get_temp_dir();
        $productId            = $this->getRequestValue('pid');
        $serverParams         = ServerParams::getServerParamsByProductId($productId);
        $installer            = (new FunctionsSettings())->where('product_id',$productId)->first()->apps_installer_type;
        $installerName        = $installer == 1 ? 'Softaculous' : 'Installatron';
        $installerScripts     = InstallScriptsService::init($serverParams)->setInstaller($installerName)->getScripts();
        $iname                = $installerName . '.txt';
        
        $list = strtoupper($installerName) . " APPLICATIONS LIST\r\n";
        $list .= "If you want to automatically install the latest version of the application then you have to use only application name without version number.\r\n";
        $list .= "Please note that Option Name of Configurable Options must be \"Installation App\". In other case the entire functionality will not work (https://www.docs.modulesgarden.com/DirectAdmin_Extended_For_WHMCS#Configurable_Options_For_Application_Autoinstall).\r\n\r\n";
        $list .= str_pad("APP NAME WITH VERSION NUMBER", 40) . " | APP NAME WITHOUT VERSION NUMBER\r\n";
        foreach ($installerScripts as $l)
        {
            $lines[] = str_pad($l['name'] . " " . $l['version'], 40) . " | " . $l['name'] . "\r\n";
        }
        sort($lines);

        foreach ($lines as $l)
        {
            $list .= $l;
        }

        $tmpfname = tempnam($tempDir, "al");
        $fp       = fopen($tmpfname, 'w');
        fwrite($fp, $list);
        fclose($fp);

        if (file_exists($tmpfname))
        {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $iname);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($tmpfname));
            ob_clean();
            flush();
            readfile($tmpfname);
            unlink($tmpfname);
            die();
        }

    }

    public function delete()
    {
        
    }
}
