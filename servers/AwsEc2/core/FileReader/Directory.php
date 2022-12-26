<?php

namespace ModulesGarden\Servers\AwsEc2\Core\FileReader;

/**
 * Description of Directory
 *
 * @author INBSX-37H
 */
class Directory extends PathValidator
{
    public function getFilesList($path, $extension = null, $trimExtensions = false)
    {
        if (!$this->pathExists($path) || !$this->isPathReadable($path))
        {
            return [];
        }

        $list = [];
        $files = scandir($path, 1);
        if (!$files)
        {
            return [];
        }

        foreach ($files as $key => $value)
        {
            //remove dots and a files with unwanted extensions
            if ($value === "." || $value === ".." || 
                    (is_string($extension) && $extension !=='' && !(stripos($value, '.php') > 0)))
            {
                unset($files[$key]);
                continue;
            }

            $list[] = $trimExtensions ? str_replace($extension, '', $value) : $value;
        }

        return $list;
    }
}
