<?php

namespace App\Traits;

use Exception;
use Throwable;

trait WithSetPropertyTrait
{
    // Dynamic property setter method
    public function setProperty(string $name, $value): self
    {
        // Property existence validation
        if (!property_exists($this, $name)) {
            // Exception handling
            throw new Exception(
                __('Property: :property does not exist @class: :class, @function: :function, @line: :line', [
                    'property' => $name,
                    'class' => get_called_class(),
                    'function' => __FUNCTION__,
                    'line' => __LINE__,
                ])
            );
        }

        try {
            // Assign value to property
            $this->{$name} = $value;
            return $this;
        } catch (Throwable $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}