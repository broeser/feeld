<?php

namespace Feeld\Interview;

class InterviewImplementation extends AbstractInterview {

    public function inviteAnswers() {
        print('What is the answer?');
    }

    public function onValidationError(\Feeld\FieldInterface $lastField = null) {
        print('Errors occurred!');
    }

    public function onValidationSuccess(\Feeld\FieldInterface $lastField = null) {
        print('The answers were valid.');
    }

    public function retrieveAnswers() {
        foreach ($this->getCurrentCollection()->getFields() as $field) {
            $field->setRawValue(42);
        }
    }

    public function execute($pageNumber = 0) {
        $this->status = InterviewInterface::STATUS_BEFORE_INTERVIEW;
        $this->inviteAnswers();
        $this->retrieveAnswers();
        $this->onValidationSuccess();
        $this->status = InterviewInterface::STATUS_AFTER_INTERVIEW;
    }

}

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-02-06 at 14:03:59.
 */
class AbstractInterviewTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var AbstractInterview
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new InterviewImplementation();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Feeld\Interview\Interview::setId
     * @todo   Implement testSetId().
     */
    public function testSetId() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Feeld\Interview\Interview::getId
     * @todo   Implement testGetId().
     */
    public function testGetId() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Feeld\Interview\Interview::getCurrentCollection
     * @todo   Implement testGetCurrentCollection().
     */
    public function testGetCurrentCollection() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Feeld\Interview\Interview::skipToCollection
     * @todo   Implement testSkipToCollection().
     */
    public function testSkipToCollection() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Feeld\Interview\Interview::nextCollection
     * @todo   Implement testNextCollection().
     */
    public function testNextCollection() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Feeld\Interview\Interview::setValidationStrategy
     * @todo   Implement testSetValidationStrategy().
     */
    public function testSetValidationStrategy() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Feeld\Interview\Interview::getStatus
     * @todo   Implement testGetStatus().
     */
    public function testGetStatus() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Feeld\Interview\Interview::execute
     * @todo   Implement testExecute().
     */
    public function testExecute() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Feeld\Interview\Interview::handleAnswers
     * @todo   Implement testHandleAnswers().
     */
    public function testHandleAnswers() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}