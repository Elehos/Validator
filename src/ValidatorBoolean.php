<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 23/11/2014
 * Time: 23:34
 */

namespace Web1\Validator;

class ValidatorBoolean
{
    /**
     * @param $boolean
     * @return bool
     */
 public static function validatorBoolean($boolean)
 {
     return $boolean === true;
 }
}
