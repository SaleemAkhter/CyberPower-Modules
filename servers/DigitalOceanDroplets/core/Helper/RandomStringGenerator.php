<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;

/**
 * Helper for generating random strings
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class RandomStringGenerator
{
    protected $stringLength = 10;
    protected $charSet      = '0123456789qwertyuioplkjhgfdsazxcvbnm';

    public function __construct($stringLength = null)
    {
        $this->setLength($stringLength);
    }

    public function setLength($stringLength)
    {
        if ((int) $stringLength > 0)
        {
            $this->stringLength = (int) $stringLength;
        }
    }

    public function genRandomString($const = '')
    {
        $randString = '';
        while (strlen($randString) < $this->stringLength)
        {
            $number     = rand(0, strlen($this->charSet) - 1);
            $randString .= $this->charSet[$number];
        }

        if (is_string($const))
        {
            $randString = $const . '_' . $randString;
        }

        return $randString;
    }
}
