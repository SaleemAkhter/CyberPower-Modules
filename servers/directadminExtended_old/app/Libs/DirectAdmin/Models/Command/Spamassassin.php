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

class Spamassassin extends AbstractModel implements ResponseLoad
{
    public $domain;
    protected $blackList;
    protected $whiteList;
    protected $highScore;
    protected $highScoreBlock;
    protected $isOn;
    protected $reportSafe;
    protected $requiredHits;
    protected $requiredScore;
    protected $requiredScoreCustom;
    protected $rewriteSubject;
    protected $rewriteHeader;
    protected $subjectTag;
    protected $where;

    public function loadResponse($response, $function = null)
    {
        foreach($response as $key => $value){
            if(strpos($key, 'blacklist_from') !== false){
                $this->setBlackList($value);
                continue;
            }

            if(strpos($key, 'whitelist_from') !== false){
                $this->setWhiteList($value);
                continue;
            }

            $method =$this->convertToCamelCase($key, "_", "set");
            if(method_exists($this, $method)){
                $this->{$method}($value);
            }
        }
        $this->addResponseElement($this);

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
     * @return Email
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWhiteList()
    {
        return $this->whiteList;
    }

    /**
     * @param mixed $whiteList
     * @return Spamassassin
     */
    public function setWhiteList($whiteList)
    {
        $this->whiteList[] = $whiteList;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlackList()
    {
        return $this->blackList;
    }

    /**
     * @param mixed $blackList
     * @return Spamassassin
     */
    public function setBlackList($blackList)
    {
        $this->blackList[] = $blackList;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHighScore()
    {
        return $this->highScore;
    }

    /**
     * @param mixed $highScore
     * @return Spamassassin
     */
    public function setHighScore($highScore)
    {
        $this->highScore = $highScore;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHighScoreBlock()
    {
        return $this->highScoreBlock;
    }

    /**
     * @param mixed $highScoreBlock
     * @return Spamassassin
     */
    public function setHighScoreBlock($highScoreBlock)
    {
        $this->highScoreBlock = $highScoreBlock;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getisOn()
    {
        return $this->isOn;
    }

    /**
     * @param mixed $isOn
     * @return Spamassassin
     */
    public function setIsOn($isOn)
    {
        $this->isOn = $isOn;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReportSafe()
    {
        return $this->reportSafe;
    }

    /**
     * @param mixed $reportSafe
     * @return Spamassassin
     */
    public function setReportSafe($reportSafe)
    {
        $this->reportSafe = $reportSafe;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequiredHits()
    {
        return $this->requiredHits;
    }

    /**
     * @param mixed $requiredHits
     * @return Spamassassin
     */
    public function setRequiredHits($requiredHits)
    {
        $this->requiredHits = $requiredHits;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequiredScore()
    {
        return $this->requiredScore;
    }

    /**
     * @param mixed $requiredScore
     * @return Spamassassin
     */
    public function setRequiredScore($requiredScore)
    {
        $this->requiredScore = $requiredScore;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRewriteSubject()
    {
        return $this->rewriteSubject;
    }

    /**
     * @param mixed $rewriteSubject
     * @return Spamassassin
     */
    public function setRewriteSubject($rewriteSubject)
    {
        $this->rewriteSubject = $rewriteSubject;
        return $this;
    }

    /**
     * @return mixed
     */

    public function getSubjectTagFromHeader(){
        if(strpos($this->rewriteHeader, 'subject') !== false){

            return str_replace('subject ', '', $this->rewriteHeader);
        }

        return $this->subjectTag;

    }
    public function getSubjectTag()
    {
        return $this->subjectTag;
    }

    /**
     * @param mixed $subjectTag
     * @return Spamassassin
     */
    public function setSubjectTag($subjectTag)
    {
        $this->subjectTag = $subjectTag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @param mixed $where
     * @return Spamassassin
     */
    public function setWhere($where)
    {
        $this->where = $where;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRewriteHeader()
    {
        return $this->rewriteHeader;
    }

    /**
     * @param mixed $rewriteHeader
     * @return Spamassassin
     */
    public function setRewriteHeader($rewriteHeader)
    {
        $this->rewriteHeader = $rewriteHeader;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequiredScoreCustom()
    {
        return $this->requiredScoreCustom;
    }

    /**
     * @param mixed $requiredScoreCustom
     * @return Spamassassin
     */
    public function setRequiredScoreCustom($requiredScoreCustom)
    {
        $this->requiredScoreCustom = $requiredScoreCustom;
        return $this;
    }


    public function getAPIData(){

        $returnData = [
            'action'            => 'save',
            'domain'            => $this->getDomain(),
            'report_safe'       => $this->getReportSafe(),
            'high_score'        => $this->getHighScore(),
            'high_score_block'  => $this->getHighScoreBlock(),
            'is_on'             => 'yes',
            'where'             => $this->getWhere(),
            'rewrite_header'    => (!empty($this->getRewriteHeader()))? $this->getRewriteHeader() : 'no',
            'rewrite_subject'   => $this->getRewriteSubject(),
            'subject_tag'       => $this->getSubjectTag(),
            'required_hits'     => $this->getRequiredScore(),
            'blacklist_from'    => implode(PHP_EOL, $this->getBlackList()),
            'whitelist_from'    => implode(PHP_EOL, $this->getWhiteList())
        ];

        if($this->getRequiredScore() == "custom"){
            $returnData['required_hits_custom'] = $this->getRequiredScoreCustom();
        }


        if(empty($returnData['blacklist_from'])){
            $returnData['blacklist_from'] = " ";
        }
        if(empty($returnData['whitelist_from'])){
            $returnData['whitelist_from'] = " ";
        }

        return $returnData;
    }

    protected function formatList($name, $list){
        $startNumber = 0;
        $newList = [];

        foreach($list as $element){
            $newList[$name.$startNumber++] = $element;
        }


        return $newList;



    }




}