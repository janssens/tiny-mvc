<?php

namespace App\Traits;

use Exception;
use Throwable;

trait WithGetPropertyTrait
{
    /**
     * Dynamically returns the value of the given property
     *
     * @param  string  $name
     * @return mixed
     * @throws Exception
     */
    public function getProperty(string $name)
    {
        try {
            if ($name == 'id'){
                return $this->getId();
            }
            return $this->{$name};
        } catch (Throwable $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}