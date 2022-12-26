<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\MailPiping\Traits;

use stdClass;

/**
 * Description of ImapReader
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
trait ImapReader
{

    protected $imapHeader;
    protected $mailcharset;
    protected $date;

    protected function imapReadHeders()
    {
        $this->date            = date("j F Y");
        $this->imapHeader = imap_search($this->imap, "SINCE \"{$this->date}\"");

        return $this;
    }

    protected function checkMineType($structure, $mineType)
    {
        $primaryMimeType = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
        if ($structure->subtype)
        {
            return (($primaryMimeType[(int) $structure->type] . "/" . $structure->subtype) == $mineType);
        }
        return ("TEXT/PLAIN" == $mineType);
    }

    protected function imapClose()
    {
        imap_close($this->imap);
        return $this;
    }

    protected function readBody($mail)
    {

        $structure = imap_fetchstructure($this->imap, $mail);



        if (!($msgBody = $this->getPart($mail, "TEXT/HTML", $structure)))
        {
            $msgBody = $this->getPart($mail, "TEXT/PLAIN", $structure);
        }

        return str_replace("&nbsp;", " ", (!$msgBody ? "No message found." : $msgBody));
    }

    protected function getPart($number, $mineType, $structure = false, $partNumber = false)
    {
        if ($structure)
        {
            $charset = "";
            foreach ($structure->parameters as $param)
            {
                if (strtoupper($param->attribute) == "CHARSET")
                {
                    $charset = ($param->value == "UTF-8" ? "" : $param->value);
                }
            }

            if ($this->checkMineType($structure, $mineType))
            {
                if (!$partNumber)
                {
                    $partNumber = "1";
                }
                $text = imap_fetchbody($this->imap, $number, $partNumber);
                $text = ($structure->encoding == 3 ? imap_base64($text) : ( $structure->encoding == 4 ? imap_qprint($text) : $text));

                if ($charset && function_exists("iconv") && !$this->disableIconv)
                    $text = iconv($charset, $this->globalConfig["Charset"], $text);

                if ($charset && !$this->mailcharset)
                    $this->mailcharset = $charset;

                return $text;
            }

            if ($structure->type == 1)
            {
                while (list($index, $subStructure) = each($structure->parts))
                {
                    if ($partNumber)
                    {
                        $prefix = $partNumber . ".";
                    }

                    if ($data = $this->getPart($number, $mineType, $subStructure, $prefix . $index + 1))
                    {
                        return $data;
                    }
                }
            }
        }
        return false;
    }

    protected function readHederInfo($number)
    {
        $info    = new stdClass();

        $headers = imap_fetchheader($this->imap, $number);
        preg_match_all('/([^: ]+): (.+?(?:\r\n\s(?:.+?))*)\r\n/m', $headers, $matches);



        $outHeaders = new stdClass();
        foreach ($matches[0] as $match)
        {
            list($key, $value) = explode(':', $match);
            $outHeaders->{$key} = $value;
        }

        if ($headers = @imap_headerinfo($this->imap, $number))
        {

            $info->id    = $headers->message_id;
            if ($headersFrom = $headers->from)
            {
                $info->fromEmail = $headersFrom[0]->mailbox . "@" . $headersFrom[0]->host;
                $elements        = imap_mime_header_decode(($headersFrom[0]->personal ? $headersFrom[0]->personal : ($headersFrom[0]->mailbox . "@" . $headersFrom[0]->host)));
                $fromName        = $elements[0]->text;
                if ($elements[0]->charset && function_exists("iconv") && !$this->disableIconv && $elements[0]->charset != "default")
                {
                    $fromName = iconv($elements[0]->charset, $this->globalConfig["Charset"], $fromName);
                }
                $info->fromName = str_replace(array("<", ">", "\"", "'"), "", $fromName);
            }

            $info->to  = array();
            if ($headersTo = $headers->to)
            {
                foreach ($headersTo as $to)
                {
                    if (!strstr(($to->mailbox . "@" . $to->host), "UNEXPECTED_DATA"))
                    {
                        $info->to[] = ($to->mailbox . "@" . $to->host);
                    }
                }
            }

            $info->cc  = array();
            if ($headersCc = $headers->cc)
            {
                foreach ($headersCc as $cc)
                {
                    $info->cc[] = ($cc->mailbox . "@" . $cc->host);
                }
            }

            $info->date = ($headers->Date ? htmlspecialchars($headers->Date) : "&nbsp;");

            if ($headers->subject)
            {
                $info->subject      = imap_utf8($headers->subject);

//                $elements      = imap_mime_header_decode($headers->subject);
//                $info->subject = $elements[0]->text;
//                if ($elements[0]->charset && function_exists("iconv") && !$this->disableIconv && $elements[0]->charset != "default")
//                {
//                    $info->subject = iconv($elements[0]->charset, $this->globalConfig["Charset"], $info->subject);
//                }
            }
            else
            {
                $info->subject = "No Subject";
            }

            $info->replyTo = array();
            if (is_array($headers->reply_to))
            {
                foreach ($headers->reply_to as $replyTo)
                {
                    $info->replyTo[] = ($replyTo->mailbox . "@" . $replyTo->host);
                }
            }
        }
        else
        {
            $info = false;
        }

        $info->fullHeaders = $outHeaders;

        return $info;
    }

}
