<?php

namespace ModulesGarden\Servers\HetznerVps\App\Service\CronTask;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Models\CronTasks;
use ModulesGarden\Servers\HetznerVps\App\Service\CronTask\Abstracts\AbstractActions;
use ModulesGarden\Servers\HetznerVps\App\Service\CronTask\Exceptions\WrongActions;

class CronTaskManager extends AbstractActions
{

    public function run()
    {
        foreach ($this->getTasks() as $task)
        {
            $task->status = 1;
            $task->save();
            try
            {
                $function = $this->checkAndGetFunction($task->action);
                $this->{$function}($task->params);
                $task->delete();
            }
            catch (WrongActions $ex)
            {
                $task->delete();
                echo "Error: " . $ex->getMessage(). "\n";
                continue;
            }
            catch (Exception $ex)
            {
                $task->status  = 0;
                $task->message = $ex->getMessage();
                $task->save();
                echo "Error: " . $ex->getMessage(). "\n";
                continue;
            }
        }
    }

    /*
     * Status:
     * 0 - waiting
     * 1 - active
     * 2 - complete
     */

    protected function getTasks()
    {
        return CronTasks::where('status', 0)->get();
    }

    protected function checkAndGetFunction($function)
    {
        $functionName = $function . "Actions";
        if (method_exists($this, $functionName))
        {
            return $functionName;
        }
        throw new WrongActions('Wrong Action');
    }

}
