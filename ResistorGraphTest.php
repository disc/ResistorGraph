<?php

require_once 'ResistorGraph.php';

class ResistorGraphTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ResistorGraph
     */
    protected $instance;

    protected function setUp() {
        // square 3x3
        $graph = array(
            array(0,5,0,8,0,0,0,0,0), //0.0
            array(5,0,3,0,5,0,0,0,0), //0.1
            array(0,3,0,0,0,7,0,0,0), //0.2
            array(8,0,0,0,7,0,3,0,0), //1.0
            array(0,6,0,7,0,6,0,0,0), //1.1
            array(0,0,7,0,6,0,0,0,0), //1.2
            array(0,0,0,3,0,0,0,14,0), //2.0
            array(0,0,0,0,13,0,14,0,10), //2.1
            array(0,0,0,0,0,6,0,10,0), //2.2
        );
        $this->instance = new ResistorGraph($graph);
    }

    /**
     * @param $from
     * @param $to
     * @param $expectedValue
     * @dataProvider resistanceDataProvider
     */
    public function testGetResistanceBetweenPoints($from, $to, $expectedValue)
    {
        $this->assertEquals($expectedValue, $this->instance->getResistanceBetweenPoints($from,$to));
    }

    public function resistanceDataProvider() {
        return array(
            // from, to, expectedValue
            // 0.0 -> 0.0 = 0
            array(0, 0, 0),
            // 1.0 -> 1.0 = 0
            array(3, 3, 0),
            // 0.0 -> 0.1 = 5
            array(0, 1, 5),
            // 0.0 -> 0.2 = 8
            array(0, 2, 8),
            // 1.1 -> 1.0 = 7
            array(4, 3, 7),
            // 0.0 -> 2.2 = 35
            array(0, 8, 35),
            // 0.0 -> non-exists point = 35
            array(0, 333, 0),
        );
    }
}
