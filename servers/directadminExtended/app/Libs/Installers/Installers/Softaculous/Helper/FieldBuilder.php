<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Softaculous\Helper;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\PasswordGenerator\Generator;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\PasswordGenerator\Submodules\DirectAdmin;

/**
 * Description of FieldBuilder
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class FieldBuilder
{

    public function buildFields($softname)
    {
        $filename = dirname(dirname(__FILE__)) . DS . 'Fields' . DS . $softname . DS . 'install.xml';
        if (file_exists($filename))
        {
            $out = [];
            $xml = simplexml_load_file($filename);
            foreach ($xml->settings->children() as $ch)
            {
                foreach ($ch->children() as $ftype => $field)
                {
                    if ($ftype == 'input')
                    {
                        $field_attr = $field->attributes();
                        switch ((string) $field_attr->type)
                        {
                            case 'text':
                                $out['fields'][] = $this->text($field_attr);
                                break;

                            case 'textarea':
                                $textarea_attr   = $field->textarea->attributes();
                                $out['fields'][] = $this->textarea($textarea_attr);
                                break;

                            case 'select':
                                $this->select($field, $out);
                                break;

                            case 'checkbox':
                                $out['fields'][] = $this->checkbox($field_attr);
                                break;
                        }
                    }
                }
            }
            $locked_fields = [
                'datadir'
            ];
            foreach ($xml->children() as $ch_k => $ch_v)
            {
                if (in_array($ch_k, $allowed_fields) && count($ch_v) == 0)
                {
                    $fieldAttr        = new \stdClass();
                    $fieldAttr->name  = $ch_k;
                    $fieldAttr->value = $ch_v;
                    $out['fields'][]  = $this->text($fieldAttr);
                }
            }

            if (isset($xml->cron))
            {
                $this->cronFields($xml->cron->children(), $out['fields']);
            }

            return $out['fields'];
        }
        else
        {
            return [];
        }
    }

    public function text($attributes)
    {
        $attribut = (string) $attributes->value;
        /**
         * set random password
         */
        if((string) $attributes->name === 'admin_pass')
        {
            $attribut = Generator::generate(new DirectAdmin());
        }

        return [
            'type'  => 'text',
            'label' => ucwords(str_replace('_', ' ', (string) $attributes->name)),
            'name'  => (string) $attributes->name,
            'value' => $attribut
        ];
    }

    public function textarea($attributes)
    {
        return [
            'type'  => 'textarea',
            'label' => ucwords(str_replace('_', ' ', (string) $attributes->name)),
            'name'  => (string) $attributes->name,
            'cols'  => (string) $attributes->cols,
            'rows'  => (string) $attributes->rows
        ];
    }

    public function checkbox($attributes)
    {
        return [
            'type'  => 'checkbox',
            'label' => ucwords(str_replace('_', ' ', (string) $attributes->name)),
            'name'  => (string) $attributes->name,
            'value' => (string) $attributes->value
        ];
    }

    public function select($field,&$out)
    {
        foreach ($field->children() as $k1 => $v1)
        {
            if ($k1 == 'select')
            {
                foreach ($v1->attributes() as $ak => $av)
                {
                    if ($ak == 'name')
                        $name = (string) current($av[0]);
                }
                $option = array();
                foreach ($v1->children() as $k2 => $v2)
                {
                    foreach ($v2->attributes() as $ak2 => $av2)
                    {
                        if ($ak2 == 'value')
                        {
                            $value = (string) current($av2[0]);
                        }
                    }
                    $text            = (string) $v2;
                    $options[$value] = $text;
                }
                if (!empty($name))
                {
                    $out['fields'][] = array(
                        'type'    => 'select',
                        'label'   => ucwords(str_replace('_', ' ', $name)),
                        'name'    => $name,
                        'options' => $options
                    );
                }
            }
        }
    }

    public function cronFields($children, &$out)
    {
        foreach ($children as $k1 => $v1)
        {
            if ($v1->getName() != 'command')
            {
                $values['cron_' . $v1->getName()] = (string) $v1;
            }
        }

        foreach ($values as $name => $val)
        {
            $labelArr         = explode("_", $name);
            $fieldAttr        = new \stdClass();
            $fieldAttr->name  = $labelArr[0] . ' ' . $labelArr[1];
            $fieldAttr->value = null;
            $out[]            = $this->text($fieldAttr);
        }
    }
    
    public static function getBasicFields()
    {
        $domain   = [
            'type'  => 'text',
            'label' => 'directory',
            'name'  => 'directory',
            'value' => ''
        ];
        $database = [
            'type'  => 'text',
            'label' => 'softdb',
            'name'  => 'softdb',
            'value' => ''
        ];

        return [$domain,$database];
    }

    public static function getStandardFields()
    {
        $lang     = [
            'type'  => 'text',
            'label' => 'language',
            'name'  => 'language',
            'value' => 'en'
        ];
        
        $dbPrefix = [
            'type'  => 'text',
            'label' => 'dbprefix',
            'name'  => 'dbprefix',
            'value' => 'Database Prefix'
        ];

        $siteName = [
            'type'  => 'text',
            'label' => 'site_name',
            'name'  => 'site_name',
            'value' => 'My Site'
        ];
        
        $siteDesc = [
            'type'  => 'text',
            'label' => 'site_desc',
            'name'  => 'site_desc',
            'value' => 'My Site'
        ];
        
        $adminFname = [
            'type'  => 'text',
            'label' => 'admin_fname',
            'name'  => 'admin_fname',
            'value' => 'admin'
        ];
        
        $adminLname = [
            'type'  => 'text',
            'label' => 'admin_lname',
            'name'  => 'admin_lname',
            'value' => 'admin'
        ];

        $adminRname = [
            'type'  => 'text',
            'label' => 'admin_realname',
            'name'  => 'admin_realname',
            'value' => 'admin'
        ];

        $adminUsername = [
            'type'  => 'text',
            'label' => 'admin_username',
            'name'  => 'admin_username',
            'value' => 'admin'
        ];

        $adminPassword = [
            'type'  => 'text',
            'label' => 'admin_pass',
            'name'  => 'admin_pass',
            'value' => 'admin'
        ];   
        
        $adminEmail = [
            'type'  => 'text',
            'label' => 'admin_email',
            'name'  => 'admin_email',
            'value' => 'admin'
        ];     
        
        return [$lang, $dbPrefix,$siteName,$siteDesc,$adminFname,$adminLname,$adminRname,$adminUsername,$adminPassword,$adminEmail];
    }    
}
