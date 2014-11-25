<?php
class Issue433Test extends PHPUnit_Framework_TestCase
{
    public function testOutputWithExpectationBefore()
    {
        $this->expectOutputString('tests');
        print 'tests';
    }

    public function testOutputWithExpectationAfter()
    {
        print 'tests';
        $this->expectOutputString('tests');
    }

    public function testNotMatchingOutput()
    {
        print 'bar';
        $this->expectOutputString('foo');
    }
}
