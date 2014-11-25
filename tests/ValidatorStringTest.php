<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 25/11/2014
 * Time: 22:32
 */

namespace Tests\Web1\Validator;

use Web1\Validator\ValidatorString;

class ValidatorStringTest extends \PHPUnit_Framework_TestCase
{
    private static $string = ' hello ';

    public function stringLenEqualTest()
    {
        $value = mb_strlen(self::$string);
        $test = ValidatorString::stringLenEqual($value,self::$string);
        $this->assertTrue($test);
    }

    public function stringLenInfTest ()
    {
        $value = mt_rand(1,6);
        $test = ValidatorString::stringLenInf($value,self::$string);
        $this->assertTrue($test);
    }

    public function stringLenSupTest ()
    {
        $value = mt_rand(8,100);
        $test = ValidatorString::stringLenSup($value,self::$string);
        $this->assertTrue($test);
    }

    public function stringLenBetweenTest ()
    {
        $value1 = mt_rand(1,6);
        $value2 = mt_rand(8,100);
        $test = ValidatorString::stringLenBetween($value1,$value2,self::$string);
        $this->assertTrue($test);
    }

    public function stringNoSpaceStartEndTest ()
    {
        $test = ValidatorString::stringNoSpaceStartEnd(self::$string);
        $this->assertTrue($test);
    }

    public function stringSpaceInTest ()
    {
        $test = ValidatorString::stringSpaceIn(self::$string);
        $this->assertTrue($test);
    }
} 