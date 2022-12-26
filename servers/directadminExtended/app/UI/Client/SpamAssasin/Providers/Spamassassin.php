<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamAssasin\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Spamassassin extends ProviderApi
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
    public function read()
    {
        $this->loadLang();
        $this->data['destination']               = [];
        $this->availableValues['destination']    = [
            'inbox'             => $this->lang->translate('destination', 'inbox'),
            'spamfolder'        => $this->lang->translate('destination', 'spamfolder'),
            'userspamfolder'    => $this->lang->translate('destination', 'userspamfolder'),
            'delete'            => $this->lang->translate('destination', 'delete'),
        ];

        $this->availableValues['score']     = [
            '5.0'     => $this->lang->translate('score', '5'),
            '7.5'     => $this->lang->translate('score', '7'),
            '10.0'    => $this->lang->translate('score', '10'),
            'custom'  => $this->lang->absoluteTranslate('custom'),
        ];
        $this->data['score']                = '5.0';
        $this->data['deliver']              = [];
        $this->availableValues['deliver']   = [
            '0'     => $this->lang->translate('deliver', '0'),
            '1'     => $this->lang->translate('deliver', '1'),
            '2'     => $this->lang->translate('deliver', '2')
        ];
        $this->fillFormFields();
    }
    protected function getArrayNumberValues($startNumber, $endNumber){
        $dataValues = [];
        for($i = $startNumber; $i <= $endNumber; $i++){
            $dataValues[$i] = $i;
        }

        return $dataValues;
    }

    protected function fillFormFields(){




        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
            {
                $this->loadResellerApi([],false);
                $domains=$this->resellerApi->domain->lists();
                $domainlist=$domains->getResponse();

                if(!empty($domainlist)){
                    $domain=$domainlist[0];
                    $domainname=$domain->name;
                    $data     = [
                        'domain' => $domainname
                    ];

                    $spamAssassinDetails = $this->resellerApi->spamassassin->get(new Models\Command\Spamassassin($data))->getResponse();

                }else{
                    $result=[];
                }


            }else{
                $this->loadUserApi();
                $data   = [
                    'domain' => $this->getWhmcsParamByKey('domain')
                ];
                $spamAssassinDetails = $this->userApi->spamassassin->get(new Models\Command\Spamassassin($data))->getResponse();
            }

        $this->data['destination']     = $spamAssassinDetails[0]->getWhere();
        $this->data['score']           = (array_key_exists($spamAssassinDetails[0]->getRequiredScore(), $this->availableValues['score'])) ? $spamAssassinDetails[0]->getRequiredScore() : 'custom';
        $this->data['customScore']     = $spamAssassinDetails[0]->getRequiredScore();
        $this->data['noDeleteScore']   = ($spamAssassinDetails[0]->getHighScoreBlock() == "yes")? "on": "off";
        $this->data['deleteScore']     = (int) $spamAssassinDetails[0]->getHighScore();
        $this->data['noSubject']       = (!is_null($spamAssassinDetails[0]->getRewriteHeader()))? "off": "on";
        $this->data['subject']         = $spamAssassinDetails[0]->getSubjectTagFromHeader();
        $this->data['hiddenNoSubject'] = $spamAssassinDetails[0]->getRewriteSubject();
        $this->data['hiddenSubject']   = $spamAssassinDetails[0]->getSubjectTag();
        $this->data['blacklist']       = implode('&#13;&#10;', $spamAssassinDetails[0]->getBlackList());
        $this->data['whitelist']       = implode('&#13;&#10;', $spamAssassinDetails[0]->getWhiteList());
        $this->data['deliver']         = $spamAssassinDetails[0]->getReportSafe();

    }

    public function update()
    {
        parent::update();


        $data = [
            'domain'                => $this->getWhmcsParamByKey('domain', false),
            'where'                 => $this->formData['destination'],
            'requiredScore'         => $this->formData['score'],
            'requiredScoreCustom'   => $this->formData['customScore'],
            'highScoreBlock'        => ($this->formData['noDeleteScore'] == "on")? "yes": "no",
            'highScore'             => $this->formData['deleteScore'],
            'rewriteHeader'         => ($this->formData['noSubject'] == "off") ? $this->formData['subject']: "",
            'rewriteSubject'        => ($this->formData['noSubject'] == "off") ? 1 : 0,
            'subjectTag'            => ($this->formData['noSubject'] == "off") ? $this->formData['subject'] : $this->data['hiddenSubject'],
            'blackList'             => explode(PHP_EOL, preg_replace('/\s/', PHP_EOL, $this->formData['blacklist'])),
            'whiteList'             => explode(PHP_EOL, preg_replace('/\s/', PHP_EOL, $this->formData['whitelist'])),
            'reportSafe'            => $this->formData['deliver'],
        ];

        $this->userApi->spamassassin->save(new Models\Command\Spamassassin($data));
    }
}
