<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 16:37
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class MailingList extends AbstractModel implements ResponseLoad
{
    public $domain;
    public $name;
    public $subscribers;
    public $digestSubscribers;
    public $type;
    public $email;
    protected $adminPasswd;
    protected $approvePasswd;
    protected $replyTo;
    protected $restrictPost;
    protected $digestIssue;
    protected $digestVolume;
    protected $digestMaxdays;
    protected $digestMaxlines;
    protected $precedence;
    protected $moderator;
    protected $subjectPrefix;
    protected $maxlength;
    protected $description;
    protected $tabooBody;
    protected $tabooHeaders;
    protected $messageFooter;
    protected $messageFronter;
    protected $messageHeaders;

    //v2 update
    protected $administrivia;
    protected $advertise;
    protected $announcements;
    protected $archiveDir;
    protected $comments;
    protected $dateInfo;
    protected $dateIntro;
    protected $debug;
    protected $digestArchive;
    protected $digestName;
    protected $digestRmFooter;
    protected $digestRmFronter;
    protected $digestWorkDir;
    protected $getAccess;
    protected $indexAccess;
    protected $info;
    protected $infoAccess;
    protected $introAccess;
    protected $moderate;
    protected $mungedomain;
    protected $noadvertise;
    protected $purgeReceived;
    protected $resendHost;
    protected $sender;
    protected $strip;
    protected $subscribePolicy;
    protected $unsubscribePolicy;
    protected $welcome;
    protected $whichAccess;
    protected $whoAccess;


    public function loadResponse($response, $function = null)
    {
        switch ($function)
        {
            case 'view':
                foreach ($response as $type => $email)
                {
                    if (strpos($type, 'd') !== false)
                    {
                        $type = 'digest';
                    }
                    else
                    {
                        $type = 'normal';
                    }
                    $data = [
                        'type'  => $type,
                        'email' => $email
                    ];

                    $this->addResponseElement(new self($data));
                }
                break;

            case 'modify':
                $this->addResponseElement(new self($response));
                break;
            case 'modifyV2':
                $this->fillFromJson($response);
                break;

            default:
                foreach ($response as $name => $sub)
                {
                    $explodeSub = explode(':', $sub);
                    $data       = [
                        'name'              => $name,
                        'subscribers'       => $explodeSub[0],
                        'digestSubscribers' => $explodeSub[1]
                    ];
                    $this->addResponseElement(new self($data));
                }
                break;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     * @return MailingList
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return MailingList
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return MailingList
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return MailingList
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }

    /**
     * @param mixed $subscribers
     * @return MailingList
     */
    public function setSubscribers($subscribers)
    {
        $this->subscribers = $subscribers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigestSubscribers()
    {
        return $this->digestSubscribers;
    }

    /**
     * @param mixed $digestSubscribers
     * @return MailingList
     */
    public function setDigestSubscribers($digestSubscribers)
    {
        $this->digestSubscribers = $digestSubscribers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdminPasswd()
    {
        return $this->adminPasswd;
    }

    /**
     * @param mixed $adminPasswd
     * @return MailingList
     */
    public function setAdminPasswd($adminPasswd)
    {
        $this->adminPasswd = $adminPasswd;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApprovePasswd()
    {
        return $this->approvePasswd;
    }

    /**
     * @param mixed $approvePasswd
     * @return MailingList
     */
    public function setApprovePasswd($approvePasswd)
    {
        $this->approvePasswd = $approvePasswd;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param mixed $replyTo
     * @return MailingList
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRestrictPost()
    {
        return $this->restrictPost;
    }

    /**
     * @param mixed $restrictPost
     * @return MailingList
     */
    public function setRestrictPost($restrictPost)
    {
        $this->restrictPost = $restrictPost;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigestIssue()
    {
        return $this->digestIssue;
    }

    /**
     * @param mixed $digestIssue
     * @return MailingList
     */
    public function setDigestIssue($digestIssue)
    {
        $this->digestIssue = $digestIssue;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigestVolume()
    {
        return $this->digestVolume;
    }

    /**
     * @param mixed $digestVolume
     * @return MailingList
     */
    public function setDigestVolume($digestVolume)
    {
        $this->digestVolume = $digestVolume;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigestMaxdays()
    {
        return $this->digestMaxdays;
    }

    /**
     * @param mixed $digestMaxdays
     * @return MailingList
     */
    public function setDigestMaxdays($digestMaxdays)
    {
        $this->digestMaxdays = $digestMaxdays;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigestMaxlines()
    {
        return $this->digestMaxlines;
    }

    /**
     * @param mixed $digestMaxlines
     * @return MailingList
     */
    public function setDigestMaxlines($digestMaxlines)
    {
        $this->digestMaxlines = $digestMaxlines;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrecedence()
    {
        return $this->precedence;
    }

    /**
     * @param mixed $precedence
     * @return MailingList
     */
    public function setPrecedence($precedence)
    {
        $this->precedence = $precedence;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModerator()
    {
        return $this->moderator;
    }

    /**
     * @param mixed $moderator
     * @return MailingList
     */
    public function setModerator($moderator)
    {
        $this->moderator = $moderator;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubjectPrefix()
    {
        return $this->subjectPrefix;
    }

    /**
     * @param mixed $subjectPrefix
     * @return MailingList
     */
    public function setSubjectPrefix($subjectPrefix)
    {
        $this->subjectPrefix = $subjectPrefix;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxLength()
    {
        return $this->maxlength;
    }

    /**
     * @param mixed $maxLength
     * @return MailingList
     */
    public function setMaxLength($maxlength)
    {
        $this->maxlength = maxlength;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return MailingList
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTabooBody()
    {
        return $this->tabooBody;
    }

    /**
     * @param mixed $tabooBody
     * @return MailingList
     */
    public function setTabooBody($tabooBody)
    {
        $this->tabooBody = $tabooBody;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTabooHeaders()
    {
        return $this->tabooHeaders;
    }

    /**
     * @param mixed $tabooHeaders
     * @return MailingList
     */
    public function setTabooHeaders($tabooHeaders)
    {
        $this->tabooHeaders = $tabooHeaders;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessageFooter()
    {
        return $this->messageFooter;
    }

    /**
     * @param mixed $messageFooter
     * @return MailingList
     */
    public function setMessageFooter($messageFooter)
    {
        $this->messageFooter = $messageFooter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessageFronter()
    {
        return $this->messageFronter;
    }

    /**
     * @param mixed $messageFronter
     * @return MailingList
     */
    public function setMessageFronter($messageFronter)
    {
        $this->messageFronter = $messageFronter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessageHeaders()
    {
        return $this->messageHeaders;
    }

    /**
     * @param mixed $messageHeaders
     * @return MailingList
     */
    public function setMessageHeaders($messageHeaders)
    {
        $this->messageHeaders = $messageHeaders;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdministrivia()
    {
        return $this->administrivia;
    }

    /**
     * @param mixed $administrivia
     * @return MailingList
     */
    public function setAdministrivia($administrivia)
    {
        $this->administrivia = $administrivia;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdvertise()
    {
        return $this->advertise;
    }

    /**
     * @param mixed $advertise
     * @return MailingList
     */
    public function setAdvertise($advertise)
    {
        $this->advertise = $advertise;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnnouncements()
    {
        return $this->announcements;
    }

    /**
     * @param mixed $announcements
     * @return MailingList
     */
    public function setAnnouncements($announcements)
    {
        $this->announcements = $announcements;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArchiveDir()
    {
        return $this->archiveDir;
    }

    /**
     * @param mixed $archiveDir
     * @return MailingList
     */
    public function setArchiveDir($archiveDir)
    {
        $this->archiveDir = $archiveDir;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     * @return MailingList
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateInfo()
    {
        return $this->dateInfo;
    }

    /**
     * @param mixed $dateInfo
     * @return MailingList
     */
    public function setDateInfo($dateInfo)
    {
        $this->dateInfo = $dateInfo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateIntro()
    {
        return $this->dateIntro;
    }

    /**
     * @param mixed $dateIntro
     * @return MailingList
     */
    public function setDateIntro($dateIntro)
    {
        $this->dateIntro = $dateIntro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @param mixed $debug
     * @return MailingList
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigestArchive()
    {
        return $this->digestArchive;
    }

    /**
     * @param mixed $digestArchive
     * @return MailingList
     */
    public function setDigestArchive($digestArchive)
    {
        $this->digestArchive = $digestArchive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigestName()
    {
        return $this->digestName;
    }

    /**
     * @param mixed $digestName
     * @return MailingList
     */
    public function setDigestName($digestName)
    {
        $this->digestName = $digestName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigestRmFooter()
    {
        return $this->digestRmFooter;
    }

    /**
     * @param mixed $digestRmFooter
     * @return MailingList
     */
    public function setDigestRmFooter($digestRmFooter)
    {
        $this->digestRmFooter = $digestRmFooter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigestRmFronter()
    {
        return $this->digestRmFronter;
    }

    /**
     * @param mixed $digestRmFronter
     * @return MailingList
     */
    public function setDigestRmFronter($digestRmFronter)
    {
        $this->digestRmFronter = $digestRmFronter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigestWorkDir()
    {
        return $this->digestWorkDir;
    }

    /**
     * @param mixed $digestWorkDir
     * @return MailingList
     */
    public function setDigestWorkDir($digestWorkDir)
    {
        $this->digestWorkDir = $digestWorkDir;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGetAccess()
    {
        return $this->getAccess;
    }

    /**
     * @param mixed $getAccess
     * @return MailingList
     */
    public function setGetAccess($getAccess)
    {
        $this->getAccess = $getAccess;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndexAccess()
    {
        return $this->indexAccess;
    }

    /**
     * @param mixed $indexAccess
     * @return MailingList
     */
    public function setIndexAccess($indexAccess)
    {
        $this->indexAccess = $indexAccess;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param mixed $info
     * @return MailingList
     */
    public function setInfo($info)
    {
        $this->info = $info;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInfoAccess()
    {
        return $this->infoAccess;
    }

    /**
     * @param mixed $infoAccess
     * @return MailingList
     */
    public function setInfoAccess($infoAccess)
    {
        $this->infoAccess = $infoAccess;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIntroAccess()
    {
        return $this->introAccess;
    }

    /**
     * @param mixed $introAccess
     * @return MailingList
     */
    public function setIntroAccess($introAccess)
    {
        $this->introAccess = $introAccess;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModerate()
    {
        return $this->moderate;
    }

    /**
     * @param mixed $moderate
     * @return MailingList
     */
    public function setModerate($moderate)
    {
        $this->moderate = $moderate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMungedomain()
    {
        return $this->mungedomain;
    }

    /**
     * @param mixed $mungedomain
     * @return MailingList
     */
    public function setMungedomain($mungedomain)
    {
        $this->mungedomain = $mungedomain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNoadvertise()
    {
        return $this->noadvertise;
    }

    /**
     * @param mixed $noadvertise
     * @return MailingList
     */
    public function setNoadvertise($noadvertise)
    {
        $this->noadvertise = $noadvertise;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPurgeReceived()
    {
        return $this->purgeReceived;
    }

    /**
     * @param mixed $purgeReceived
     * @return MailingList
     */
    public function setPurgeReceived($purgeReceived)
    {
        $this->purgeReceived = $purgeReceived;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResendHost()
    {
        return $this->resendHost;
    }

    /**
     * @param mixed $resendHost
     * @return MailingList
     */
    public function setResendHost($resendHost)
    {
        $this->resendHost = $resendHost;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $sender
     * @return MailingList
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStrip()
    {
        return $this->strip;
    }

    /**
     * @param mixed $strip
     * @return MailingList
     */
    public function setStrip($strip)
    {
        $this->strip = $strip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubscribePolicy()
    {
        return $this->subscribePolicy;
    }

    /**
     * @param mixed $subscribePolicy
     * @return MailingList
     */
    public function setSubscribePolicy($subscribePolicy)
    {
        $this->subscribePolicy = $subscribePolicy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnsubscribePolicy()
    {
        return $this->unsubscribePolicy;
    }

    /**
     * @param mixed $unsubscribePolicy
     * @return MailingList
     */
    public function setUnsubscribePolicy($unsubscribePolicy)
    {
        $this->unsubscribePolicy = $unsubscribePolicy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWelcome()
    {
        return $this->welcome;
    }

    /**
     * @param mixed $welcome
     * @return MailingList
     */
    public function setWelcome($welcome)
    {
        $this->welcome = $welcome;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWhichAccess()
    {
        return $this->whichAccess;
    }

    /**
     * @param mixed $whichAccess
     * @return MailingList
     */
    public function setWhichAccess($whichAccess)
    {
        $this->whichAccess = $whichAccess;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWhoAccess()
    {
        return $this->whoAccess;
    }

    /**
     * @param mixed $whoAccess
     * @return MailingList
     */
    public function setWhoAccess($whoAccess)
    {
        $this->whoAccess = $whoAccess;
        return $this;
    }
}