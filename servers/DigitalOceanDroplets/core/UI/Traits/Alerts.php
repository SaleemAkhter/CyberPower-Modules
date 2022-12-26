<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Helpers\AlertTypesConstants;

/**
 * Alerts related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait Alerts
{
    protected $internalAllertMessage     = null;
    protected $internalAllertMessageType = 'info';
    protected $internalAllertMessageRaw  = false;
    protected $internalAllertSize        = 'sm';

    public function setInternalAllertMessage($message)
    {
        if (is_string($message))
        {
            $this->internalAllertMessage = $message;
        }

        return $this;
    }

    public function getInternalAllertMessage()
    {
        return $this->internalAllertMessage;
    }

    public function setInternalAllertMessageType($type)
    {
        if (in_array($type, AlertTypesConstants::getAlertTypes()))
        {
            $this->internalAllertMessageType = $type;
        }

        return $this;
    }

    public function getInternalAllertMessageType()
    {
        return $this->internalAllertMessageType;
    }

    public function setInternalAllertMessageRaw($isRaw = false)
    {
        if (is_bool($var))
        {
            $this->internalAllertMessageRaw = $isRaw;
        }

        return $this;
    }

    public function isInternalAllertMessageRaw()
    {
        return $this->internalAllertMessageRaw;
    }

    public function addInternalAllert($message = null, $type = null, $size = null, $isRaw = false)
    {
        $this->setInternalAllertMessage($message);
        $this->setInternalAllertMessageType($type);
        $this->setInternalAllertSize($size);
        $this->setInternalAllertMessageRaw($isRaw);

        return $this;
    }

    public function haveInternalAllertMessage()
    {
        return $this->internalAllertMessage !== null;
    }

    public function setInternalAllertSize($size = null)
    {
        if (in_array($size, AlertTypesConstants::getAlertSizes()))
        {
            $this->internalAllertSize = $size;
        }

        return $this;
    }

    public function getInternalAllertSize()
    {
        return $this->internalAllertSize;
    }
}
