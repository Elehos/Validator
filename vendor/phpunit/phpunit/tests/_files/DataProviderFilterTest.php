<?php
class DataProviderFilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider truthProvider
     */
    public function testTrue($truth)
    {
        $this->assertTrue($truth);
    }

    public static function truthProvider()
    {
        return array(
           array(true),
           array(true),
           array(true),
           array(true)
        );
    }

    /**
     * @dataProvider falseProvider
     */
    public function testFalse($false)
    {
        $this->assertFalse($false);
    }

    public static function falseProvider()
    {
        return array(
          'false tests'=>array(false),
          'false tests 2'=>array(false),
          'other false tests'=>array(false),
          'other false test2'=>array(false)
        );
    }
}
