<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;



use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Database;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\File;
use ModulesGarden\Servers\DirectAdminExtended\App\Models\Hosting;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;

class DownloadFile
{
    use DirectAdminAPIComponent;

    protected $request;
    protected $path;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function getServerDetails()
    {
        return ServerParams::getServerParamsByHostingId($this->request->get('id'));
    }

    private function getParams(){
        $server = $this->getServerDetails();
        $hosting = $this->getHosting();

        return array_merge($server, $hosting);
    }

    private function getHosting(){
        $hosting =  Hosting::where('id', $this->request->get('id'))->first();
        if(is_null($hosting)){
            return [];
        }

        return [
            'username'=> $hosting->username,
            'password' => \decrypt($hosting->password)
        ];

    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path = "")
    {
        $this->path = $path;

        return $this;
    }
    public function download(){

        $server = $this->getParams();
        $this->loadFileManager($server);

        $this->fileManager->downloadFile($this->getFieModel());
    }
    private function getFieModel(){
        $fileName = $this->request->get('file');
        $model = new File();
        $model->setFullPath('/'. $this->path . $fileName);
        $model->setName($fileName);

        return $model;

    }

    public function downloadDatabase($name)
    {
        $this->loadUserApi();

        $model = new Database(['name' => $name]);
        $this->userApi->database->download($model);
    }

}
