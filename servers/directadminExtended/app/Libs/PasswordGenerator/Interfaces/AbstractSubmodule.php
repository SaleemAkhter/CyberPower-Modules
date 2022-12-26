<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\PasswordGenerator\Interfaces;
/**
 *
 * Created by PhpStorm.
 * User: Tomasz Bielecki ( tomasz.bi@modulesgarden.com )
 * Date: 25.09.19
 * Time: 13:59
 * Class AbstractSubmodule
 */
class AbstractSubmodule
{
    /**
     * @var int
     */
    protected $minLength        = 6;
    /**
     * @var int
     */
    protected $maxLength        = 10;
    /**
     * @var bool
     */
    protected $requiredNumbers  = true;
    /**
     * @var bool
     */
    protected $requiredChars    = true;
    /**
     * @var bool
     */
    protected $requiredSpecial  = true;

    /**
     * @return int
     */
    public function getMinLength()
    {
        return $this->minLength;
    }

    /**
     * @param int $minLength
     */
    public function setMinLength($minLength)
    {
        $this->minLength = $minLength;
    }

    /**
     * @return int
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }

    /**
     * @param int $maxLength
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * @return bool
     */
    public function isRequiredNumbers()
    {
        return $this->requiredNumbers;
    }

    /**
     * @param bool $requiredNumbers
     */
    public function setRequiredNumbers($requiredNumbers)
    {
        $this->requiredNumbers = $requiredNumbers;
    }

    /**
     * @return bool
     */
    public function isRequiredChars()
    {
        return $this->requiredChars;
    }

    /**
     * @param bool $requiredChars
     */
    public function setRequiredChars($requiredChars)
    {
        $this->requiredChars = $requiredChars;
    }

    /**
     * @return bool
     */
    public function isRequiredSpecial()
    {
        return $this->requiredSpecial;
    }

    /**
     * @param bool $requiredSpecial
     */
    public function setRequiredSpecial($requiredSpecial)
    {
        $this->requiredSpecial = $requiredSpecial;
    }


}