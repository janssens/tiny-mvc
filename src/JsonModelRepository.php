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
class JsonModelRepository
{

    private string $_class;

    /**
     * Constructor
     * 
     * @param $class_name string
     * 
     * @return void
     */
    public function __construct(string $class_name)
    {
        if (!class_exists($class_name)) {
            throw new \Exception("Class ".$class_name." do not exist");
        }
        $this->_class = $class_name;
    }

    /**
     * Get objects dir
     *  
     * @return string object directory
     */
    private function _getDir(): string
    {
        return 'data/'.$this->_class::SLUG .'/';
    }

    /**
     * Find all object
     *  
     * @return array of object
     */
    public function findAll(): array
    {
        $objects = [];

        foreach (glob($this->_getDir()."*.json") as $file) {
            $new = new $this->_class();
            $objects[] = $new->load($file);
        }

        return $objects;
    }

}
