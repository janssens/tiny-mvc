<?php

/**
 * A parent class for Models to work with Json files
 *
 * PHP version 8
 *
 * @category SiCard_Web_Shop
 * @package  SiCard_Web_Shop
 * @author   Gaëtan Janssens <contact@plopcom.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/janssens
 */

namespace App\TinyMVC;

/**
 * A parent class for Models to work with Json files
 *
 * @category SiCard_Web_Shop
 * @package  SiCard_Web_Shop
 * @author   Gaëtan Janssens <contact@plopcom.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/janssens
 */
class JsonModel
{
    use \App\Traits\WithGetPropertyTrait;
    use \App\Traits\WithSetPropertyTrait;

    private array $_jsonData = [];

    private string $_type;

    private ?string $_id = null;

    private array $_keys = [];

    /**
     * Constructor
     * 
     * @param $type string
     * 
     * @return void
     */
    public function __construct(string $type)
    {
        if (!$type) {
            throw new \Exception("Type is null");
        }
        $this->_type = strtolower($type);
    }

    /**
     * Get object properties keys as array
     *  
     * @return array of keys
     */
    public function getKeys(): array
    {
        return $this->_keys;
    }

    public function setKeys(array $keys): void
    {
        $this->_keys = array_merge($this->_keys, $keys);
    }

    public function __tostring(): string
    {
        $r = strtoupper($this->_type).((is_null($this->_id)) ? '' : ' #'.$this->getId())."\n";
        foreach ($this->getKeys() as $key) {
            $r .= $key." => ".$this->getProperty($key)."\n";
        }
        return $r;
    }

    public function getData(): array
    {
        return $this->_jsonData;
    }

    public function getId(): string
    {
        if (is_null($this->_id)) {
            $this->_id = uniqid($this->_type);
        }
        return $this->_id;
    }

    private function getFilePath(): string
    {
        return 'data/'.$this->_type .'/'. $this->getId().'.json';
    }

    public function load(string $id): void
    {
        if (is_null($id)) {
            throw new \Exception("New object cannot be loaded");
        }

        $this->_id = $id;

        if (
            !file_exists($this->getFilePath())
                    || !is_readable($this->getFilePath())
        ) {
            throw new \Exception("Cannot find or read data [".$this->getFilePath().']');
        }

        //read json file
        $jsonString = file_get_contents($this->getFilePath());
        $this->_jsonData = json_decode($jsonString, true);

        //update property from data
        foreach ($this->getKeys() as $key) {
            if (isset($this->_jsonData[$key])) {
                $this->setProperty($key, $this->_jsonData[$key]);
            }
        }
    }

    public function save(): string
    {
        //prepare data
        foreach ($this->getKeys() as $key) {
            $this->_jsonData[$key] = $this->getProperty($key);
        }
        // Convert JSON data from an array to a string
        $jsonString = json_encode($this->_jsonData, JSON_PRETTY_PRINT);
        // Write in the file
        $fp = fopen($this->getFilePath(), 'w');
        fwrite($fp, $jsonString);
        fclose($fp);
        return $this->getId();
    }
}
