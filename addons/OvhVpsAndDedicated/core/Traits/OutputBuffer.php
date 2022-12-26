<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\Traits;

trait OutputBuffer
{
    /**
     * cleans an output buffer before sending the response form server
     */
    protected function cleanOutputBuffer()
    {
        $outputBuffering = \ob_get_contents();
        if($outputBuffering !== FALSE)
        {
            if(!empty($outputBuffering))
            {
                \ob_clean();
            }
            else
            {
                \ob_start();
            }
        }

        return $this;
    }
}
