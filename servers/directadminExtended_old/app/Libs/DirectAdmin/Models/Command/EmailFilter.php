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

class EmailFilter extends AbstractModel implements ResponseLoad
{
    public $id;
    public $domain;
    public $type;
    public $value;
    protected $action;
    protected $adult;
    protected $high_score;
    protected $high_score_block;

    public function loadResponse($response, $function = null)
    {
        foreach ($response as $id => $data)
        {
            if (!is_numeric($id))
            {
                if ($id == 'action')
                {
                    $this->action = end(explode('=', $data));
                    continue;
                }
                $this->$id = $data;
                continue;
            }
            parse_str($data, $dataArray);
            $self = array_merge($dataArray, ['id' => $id]);
            $this->addResponseElement(new self($self));
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return EmailFilter
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return EmailFilter
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
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
     * @return EmailFilter
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return EmailFilter
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdult()
    {
        return $this->adult;
    }

    /**
     * @param mixed $adult
     * @return EmailFilter
     */
    public function setAdult($adult)
    {
        $this->adult = $adult;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHighScore()
    {
        return $this->high_score;
    }

    /**
     * @param mixed $high_score
     * @return EmailFilter
     */
    public function setHighScore($high_score)
    {
        $this->high_score = $high_score;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHighScoreBlock()
    {
        return $this->high_score_block;
    }

    /**
     * @param mixed $high_score_block
     * @return EmailFilter
     */
    public function setHighScoreBlock($high_score_block)
    {
        $this->high_score_block = $high_score_block;
        return $this;
    }

}