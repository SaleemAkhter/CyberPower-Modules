<?php

namespace ModulesGarden\WordpressManager\Core\Hook;

/**
 * a base class for every hook integration controller
 */
abstract class AbstractHookIntegrationController
{
    /** @var string
     * allowed integration type
     */
    const TYPE_APPEND = 'append';

    /** @var string
     * allowed integration type
     */
    const TYPE_PREPEND = 'prepend';

    /** @var string
     * allowed integration type
     */
    const TYPE_REPLACE = 'replace';

    /** @var string
     * allowed integration type
     */
    const TYPE_CUSTOM = 'custom';

    /** @var string
     * allowed integration type
     */
    const TYPE_AFTER = 'after';

    /** @var string
     * allowed integration type
     */
    const TYPE_BEFORE = 'before';

    /** @var null|string
     *  determines the file name to be integrated which
     *  if null, will not integrate
     */
    protected $fileName = null;

    /** @var null|array
     *  determines the $_REQUEST params for which the integration should be done
     *  if null, this condition will be skipped
     */
    protected $requestParams = null;

    /** @var null|callable
     * a callback for the admin area controller
     */
    protected $controllerCallback = null;

    /** @var null|string
     *  a jQuery selector determines a DOM object to which the Vue App should be added
     * e.g '#exampleDiv', '.btn-container:first'
     */
    protected $jqSelector = null;

    /** @var string
     * states what type of integration should be used
     */
    protected $integrationType = self::TYPE_APPEND;

    /** @var null|string
     * a js function name, just for custom integration type
     */
    protected $jsFunctionName = null;

    /**
     * @param null|string $fileName
     */
    public function setFileName($fileName)
    {
        if ((is_string($fileName) && $fileName !== '') || $fileName === null)
        {
            $this->fileName = $fileName;
        }

        return $this;
    }

    /**
     * @param null|array $requestParams
     */
    public function setRequestParams($requestParams = null)
    {
        if (is_array($requestParams) || $requestParams === null)
        {
            $this->requestParams = $requestParams;
        }

        return $this;
    }

    /**
     * @param callable|null $controllerCallback
     */
    public function setControllerCallback($controllerCallback)
    {
        $this->controllerCallback = $controllerCallback;
    }

    /**
     * @param null|string $jqSelector
     */
    public function setJqSelector($jqSelector)
    {
        if ((is_string($jqSelector) && $jqSelector !== '') || $jqSelector === null)
        {
            $this->jqSelector = $jqSelector;
        }
    }

    /**
     * @param string $type
     * @return self::class
     */
    public function setIntegrationType($type = null)
    {
        if (in_array($type, $this->getAvailableIntegrationTypes()))
        {
            $this->integrationType = $type;
        }

        return $this;
    }

    /**
     * @param null|string $jsFunctionName
     */
    public function setJsFunctionName ($jsFunctionName)
    {
        if ($jsFunctionName !== '' && is_string($jsFunctionName))
        {
            $this->jsFunctionName = $jsFunctionName;
        }

        return $this;
    }

    /**
     * @return null|string
     */
    public function getJsFunctionName ()
    {
        return $this->jsFunctionName;
    }

    /**
     * @return null|string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return null|array
     */
    public function getRequestParams()
    {
        return $this->requestParams;
    }

    /**
     * @return callable|null
     */
    public function getControllerCallback()
    {
        return $this->controllerCallback;
    }

    /**
     * @return null|string
     */
    public function getJqSelector()
    {
        return $this->jqSelector;
    }

    /**
     * @return string
     */
    public function getIntegrationType()
    {
        return $this->integrationType;
    }

    /**
     *  @return array
     *  returns a list of integration types possible to use
     */
    public function getAvailableIntegrationTypes()
    {
        return [
            self::TYPE_APPEND,
            self::TYPE_PREPEND,
            self::TYPE_REPLACE,
            self::TYPE_CUSTOM,
            self::TYPE_AFTER,
            self::TYPE_BEFORE
        ];
    }

    /**
     * a wrapper to set up integration process quickly
     */
    public function addIntegration($fileName = null, $requestParams = null, $controllerCallback = null, $jqSelector = null, $integrationType = null, $jsFunctionName = null)
    {
        $this->setFileName($fileName);
        $this->setRequestParams($requestParams);
        $this->setControllerCallback($controllerCallback);
        $this->setJqSelector($jqSelector);
        $this->setIntegrationType($integrationType);
        $this->setJsFunctionName($jsFunctionName);
    }
}
