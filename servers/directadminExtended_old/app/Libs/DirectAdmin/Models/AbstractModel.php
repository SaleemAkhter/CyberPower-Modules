<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 16:28
 */
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;


abstract class AbstractModel
{

    public $response = [];
    protected $action;

    public function __construct($params = [])
    {
        if ($params)
        {
            $this->fill($params);
        }
    }

    // toDo : refactor
    public function fill($data)
    {
        if (!is_array($data))
        {
            $data = get_object_vars($data);
        }


        foreach ($data as $key => $val)
        {
            if (property_exists($this, $key) === false)
            {
                if (strpos($key, '_') !== false)
                {
                    $explode = explode('_', $key);
                    $ucfirstAll = array_map('ucfirst', $explode);
                    $fixedKey = lcfirst(implode('', $ucfirstAll));
                    if (property_exists($this, $fixedKey))
                    {
                        $this->$fixedKey = $val;
                    }

                    continue;
                }

                if (strpos($key, '-') !== false)
                {
                    $explode = explode('-', $key);
                    $ucfirstAll = array_map('ucfirst', $explode);
                    $fixedKey = lcfirst(implode('', $ucfirstAll));
                    if (property_exists($this, $fixedKey))
                    {
                        $this->$fixedKey = $val;
                    }

                    continue;
                }

                continue;
            }

            $this->$key = $val;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     * @return AbstractModel
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function addResponseElement(AbstractModel $element)
    {
        $this->response[] = $element;
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function first()
    {
        return $this->response[0];
    }

    public function firstOfArray()
    {
        return $this->toArray()[0];
    }

    protected function convertToCamelCase($string, $delimiter = "_", $addPrefix = "")
    {
        $explodeString = explode($delimiter, $string);
        $newString = "";

        foreach($explodeString as $value)
        {
            if(empty($newString) && $addPrefix != "")
            {
                $newString = lcfirst($addPrefix);

            }
            elseif(empty($newString) && $addPrefix == "")
            {
                $newString = lcfirst($value);
                continue;
            }

            $newString .= ucfirst(($value));
        }

        return $newString;
    }

    public function toArray()
    {
        return $this->convertToArray($this->response);
    }

    private function convertToArray($data)
    {
        if (is_array($data) || is_object($data))
        {
            $result = [];
            foreach ($data as $key => $value)
            {
                $result[$key] = $this->convertToArray($value);
            }
            return $result;
        }

        return $data;
    }

    public function explodeMassOptions($fieldName, $prefix = 'select', $startNumber = 0)
    {
         $selectedData = [];

         $explodeData = explode(',', $this->{$fieldName});

         foreach ($explodeData as $data)
         {
             $selectedData[$prefix . $startNumber] = $data;

             $startNumber++;
         }

         return $selectedData;

    }

    protected function fillFromJson($response)
    {
        if(is_object($response))
        {
            foreach($response as $key => $value)
            {
                $method = $this->convertToCamelCase($key, "_", "set");

                if(method_exists($this, $method))
                {
                    //dropdown methods
                    if(is_object($value))
                    {
                        $value = $this->getValueFromDropdown($value);
                    }

                    $this->{$method}($value);
                }
            }
        }
    }

    protected function getValueFromDropdown($response)
    {
        foreach($response as $line)
        {
            if(property_exists($line, 'selected') && $line->selected == "yes")
            {
                return $line->value;
            }
            if(property_exists($line, 'netmask'))
            {
                return $response;
            }
        }

        $first = reset($response);

        if(property_exists($first, 'value'))
        {
            return $first->value;
        }

        return "";
    }

}
