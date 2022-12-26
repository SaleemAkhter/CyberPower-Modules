<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Helper\StringFormat;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class MailingList extends AbstractCommand
{
    const CMD_EMAIL_LIST = 'CMD_API_EMAIL_LIST';
    const CMD_EMAIL_LIST_V2 = 'CMD_EMAIL_LIST';

    const TYPE_DIGEST        = 'digest';
    const DELETE_SUBS_DIGEST = 'delete_subscriber_digest';
    const DELETE_SUBS        = 'delete_subscriber';

    /**
     * get mailing lists list
     *
     * @param Models\Command\MailingList $mailingList
     * @return mixed
     */
    public function lists(Models\Command\MailingList $mailingList)
    {
        $response = $this->curl->request(self::CMD_EMAIL_LIST, [
            'domain' => $mailingList->getDomain()
        ]);

        return $this->loadResponse(new Models\Command\MailingList(), $response);
    }

    /**
     * create mailing list
     *
     * @param Models\Command\MailingList $mailingList
     * @return mixed
     */
    public function create(Models\Command\MailingList $mailingList)
    {
        return $this->curl->request(self::CMD_EMAIL_LIST, [
            'action'    => __FUNCTION__,
            'domain'    => $mailingList->getDomain(),
            'name'      => $mailingList->getName()
        ]);
    }

    /**
     * delete mailing list
     *
     * @param Models\Command\MailingList $mailingList
     * @return mixed
     */
    public function delete(Models\Command\MailingList $mailingList)
    {
        return $this->curl->request(self::CMD_EMAIL_LIST, [
            'action'    => __FUNCTION__,
            'domain'    => $mailingList->getDomain(),
            'select0'   => $mailingList->getName()
        ]);
    }

    /**
     * modify mailing list
     *
     * @param Models\Command\MailingList $mailingList
     * @return mixed
     */
    public function modify(Models\Command\MailingList $mailingList)
    {
        $response = $this->curl->request(self::CMD_EMAIL_LIST, [
            'action'    => __FUNCTION__,
            'domain'    => $mailingList->getDomain(),
            'name'      => $mailingList->getName()
        ]);

        return $this->loadResponse(new Models\Command\MailingList(), $response, __FUNCTION__);
    }

    public function modifyV2(Models\Command\MailingList $mailingList)
    {
        $response = $this->curl->request(self::CMD_EMAIL_LIST_V2, [], [
            'json'      => 'yes',
            'action'    => 'modify',
            'domain'    => $mailingList->getDomain(),
            'name'      => $mailingList->getName()
        ]);
        return $this->loadResponse(new Models\Command\MailingList(), $response, 'modifyV2');
    }

    /**
     * view mailing list
     *
     * @param Models\Command\MailingList $mailingList
     * @return mixed
     */
    public function view(Models\Command\MailingList $mailingList)
    {
        $response =  $this->curl->request(self::CMD_EMAIL_LIST, [
            'action'    => __FUNCTION__,
            'domain'    => $mailingList->getDomain(),
            'name'      => $mailingList->getName()
        ]);

        return $this->loadResponse(new Models\Command\MailingList(), $response, __FUNCTION__);
    }

    /**
     * add mailing list
     *
     * @param Models\Command\MailingList $mailingList
     * @return mixed
     */
    public function add(Models\Command\MailingList $mailingList)
    {
        return $this->curl->request(self::CMD_EMAIL_LIST, [
            'action'    => __FUNCTION__,
            'domain'    => $mailingList->getDomain(),
            'name'      => $mailingList->getName(),
            'type'      => $mailingList->getType(),
            'email'     => $mailingList->getEmail()
        ]);
    }

    /**
     * remove subscriber
     *
     * @param Models\Command\MailingList $mailingList
     * @return mixed
     */
    public function deleteSubscriber(Models\Command\MailingList $mailingList)
    {
        return $this->curl->request(self::CMD_EMAIL_LIST, [
            'action'    => StringFormat::getWithUnderscore(__FUNCTION__),
            'domain'    => $mailingList->getDomain(),
            'name'      => $mailingList->getName(),
            'select0'   => $mailingList->getEmail()
        ]);
    }

    /**
     * remove digest subscriber
     *
     * @param Models\Command\MailingList $mailingList
     * @return mixed
     */
    public function deleteSubscriberDigest(Models\Command\MailingList $mailingList)
    {
        return $this->curl->request(self::CMD_EMAIL_LIST, [
            'action'    => StringFormat::getWithUnderscore(__FUNCTION__),
            'domain'    => $mailingList->getDomain(),
            'name'      => $mailingList->getName(),
            'select0'   => $mailingList->getEmail()
        ]);
    }

    /**
     * save mailing list
     *
     * @param Models\Command\MailingList $mailingList
     * @return mixed
     */
    public function save(Models\Command\MailingList $mailingList)
    {
        return $this->curl->request(self::CMD_EMAIL_LIST, [
            'action'            => __FUNCTION__,
            'domain'            => $mailingList->getDomain(),
            'name'              => $mailingList->getName(),
            'admin_passwd'      => $mailingList->getAdminPasswd(),
            'approve_passwd'    => $mailingList->getApprovePasswd(),
            'reply_to'          => $mailingList->getReplyTo(),
            'restrict_post'     => $mailingList->getRestrictPost(),
            'digest_issue'      => $mailingList->getDigestIssue(),
            'digest_volume'     => $mailingList->getDigestVolume(),
            'digest_maxdays'    => $mailingList->getDigestMaxdays(),
            'digest_maxlines'   => $mailingList->getDigestMaxlines(),
            'precedence'        => $mailingList->getPrecedence(),
            'moderator'         => $mailingList->getModerator(),
            'subject_prefix'    => $mailingList->getSubjectPrefix(),
            'maxlength'         => $mailingList->getMaxLength(),
            'description'       => $mailingList->getDescription(),
            'taboo_body'        => $mailingList->getTabooBody(),
            'taboo_headers'     => $mailingList->getTabooHeaders(),
            'message_footer'    => $mailingList->getMessageFooter(),
            'message_fronter'   => $mailingList->getMessageFronter(),
            'message_headers'   => $mailingList->getMessageHeaders(),
            'administrivia'     => $mailingList->getAdministrivia(),
            'advertise'         => $mailingList->getAdvertise(),
            'announcements'     => $mailingList->getAnnouncements(),
            'archive_dir'       => $mailingList->getArchiveDir(),
            'comments'          => $mailingList->getComments(),
            'date_info'         => $mailingList->getDateInfo(),
            'date_intro'        => $mailingList->getDateIntro(),
            'debug'             => $mailingList->getDebug(),
            'digest_archive'    => $mailingList->getDigestArchive(),
            'digest_name'       => $mailingList->getDigestName(),
            'digest_rm_footer'  => $mailingList->getDigestRmFooter(),
            'digest_rm_fronter' => $mailingList->getDigestRmFronter(),
            'digest_work_dir'   => $mailingList->getDigestWorkDir(),
            'get_access'        => $mailingList->getGetAccess(),
            'index_access'      => $mailingList->getIndexAccess(),
            'info'              => $mailingList->getInfo(),
            'info_access'       => $mailingList->getInfoAccess(),
            'intro_access'      => $mailingList->getIntroAccess(),
            'moderate'          => $mailingList->getModerate(),
            'mungedomain'       => $mailingList->getMungedomain(),
            'noadvertise'       => $mailingList->getNoadvertise(),
            'purge_received'    => $mailingList->getPurgeReceived(),
            'resend_host'       => $mailingList->getResendHost(),
            'sender'            => $mailingList->getSender(),
            'strip'             => $mailingList->getStrip(),
            'subscribe_policy'  => $mailingList->getSubscribePolicy(),
            'unsubscribe_policy'=> $mailingList->getUnsubscribePolicy(),
            'welcome'           => $mailingList->getWelcome(),
            'which_access'      => $mailingList->getWhichAccess(),
            'who_access'        => $mailingList->getWhoAccess(),
        ], [], false, true);
    }

    public function deleteMany(array $deleteData)
    {
        foreach($deleteData as $key => $value) {
            $data = [
                'json'    => 'yes',
                'action' => 'delete',
                'delete' => 'yes',
            ];
            if($data['domain'] !== $key || empty($data['domain']))
            {
                $data['domain'] = $key;

                foreach($value as $elem => $each)
                {
                    $data['select'.$elem] = $each;
                }
                $this->curl->request(self::CMD_EMAIL_LIST_V2, $data);
            }
        }
    }

    public function deleteSubMany(array $deleteData)
    {
        $data = [
            'json'    => 'yes',
            'delete' => 'yes'
        ];
        foreach($deleteData as $key => $value)
        {
            $data['select'.$key] = $value->getEmail();
            $data['domain'] = $value->getDomain();
            $data['name'] = $value->getName();
            $data['digest'] = $value->getType() === self::TYPE_DIGEST ? 'yes' : 'no';
            $data['action'] = $value->getType() === self::TYPE_DIGEST  ? self::DELETE_SUBS_DIGEST : self::DELETE_SUBS;
        }
        $this->curl->request(self::CMD_EMAIL_LIST_V2, [], $data);
    }
}