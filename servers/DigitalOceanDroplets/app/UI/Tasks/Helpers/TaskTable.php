<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Tasks\Helpers;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class TaskTable
{

    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function getTasks()
    {
        $allTask = $this->getAll();
        $rows    = [];
        foreach ($allTask as $task)
        {
            $rows[] = $this->assignToTable($task);
        }
        return $rows;
    }

    private function getAll()
    {
        $serviceManager = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\ServiceManager($this->params);
        return $serviceManager->getTask();
    }

    private function assignToTable($row)
    {
        return [
            'id'         => $row->id,
            'task'       => \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang::getInstance()->absoluteT('task', $row->type),
            'status'     => ucfirst(str_replace("-", " ", $row->status)),
            'created_at' => \Carbon\Carbon::parse($row->startedAt)->toDateTimeString(),
            'end_at'     => \Carbon\Carbon::parse($row->completedAt, 'Europe/Paris')->toDateTimeString(),
        ];
    }

}
