<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 23/11/2014
 * Time: 23:34
 */

namespace Web1\Validator;

class ValidatorString
{
    /**
     * @param $value
     * @param $string
     * @return bool
     */
    public static function stringLenEqual ($value,$string)
    {
        return $value == mb_strlen($string);
    }

    /**
     * @param $value
     * @param $string
     * @return bool
     */
    public static function stringLenInf ($value,$string)
    {
        return $value > mb_strlen($string);
    }

    /**
     * @param $value
     * @param $string
     * @return bool
     */
    public static function stringLenSup ($value,$string)
    {
        return $value < mb_strlen($string);
    }

    /**
     * @param $string
     * @param $value1
     * @param $value2
     * @return bool
     */
    public static function stringLenBetween ($string, $value1,$value2)
    {
        return mb_strlen($string) >= $value1 && mb_strlen($string) <= $value2;
    }

    /**
     * @param $string
     * @return bool
     */
    public static function stringNoSpaceStartEnd ($string)
    {
        return strrpos($string, ' ', 0) === false && strrpos($string, ' ', mb_strlen($string) + 1) == false;
    }
}
