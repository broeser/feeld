<?php

/*
 * The MIT License
 *
 * Copyright 2016 Benedict Roeser <b-roeser@gmx.net>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Feeld\Interview;
use Wellid\Exception\NotFound;
use Wellid\Exception\DataType;
use Feeld\FieldCollection\FieldCollectionInterface;

/**
 * Wraps at least one FieldCollection and handles asking the questions within
 * the collection, retrieving the answers, validating the answers and returning
 * appropriate status codes
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
abstract class Interview implements InterviewInterface{
    /**
     * Validation is disabled for this Interview
     * 
     * This is not recommended. It may however be useful in conjunction with
     * frameworks that use their own validation techniques.
     * 
     * (currently not supported)
     */
    // const VALIDATE_NEVER = 0;
    
    /**
     * After one question is answered, the answer is validated immediately
     */
    const VALIDATE_PER_FIELD = 1;
    
    /**
     * After all questions are answered, all answers are validated (default)
     */
    const VALIDATE_PER_COLLECTION = 2;
    
    /**
     * Validation strategy, one of the constants above
     * 
     * @var int
     */
    protected $validationStrategy;
    
    /**
     * The user did not answer any questions
     */
    const STATUS_BEFORE_INTERVIEW = 0;
    
    /**
     * The user answered all questions
     */
    const STATUS_AFTER_INTERVIEW = 1;
    
    /**
     * The user answered at least one question, but at least one answer was
     * invalid
     */
    const STATUS_VALIDATION_ERROR = 2;
    
    /**
     * There was a recoverable error that has nothing to do with validating one
     * single answer; e.g. there is a time limit for answering all questions
     * and the user did not answer them within the limit
     */
    const STATUS_RECOVERABLE_ERROR = 4;
    
    /**
     * Interview status, one of the constants above
     * 
     * @var int
     */
    protected $status;
    
    /**
     * FieldCollections assigned to this Interview
     * Assigning more then one FieldCollection may be useful for Interviews
     * with several pages, especially if there are conditional skips
     * e.g. "Are you between the age of 20 and 30?", if yes skip to 
     * FieldCollection 2, otherwise skip to FieldCollection 3
     * 
     * @var FieldCollectionInterface[]
     */
    protected $fieldCollections = array();
    
    /**
     * The FieldCollection whose fields are currently used in the interview
     * "page number"
     * 
     * @var int
     */
    protected $currentCollectionId = 0;
    
    /**
     * The number of the FieldCollection that has been answered last
     * NULL if no FieldCollection has been answered yet; Will only be set, if
     * ALL questions of a FieldCollection have been answered in a valid way
     * 
     * @var int
     */
    protected $lastAnsweredCollection = null;
    
    /**
     * Identifier of this Interview
     * Useful for handling several Interviews
     * 
     * @var mixed
     */
    protected $id = 'interview0';    
    
    /**
     * Constructor
     * 
     * @param int $validationStrategy
     * @param \Feeld\FieldCollection\FieldCollectionInterface ...$fieldCollections
     * @throws \Wellid\Exception\DataType
     */
    public function __construct($validationStrategy = self::VALIDATE_PER_COLLECTION, FieldCollectionInterface ...$fieldCollections) {
        $this->status = self::STATUS_BEFORE_INTERVIEW;
        $this->fieldCollections = $fieldCollections;
        $this->setValidationStrategy($validationStrategy);
    }
       
    /**
     * Sets the identifier of this Interview
     * Useful for handling several Interviews
     * 
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Gets the identifier of this Interview
     * Useful for handling several Interviews
     * 
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Returns the currently active FieldCollection
     * 
     * @return FieldCollectionInterface
     */
    public function getCurrentCollection() {
        return $this->fieldCollections[$this->currentCollectionId];
    }
    
    /**
     * Skips to another FieldCollection
     * 
     * @param int $pageNumber
     * @throws DataType
     * @throws NotFound
     */
    public function skipToCollection($pageNumber) {
        if(!is_int($pageNumber)) {
            throw new DataType('pageNumber', 'int', $pageNumber);
        }
        
        if(!isset($this->fieldCollections[$pageNumber])) {
            throw new NotFound($pageNumber, 'fieldCollections');
        }
        
        $this->currentCollectionId = $pageNumber;
    }
    
    /**
     * Skips to the next FieldCollection
     */
    public function nextCollection() {
        $this->skipToCollection($this->currentCollectionId+1);
    }
    
    /**
     * Sets a validation strategy
     * 
     * @param int $validationStrategy
     */
    public function setValidationStrategy($validationStrategy) {
        if(!is_int($validationStrategy)) {
            throw new \Wellid\Exception\DataType('validationStrategy', 'integer', $validationStrategy);
        }
        
        $this->validationStrategy = $validationStrategy;
    }
    
    /**
     * Returns the current status of the Interview
     * One of the STATUS_-constants
     * 
     * @return int
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * Executes the Interview in the following manner:
     * - If at least one answer was given, the answer(s) are validated
     * - If no answers were given, the user is invited to answer the question(s),
     *   e.g. by displaying them to the user
     * - In either case the current status of the Interview is returned
     * 
     * @param int $pageNumber Set the number of the page you want to execute for
     *  multi-page-forms; NOTE: first page is number 0
     * @return int
     */
    public function execute($pageNumber = 0) {
        $this->skipToCollection($pageNumber);
        
        if($this->retrieveAnswers()) {
            $this->handleAnswers();
        } else {
            $this->inviteAnswers();
        }
        
        return $this->status;
    }
    
    /**
     * Filters (sanitizes) data and validates it, sets the Interview status
     * accordingly and returns it
     * 
     * @return int
     */
    public function handleAnswers() {        
        if($this->validationStrategy===self::VALIDATE_PER_COLLECTION) {
            if($this->getCurrentCollection()->validate()->hasPassed()) {
                $this->onValidationSuccess();
                $this->status = self::STATUS_AFTER_INTERVIEW;                
            } else {
                /*  If there is at least one validation error and validation
                    is done per collection, let's assume that all passing fields
                    that can have a default value should use the passing value
                    that has just been entered as new default value
                 */
                foreach($this->getCurrentCollection()->getFields() as $field) {
                    if(!$field instanceof \Feeld\Field\CommonProperties\DefaultValueInterface || !$field->validateBool() || $field instanceof \Feeld\Field\CloakedEntry) {
                        continue;
                    }
                    
                    $field->setDefault($field->getFilteredValue());
                }
                $this->onValidationError();
                $this->status = self::STATUS_VALIDATION_ERROR;
                return $this->status;
            }
        }
        
        if($this->validationStrategy===self::VALIDATE_PER_FIELD) {
            foreach($this->getCurrentCollection()->getFields() as $field) {
                if(!$field->validateBool()) {
                    $this->onValidationError($field);
                    $this->status = self::STATUS_VALIDATION_ERROR;
                    return $this->status;
                } else {
                    $this->onValidationSuccess($field);
                }
            }
        }
        
        $this->status = self::STATUS_AFTER_INTERVIEW;
        $this->lastAnsweredCollection = $this->currentCollectionId;
        return $this->status;
    }
}
