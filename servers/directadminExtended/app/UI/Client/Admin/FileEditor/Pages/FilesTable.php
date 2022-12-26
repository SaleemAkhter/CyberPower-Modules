<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\FileEditor\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\FileEditor\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;


class FilesTable extends DataTableApi implements ClientArea
{
    use WhmcsParams;

    protected $id    = 'usersTable';
    protected $name  = 'usersTable';
    protected $title = null;

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn(
            (new Column('file'))
                ->setSearchable(true, Column::TYPE_STRING)
                ->setOrderable('ASC')
        )
        ->addColumn((new Column('size'))->setOrderable(true));
    }

    public function initContent()
    {
    }
    public function getEditButton()
    {
        $button = new ButtonRedirect('editButton');

        $button->setRawUrl($this->getURL())
            ->setRedirectParams(['actionElementId'=>':id']);

        return $button;
    }

    public function getLoginAsButton()
    {
        $button = new ButtonRedirect('loginAsButton');

        $button->setRawUrl($this->getLoginURL())
            ->setIcon('lu-btn__icon lu-zmdi lu-zmdi-square-right')
            ->setRedirectParams(['actionElementId'=>':id','doLogin'=>'1']);

        return $button;
    }
    protected function getURL($id)
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'FileEditor',
            'mg-action'     => 'Edit',
            'actionElementId'=>$id
        ];

        return 'clientarea.php?'. \http_build_query($params);
    }
    public function replaceFieldFile($key, $row)
    {
        return '<a href="'.$this->getURL($row['id']).'">'.$row['file'].'</a>';

    }
    public function replaceFieldSize($key, $row)
    {
        if($row['exists'])
        {
            return $this->bytesToHuman($row['size']) ;
        }
        return "Does not exist";

    }
    protected function loadData()
    {

        $this->loadAdminApi();

        $result     = $this->adminApi->fileEditor->listAll();
        $files=[];
        foreach($result->FILES as $key=>$f){
            $file=[];
            $file['file']=$key;
            $file['size']=$f->size;
            $file['exists']=$f->exists;
            $file['id']=base64_encode(json_encode($file));
            $files[]=$file;
        }
        $result = json_decode(json_encode($files),true);
        $provider   = new ArrayDataProvider();
        $provider->setData($result);
        $provider->setDefaultSorting('file', 'ASC');
        $this->setDataProvider($provider);
    }

    private function toArray($result)
    {
        $resultArray = [];
        foreach($result as $keyRow => $row)
        {
            if(is_object($row))
            {
                foreach($row as $key => $value)
                {
                    $resultArray[$keyRow][$key] = $value;
                }

                continue;
            }
            $resultArray[$keyRow] = $row;
        }

        return $resultArray;
    }
    protected function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }

}
