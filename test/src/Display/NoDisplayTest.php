<?php

namespace Feeld\Display;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-24 at 16:35:41.
 */
class NoDisplayTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var NoDisplay
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new NoDisplay;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Feeld\Display\NoDisplay::__toString
     * @covers Feeld\Display\NoDisplay::informAboutStructure
     */
    public function test__toString() {
        $this->assertEmpty((string)$this->object);
        $this->assertEmpty($this->object->__toString());
        $this->object->informAboutStructure(new \Feeld\Field\Select(new \Feeld\DataType\URL()));
        $this->assertEmpty((string)$this->object);
        $this->assertEmpty($this->object->__toString());
    }
}
