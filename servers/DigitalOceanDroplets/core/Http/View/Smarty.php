<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\View;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;

/**
 * Smarty Wrapper
 *
 * @author Michal Czech <michael@modulesgarden.com>
 * @SuppressWarnings(PHPMD)
 */
class Smarty
{

    private static $instance = null;
    private $smarty;
    private $templateDIR;
    private $lang;

    final private function __construct()
    {
        $this->smarty = new \Smarty();
    }

    final private function __clone()
    {
        
    }

    public function get()
    {
        if (empty(self::$instance))
        {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function setLang($land)
    {
        $this->lang = $land;
        return $this;
    }

    /**
     * Set Tempalte Dir
     *
     * @author Michal Czech <michael@modulesgarden.com>
     * @param string $dir
     */
    public function setTemplateDir($dir)
    {

        if (is_array($dir))
        {
            ServiceLocator::call('errorManager')->addError(self::class, 'Wrong Template Path : ' . $dir, ['dir' => $dir]);
        }
        $this->templateDIR = $dir;
        return $this;
    }

    /**
     * Parse Template File
     *
     * @author Michal Czech <michael@modulesgarden.com>
     * @global string $templates_compiledir
     * @param string $template
     * @param array $vars
     * @param string $customDir
     * @return string
     * @throws exceptions\System
     */
    public function view($template, $vars = [], $customDir = false)
    {

        if (is_array($customDir))
        {
            ServiceLocator::call('errorManager')->addError(self::class, 'Wrong Template Path : ' . $customDir, ['dir' => $customDir]);
            return '';
        }

        global $templates_compiledir;
        if ($customDir)
        {
            $this->smarty->template_dir = $customDir;
        }
        else
        {
            $this->smarty->template_dir = $this->templateDIR;
        }

        $this->smarty->compile_dir   = $templates_compiledir;
        $this->smarty->force_compile = 1;
        $this->smarty->caching       = 0;

        $this->clear();

        $this->smarty->assign('MGLANG', $this->lang);

        if (is_array($vars))
        {
            foreach ($vars as $key => $val)
            {
                $this->smarty->assign($key, $val);
            }
        }

        if (is_array($this->smarty->template_dir))
        {
            $file = $this->smarty->template_dir[0] . DS . $template . '.tpl';
        }
        else
        {
            $file = $this->smarty->template_dir . DS . $template . '.tpl';
        }


        if (!file_exists($file))
        {
            $errorManager = ServiceLocator::call('errorManager');
            $errorManager->addError(self::class, 'Unable to find Template: ' . $file, ['file' => $file]);
            return (string) $errorManager;
        }

        return $this->smarty->fetch($template . '.tpl', uniqid());
    }

    protected function clear()
    {
        if (method_exists($this->smarty, 'clearAllAssign'))
        {
            $this->smarty->clearAllAssign();
        }
        elseif (method_exists($this->smarty, 'clear_all_assign'))
        {
            $this->smarty->clear_all_assign();
        }
    }
    
    public function fetch($stringTemplate, $params)
    {
        global $templates_compiledir;
        $this->smarty->compile_dir   = $templates_compiledir;
        $this->smarty->force_compile = 1;
        $this->smarty->caching       = 0;
              
        foreach ($params as $key => $val)
        {
            $this->smarty->assign($key, $val);
        }

        return $this->smarty->fetch('string:'.$stringTemplate);
    }


}
