<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Pages;

use ModulesGarden\Servers\AwsEc2\Core\Queue\Models\Job;
use ModulesGarden\Servers\AwsEc2\Core\Queue\Models\JobLog;
use ModulesGarden\Servers\AwsEc2\Core\Traits\IsAdmin;
use ModulesGarden\Servers\AwsEc2\Core\Traits\Lang;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\Traits\AdminAreaServicePageHelper;
use ModulesGarden\Servers\AwsEc2\App\Helpers\InstanceStatus;

class ScheduledTasks extends DataTable implements AdminArea, ClientArea
{
    use AdminAreaServicePageHelper;
    use IsAdmin;
    use Lang;

    protected $id = 'scheduledTasks';
    protected $name = 'scheduledTasks';
    protected $title = 'scheduledTasksTitle';


    public function initContent()
    {
        $this->searchable = false;
        $this->disabledViewFooter();
        $this->disabledViewTopBody();

        $this->addColumn(new Column('job'));
        $this->addColumn(new Column('status'));
        if ($this->isAdmin())
        {
            $this->addColumn(new Column('retry_count'));
        }
        $this->addColumn(new Column('message'));
        $this->addColumn(new Column('created_at'));
        if ($this->isAdmin())
        {
            $this->addColumn(new Column('updated_at'));
        }
    }

    public function loadData()
    {
        $this->loadLang();

        $serviceId = (int)$this->getServiceIdForAAServicePage();
        $jobModel = new Job();
        $dataQuery = $jobModel->query()->getQuery()
            ->select(['id', 'job', 'status', 'retry_count', 'created_at', 'updated_at'])
            ->where('rel_id', $serviceId)->where('rel_type', 'Hosting')->where('status', '!=', Job::STATUS_CANCELED)
            ->where('status', '!=', Job::STATUS_FINISHED);

        $dataProvieder = new QueryDataProvider();
        $dataProvieder->setData($dataQuery);
        $this->setDataProvider($dataProvieder);
    }

    public function checkStatus($message, $row)
    {
        if ($this->getTaskName($row->job) === 'Changing Package' && stripos($message, 'stopped') !== false)
        {
            return $this->translateDbData('The machine status must be "Stopped" to run this task');
        }

        if (!is_string($message) || trim($message) === '' || !$message)
        {
            return  '-';
        }

        return $message;
    }
    public function replaceFieldJob($key, $row)
    {
        $status = $this->getTaskName($row->{$key});

        return $this->translateDbData($status, 'task');
    }

    public function replaceFieldStatus($key, $row)
    {
        if (!$row->{$key})
        {
            $row->{$key} = 'waiting';
        }

        $status = ucfirst($row->{$key});
        if ($this->isAdmin())
        {
            return $status;
        }

        return $this->translateDbData($status, 'taskStatus');
    }

    public function replaceFieldMessage($key, $row)
    {
        $jobLogModel = new JobLog();
        $jobLog = $jobLogModel->select('message')->where('job_id', $row->id)->orderBy('id', 'desc')->first();

        $message = $jobLog === null ? $jobLog : $jobLog->message;

        return $this->checkStatus($message, $row);
    }

    public function translateDbData($text = null, $type = null)
    {
        if ($this->isAdmin())
        {
            return $text;
        }

        if (is_string($type) && trim($type) !== '')
        {
            return $this->lang->translate($this->id, $type, $text);
        }

        return $this->lang->translate($this->id, $text);
    }

    public function getTaskName($className = null)
    {
        if (stripos($className, 'VmCreated') > 0)
        {
            return 'Creating VM';
        }

        if (stripos($className, 'VmDeleted') > 0)
        {
            return 'Deleting VM';
        }

        if (stripos($className, 'VmChangedPackage') > 0)
        {
            return 'Changing Package';
        }

        if (stripos($className, 'VmNetworkInterfaceDetached'))
        {
            return 'Detaching Network Interface';
        }

        if (stripos($className, 'GetWindowsPassword'))
        {
            return 'Loading Windows Password';
        }

        return $className;
    }
}
