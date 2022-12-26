<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class CronEdit extends Cron
{
    use Lang;
    public function read()
    {

        $rowData = json_decode(base64_decode($this->actionElementId));

        $this->data['hiddenID']     = $rowData->id;
        $this->data['minute']       = $rowData->minute;
        $this->data['hour']         = $rowData->hour;
        $this->data['day']          = $rowData->day;
        $this->data['month']        = $rowData->month;
        $this->data['week']         = $rowData->week;
        $this->data['command']      = htmlentities($rowData->command);
        $this->loadLang();

        $this->availableValues['commonSettings'] = [
            "--"           => $this->lang->translate("Custom"),
            "* * * * *"    => $this->lang->translate("EveryMinute"),
            "*/5 * * * *"  => $this->lang->translate("Every5Minutes"),
            "0,30 * * * *" => $this->lang->translate("TwiceAnHour"),
            "0 * * * *"    => $this->lang->translate("OnceAnHour"),
            "0 0,12 * * *" => $this->lang->translate("TwiceADay"),
            "0 0 * * *"    => $this->lang->translate("OnceADay"),
            "0 0 * * 0"    => $this->lang->translate("OnceAWeek"),
            "0 0 1,15 * *" => $this->lang->translate("FirstAndFifteen"),
            "0 0 1 * *"    => $this->lang->translate("OnceAMonth"),
            "0 0 1 1 *"    => $this->lang->translate("OnceAYear")
        ];



    }

    public function update()
    {
        $this->loadUserApi();

        if($this->formData['commonSettings'] !== '--')
        {
            $explodeCommonSettings = explode(' ', $this->formData['commonSettings']);
            list(
                $this->formData['minute'],
                $this->formData['hour'],
                $this->formData['day'],
                $this->formData['month'],
                $this->formData['week'],
                ) = $explodeCommonSettings;
        }

        $data = [
            'id'        => $this->formData['hiddenID'],
            'minute'    => $this->formData['minute'],
            'hour'      => $this->formData['hour'],
            'day'       => $this->formData['day'],
            'month'     => $this->formData['month'],
            'week'      => $this->formData['week'],
            'command'   => html_entity_decode($this->formData['command'])
        ];

        $this->userApi->cron->save(new Models\Command\Cron($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('cronHasBeenAdded');
    }

    public function delete()
    {
        parent::delete();

        $data = [
            'id' => ($this->formData['idHidden'] == "") ? "0" : $this->formData['idHidden']
        ];
        $this->userApi->cron->delete(new Models\Command\Cron($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('cronHasBeenDeleted');
    }

    public function reload()
    {
        $this->data['commonSettings']   = (is_null($this->formData['commonSettings'])) ? $this->data['commonSettings'] : $this->formData['commonSettings'] ;
        $this->data['command']   = (is_null($this->formData['command'])) ? $this->data['command'] : $this->formData['command'] ;
    }

}
