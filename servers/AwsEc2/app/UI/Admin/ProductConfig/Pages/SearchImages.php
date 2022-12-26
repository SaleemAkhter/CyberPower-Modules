<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Buttons\AddImageToAvailable;
use ModulesGarden\Servers\AwsEc2\Core\FileReader\Directory;
use ModulesGarden\Servers\AwsEc2\Core\ModuleConstants;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\Filters\Select;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\Filters\Text;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\RawDataTable\RawDataTable;

class SearchImages extends RawDataTable implements AdminArea
{
    protected $id = 'searchImages';
    protected $name = 'searchImages';
    protected $title = 'searchImagesTitle';

    protected $searchable = false;
    protected $isViewTopBody = false;

    protected $actionIdColumnName = 'ImageId';

    /*
     * time in seconds
     */
    protected $cacheTime = 300;

    public function initContent()
    {
        $this->disableAutoloadDataAfterCreated();

        $this->enabledTalbeLengthInfinity();

        $this->setFiltersPerRowCount(5);
        $this->setInternalAlertMessage('Due to a great number of available images on the AWS platform, please set up as many filters as possible in order the search process was successful');

        $this->loadFilters();

        $this->addColumn((new Column('ImageId'))->setSearchable(true));
        $this->addColumn((new Column('Description'))->setSearchable(true));

        $this->addActionButton(AddImageToAvailable::class);
    }

    public function loadData()
    {
        $list = $this->getDataWithCache();
        $filters = $this->getRequestValue('filters', []);

        $dataProvieder = new ArrayDataProvider();
        //$dataProvieder->setSearch($filters['descriptionFilter']);
        $dataProvieder->setData($list);
        $this->setDataProvider($dataProvieder);
    }

    protected function getDataWithCache()
    {
        $file = $this->getLastStorageFile();

        if ($this->isCacheDataValid($file))
        {
            $data = $this->getCacheData($file);
        }

        if (!$data)
        {
            $data = $this->getDataFormApi();
            $this->cacheData($data);
        }

        return $data;
    }

    public function isCacheDataValid($file)
    {
        $lastUpdate = (int)$file[1];
        if (time() - $lastUpdate > $this->cacheTime)
        {
            return false;
        }

        $newFilterValues = md5(json_encode($this->getRequestValue('filters', [])));
        $lastFilterValues = $file[2];
        if ($newFilterValues !== $lastFilterValues)
        {
            return false;
        }

        return true;
    }

    public function getLastStorageFile()
    {
        $dir = new Directory();
        $filesList = $dir->getFilesList($this->getStoragePath());
        foreach ($filesList as $file)
        {
            $nameParts = explode('-', $file);
            $fullPath = $this->getStoragePath() . DIRECTORY_SEPARATOR . $file;
            if ($nameParts[0] === 'amiStorage' && is_readable($fullPath))
            {
                return $nameParts;
            }
        }

        return false;
    }

    public function removeStorageFiles()
    {
        $dir = new Directory();
        $filesList = $dir->getFilesList($this->getStoragePath());
        foreach ($filesList as $file)
        {
            $nameParts = explode('-', $file);
            if ($nameParts[0] === 'amiStorage')
            {
                $fullPath = $this->getStoragePath() . DIRECTORY_SEPARATOR . $file;

                unlink($fullPath);
            }
        }
    }

    public function getStoragePath()
    {
        return ModuleConstants::getFullPath() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'app';
    }

    protected function cacheData($data = [])
    {
        $this->removeStorageFiles();

        $newFilterValues = md5(json_encode($this->getRequestValue('filters', [])));
        $path = $this->getStoragePath() . DIRECTORY_SEPARATOR . 'amiStorage-' . (string)time() . '-' . $newFilterValues;

        $parsed = json_encode($data);

        file_put_contents($path, $parsed);
    }

    public function getCacheData($file)
    {
        $path = $this->getStoragePath() . DIRECTORY_SEPARATOR . implode('-', $file);
        if (!is_readable($path))
        {
            return false;
        }

        $cont = file_get_contents($path);

        $list = json_decode($cont, true);

        return $list;
    }

    protected function getDataFormApi()
    {
        $awsClient = new ClientWrapper((int)$this->getRequestValue('id'));
        $list = $awsClient->getImagesList(['filters' => $this->getRequestValue('filters')]);

        return $list;
    }

    public function replaceFieldDescription($key, $row)
    {
        return ($row[$key] ? $row[$key] : $row['Name']);//ImageId
    }

    protected function loadFilters()
    {
        $descriptionFilter = new Text('searchName');
        $descriptionFilter->setPlaceholder('descriptionFilterPlaceholder');
        $descriptionFilter->setRequiredToSearch();
        $this->addFilter($descriptionFilter);

        $filtersData = \ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\Helpers\Images::FILTERS;
        foreach ($filtersData as $fId => $fData)
        {
            $filterInstance = new Select($fId);
            $filterValues = [];
            $filterValues['all'] = 'all';
            foreach ($fData['allowedValues'] as $fValue)
            {
                $filterValues[$fValue] = $fValue;
            }

            $filterInstance->setAvailableValues($filterValues);
            if ($fData['defaultValue'])
            {
                $filterInstance->setSelectedValue($fData['defaultValue']);
            }

            $this->addFilter($filterInstance);
        }
    }
}
