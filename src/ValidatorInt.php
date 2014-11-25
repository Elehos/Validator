<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 23/11/2014
 * Time: 22:35
 */

namespace Web1\Validator;

class ValidatorInt
{
    public static function intEqual ($a,$b)
    {
        return $a == $b;
    }
    public static function intInf ($a,$b)
    {
        return $a < $b;
    }
    public static function intSup ($a,$b)
    {
        return $a > $b;
    }
    public static function intBetween ($number,$value1,$value2)
    {
       return $number >= $value1 && $number <= $value2;
    }
    public static function intNegative ($negative)
    {
        return $negative <= 0;
    }
    public static function intPositive ($positive)
    {
        return $positive >= 0;
    }
}
