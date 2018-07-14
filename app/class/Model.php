<?php


namespace CandleLight;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Generic Model for dynamic types
 * @package CandleLight
 */
abstract class Model extends Eloquent{


    public function doValidation(): bool{
        return false;
    }

    public function getValidationMessage(): string{
        return '';
    }


}