<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jan 9, 2019)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\UI\Admin\Jobs;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Others\Label;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;
/**
 * Description of JobLabel
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class StatusLabel extends Label
{
    protected $name  = 'labelStatus';
    protected $id    = 'labelStatus';
    protected $title = 'labelStatus';
    private $status;
    
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        $this->setMessage(sl('lang')->tr($this->status))
             ->setTitle(sl('lang')->tr($this->status));
        switch ($this->status)
        {
            case "pending":
                $this->setColor('e9fff7')->setBackgroundColor('737980');
                break;
            case "in_progress":
            case "running":
                $this->setColor('7b007b')->setBackgroundColor('e9ebf0');
                break;
            case "completed":
            case "finished":
                $this->setColor('e5fff4')->setBackgroundColor('5bc758');
                break;
            case "error":
            case "failed":
            case "critical":
                $this->setColor('fcffff')->setBackgroundColor('ed4040');
                break;
            default:
                //pending
                $this->setColor('e9fff7')->setBackgroundColor('737980');
                break;
        }
        return $this;
    }


}
