<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class MailingListsSettings extends MailingLists
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public $selectValuesV1 = ['list' => '', 'open' => '', 'closed' => ''];
    public $selectValuesV2 = ['yes' => '', 'no' => ''];
    public $selectValuesV3 = ['auto' => '', 'open' => '', 'closed' => '', 'open+confirm' => '', 'auto+confirm' => '', 'closed+confirm' => ''];

    public function read()
    {
        $this->loadLang();

        $this->initSelectValues();

        if ($this->getRequestValue('index') === 'settingsForm')
        {
            return;
        }
        parent::loadUserApi();
        parent::read();

        $explodeList = explode('@', $this->actionElementId);
        $data        = [
            'name'   => $explodeList[0],
            'domain' => $explodeList[1]
        ];
        $result2 = $this->userApi->mailingList->modify(new Models\Command\MailingList($data))->first();

        $result = $this->userApi->mailingList->modifyV2(new Models\Command\MailingList($data));

        if ($result)
        {
            $this->data['options[adminPasswd]']      = $result->getAdminPasswd();
            $this->data['options[replyTo]']          = $result->getReplyTo();
            $this->data['options[digestIssue]']      = $result->getDigestIssue();
            $this->data['options[digestMaxdays]']    = $result->getDigestMaxdays();
            $this->data['options[precedence]']       = $result->getPrecedence();
            $this->data['options[subjectPrefix]']    = $result->getSubjectPrefix();
            $this->data['options[approvePasswd]']    = $result->getApprovePasswd();
            $this->data['options[restrictPost]']     = $result->getRestrictPost();
            $this->data['options[digestVolume]']     = $result->getDigestVolume();
            $this->data['options[digestMaxlines]']   = $result->getDigestMaxlines();
            $this->data['options[moderator]']        = $result->getModerator();
            $this->data['options[description]']      = $result->getDescription();
            $this->data['options[tabooBody]']        = $result->getTabooBody();
            $this->data['options[tabooHeaders]']     = $result->getTabooHeaders();
            $this->data['options[messageFooter]']    = $result->getMessageFooter();
            $this->data['options[messageFronter]']   = $result->getMessageFronter();
            $this->data['options[messageHeaders]']   = $result->getMessageHeaders();
            $this->data['options[maxlength]']        = is_numeric($result->getMaxLength()) ? $result->getMaxLength() :  $result2->getMaxLength();
            $this->data['options[subscribePolicy]']   = $result->getSubscribePolicy();
            $this->data['options[getAccess]']   = $result->getGetAccess();
            $this->data['options[infoAccess]']   = $result->getInfoAccess();
            $this->data['options[whichAccess]']   = $result->getWhichAccess();
            $this->data['options[welcome]']   = $result->getWelcome();
            $this->data['options[moderate]']   = $result->getModerate();
            $this->data['options[strip]']   = $result->getStrip();
            $this->data['options[administrivia]']   = $result->getAdministrivia();
            $this->data['options[indexAccess]']   = $result->getIndexAccess();
            $this->data['options[introAccess]']   = $result->getIntroAccess();
            $this->data['options[whoAccess]']   = $result->getWhoAccess();
            $this->data['options[mungedomain]']   = $result->getMungeDomain();
            $this->data['options[purgeReceived]']   = $result->getPurgeReceived();
            $this->data['options[unsubscribePolicy]']   = $result->getUnsubscribePolicy();
            $this->data['options[info]']   = $result->getInfo();
        }


        $this->availableValues['options[subscribePolicy]'] = $this->selectValuesV3;
        $this->availableValues['options[getAccess]'] = $this->selectValuesV1;
        $this->availableValues['options[infoAccess]'] = $this->selectValuesV1;
        $this->availableValues['options[whichAccess]'] = $this->selectValuesV1;
        $this->availableValues['options[welcome]'] = $this->selectValuesV2;
        $this->availableValues['options[moderate]'] = $this->selectValuesV2;
        $this->availableValues['options[strip]'] = $this->selectValuesV2;
        $this->availableValues['options[administrivia]'] = $this->selectValuesV2;
        $this->availableValues['options[indexAccess]'] = $this->selectValuesV1;
        $this->availableValues['options[introAccess]'] = $this->selectValuesV1;
        $this->availableValues['options[whoAccess]'] = $this->selectValuesV1;
        $this->availableValues['options[mungedomain]'] = $this->selectValuesV2;
        $this->availableValues['options[purgeReceived]'] = $this->selectValuesV2;
        $this->availableValues['options[unsubscribePolicy]'] = $this->selectValuesV1;

    }

    protected function translateRecursive($vars)
    {
        foreach($vars as $key => $value)
        {
            $vars[$key] =  $this->lang->absoluteTranslate($key);
        }

        return $vars;

    }
    protected function initSelectValues()
    {
        $this->selectValuesV1   = $this->translateRecursive($this->selectValuesV1);
        $this->selectValuesV2   = $this->translateRecursive($this->selectValuesV2);
        $this->selectValuesV3   = $this->translateRecursive($this->selectValuesV3);
    }
}
