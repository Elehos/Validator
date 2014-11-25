<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 25/11/2014
 * Time: 10:40
 */

namespace Tests\Web1\Validator;

use Web1\Validator\ValidatorBoolean;

class ValidatorBooleanTest extends \PHPUnit_Framework_TestCase
{
    public function testValidatorBoolean()
    {
        $boolean = true;
        $test = ValidatorBoolean::validatorBoolean($boolean);
        $this->assertEquals($boolean,$test);
    }
} 