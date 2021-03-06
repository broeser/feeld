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

use Feeld\FieldCollection\ValueMapper;
use Feeld\FieldCollection\ValueMapStrategy;

/**
 * Example implementation of InterviewInterface: a HTML form handler
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class HTMLForm extends AbstractTreeInterview implements \Feeld\Field\CommonProperties\IdentifierInterface {
    use \Feeld\Field\CommonProperties\Identifier;
    
    /**
     * Either \INPUT_POST, \INPUT_GET or \INPUT_REQUEST
     * 
     * @var int
     */
    protected $method;
    
    /**
     * Unique ID for this form, useful if several HTMLForms are on the same
     * page
     * 
     * @var string
     */
    protected $uniqueID;
    
    /**
     * Container to store validation errors in
     * 
     * @var ErrorContainer
     */
    protected $errorContainer;
    
    /**
     * Optional Display that success messages are displayed on
     * 
     * @var \Feeld\Display\HTML\Element
     */
    protected $successDisplay = null;
    
    const PREFIX_VALUE_MAPPER = 'valuemapper_';
    const PREFIX_FIELD_PAGE = 'page_';
    const PREFIX_FIELD_FORM = 'form_';
    
    /**
     * Constructor
     * 
     * @param mixed $id If several forms are on one page, use this id to differentiate them
     * @param \Feeld\FieldCollection\FieldCollectionInterface ...$fieldCollections
     */
    public function __construct($id = 0, ErrorContainer $errorContainer = null, \Feeld\FieldCollection\FieldCollectionInterface ...$fieldCollections) {
        parent::__construct(...$fieldCollections);
        $this->setId(self::PREFIX_FIELD_FORM.$id);
        $this->addInternalFields();
        $this->errorContainer = is_null($errorContainer)?new ErrorContainer():$errorContainer;
        $this->method = INPUT_POST;
    }
    
    /**
     * Returns the ErrorContainer
     * 
     * @return ErrorContainer
     */
    public function getErrorContainer() {
        return $this->errorContainer;
    }
    
    /**
     * Returns the Display success messages shall be displayed on
     * 
     * @return \Feeld\Display\DisplayInterface
     */
    public function getSuccessDisplay() {
        if($this->successDisplay instanceof \Feeld\Display\HTML\Element) {
            return $this->successDisplay;
        }
        
        $this->successDisplay = new \Feeld\Display\NoDisplay();
            
        return $this->successDisplay;
    }
    
    /**
     * Sets the display success messages shall be displayed on
     * 
     * @param \Feeld\Display\HTML\Element $successDisplay
     */
    public function setSuccessDisplay(\Feeld\Display\HTML\Element $successDisplay) {
        $this->successDisplay = $successDisplay;
    }
    
    /**
     * Set the HTTP method, currently INPUT_POST, INPUT_GET and INPUT_REQUEST 
     * (check both) are supported
     * 
     * @param int $method
     * @throws \Wellid\Exception\DataFormat
     */
    public function setMethod($method) {
        if(!in_array($method, array(INPUT_POST, INPUT_GET, INPUT_REQUEST))) {
            throw new \Wellid\Exception\DataFormat('method', 'post, get or request constant', $method);
        }
        
        $this->method = $method;
    }
    
    /**
     * Adds Fields for internal use:
     * - One field that specifies the unique id of the form (useful if several
     *   forms are on one page)
     * - One field that specifies the page number of the form (for multi-page
     *   forms)
     * - For multi-page-forms, each page gets all fields of the preceding page
     *   as hidden constant Fields
     */
    private function addInternalFields() {
        $i = 0;
        foreach($this->fieldCollections as $collection) {
            $collection->getDisplay()->setInvisible();
            
            $pageNumber = new \Feeld\Field\Constant(new \Feeld\DataType\Integer(), self::PREFIX_FIELD_PAGE.$this->getId(), new \Feeld\Display\HTML\Input('hidden'));
            $pageNumber->setDefault($i);
            $collection->addField($pageNumber);
            $collection->addField((new \Feeld\Field\Constant(new \Feeld\DataType\Str(new \Sanitor\Sanitizer(FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)), $this->getId(), new \Feeld\Display\HTML\Input('hidden')))->setDefault($this->getUniqueID())->addValidator(new \Wellid\Validator\MinLength(13)));
            $lastCollection = $collection;
            if($i>0) {
                $collection->addCollection($this->transformToConstants($lastCollection));
            }
            $i++;
            
            $valueMapper = new ValueMapper(
                                new HTMLFormInternalFields(),
                                ValueMapStrategy::MAP_SETTERS,
                                array(
                                    self::PREFIX_FIELD_PAGE.$this->getId() => 'PageNumber',
                                    $this->getId() => 'UID'
                                )
                           );
            
            $valueMapper->setId(self::PREFIX_VALUE_MAPPER.$this->getId());
            
            $collection->addValueMapper($valueMapper);
        }
    }
    
    /**
     * Returns an unique ID for this HTMLForm
     * 
     * @return string
     */
    protected function getUniqueID() {  
        if($this->getStatus()===InterviewInterface::STATUS_AFTER_INTERVIEW && !is_null($this->uniqueID)) {
            $answers = $this->getCurrentCollection()->getValidAnswers();
            $this->uniqueID = $answers[self::PREFIX_VALUE_MAPPER.$this->getId()]->{$this->getId()};
        } elseif(is_null($this->uniqueID)) {
            $this->uniqueID = uniqid();
        }
        
        return $this->uniqueID;
    }    
    
    /**
     * One of the INPUT_GET or INPUT_POST constants
     * 
     * @return int
     */
    public function getMethod() {
        return $this->method;
    }
    
    /**
     * Invites the user to answer the questions by displaying them
     */
    public function inviteAnswers() {
        $this->getCurrentCollection()->getDisplay()->setVisible();
        $this->getSuccessDisplay()->setInvisible();
    }

    /**
     * In case there was a validation error, let's invite the user again to
     * answer the questions
     * 
     * @param \Feeld\FieldInterface $lastField
     */
    public function onValidationError(\Feeld\FieldInterface $lastField = null) {
        $this->status = InterviewInterface::STATUS_VALIDATION_ERROR;
        $this->errorContainer->clear();
        $this->errorContainer->addSet($this->getCurrentCollection()->validate());
        $this->inviteAnswers();
    }

    /**
     * In case everything was fine, let's return a thank you note to the user
     * 
     * @param \Feeld\FieldInterface $lastField
     */
    public function onValidationSuccess(\Feeld\FieldInterface $lastField = null) {
        $this->status = InterviewInterface::STATUS_AFTER_INTERVIEW;
        $this->getUniqueID();
        $this->errorContainer->clear();
        $this->getSuccessDisplay()->setVisible();
        $this->getCurrentCollection()->getDisplay()->setInvisible();
        $this->lastAnsweredCollection = $this->currentCollectionId;
    }

    /**
     * Returns whether the method this page was accessed matches the method
     * this HTML-Form should listen to
     * 
     * @return boolean
     */
    protected function methodMatches() {
        switch($this->method) {
            case INPUT_POST:
                return strtolower($_SERVER['REQUEST_METHOD'])==='post';
            case INPUT_GET:
                return strtolower($_SERVER['REQUEST_METHOD'])==='get';
            case INPUT_REQUEST:
                return strtolower($_SERVER['REQUEST_METHOD'])==='get' || strtolower($_SERVER['REQUEST_METHOD'])==='post';
            default:
                return false;
        }
    }
    
    /**
     * Return whether the current collection matches a field that holds the page
     * number of the submitted collection
     * 
     * @return boolean
     */
    protected function pageMatches() {
        return $this->getCurrentCollection()->getFieldById(self::PREFIX_FIELD_PAGE.$this->getId())->getDataType()->transformSanitizedValue()===$this->currentCollectionId;
    }
   
    /**
     * Return whether a valid unique ID was submitted (at least 13 characters)
     * 
     * @return boolean
     */
    protected function uniqidSubmitted() {
        return strlen($this->getCurrentCollection()->getFieldById($this->getId())->getFilteredValue())>12;
    }
            
    /**
     * Retrieves answers and returns whether there are any
     * 
     * @return boolean
     */
    public function retrieveAnswers() {
        if(!$this->methodMatches() || !$this->pageMatches() || !$this->uniqidSubmitted()) {
            return false;
        }
        
        foreach($this->getCurrentCollection()->getFields() as $field) {
            if($field instanceof \Feeld\Field\CommonProperties\IdentifierInterface && $field->hasId()) {
                $field->rawValueFromInput($this->getMethod(), $field->getId());
            }
        }
        
        return true;
    }
    
    /**
     * Transforms all Fields from the current FieldCollection (current page)
     * to Constants with Hidden UIs and returns a FieldCollection containing
     * those Fields.
     * 
     * Useful for multi-page-forms that should not loose the values of the
     * pages that were already answered
     * 
     * @return \Feeld\FieldCollection\FieldCollection
     */
    private function transformToConstants(\Feeld\FieldCollection\FieldCollectionInterface $currentCollection = null) {
        if(is_null($currentCollection)) {
            $currentCollection = $this->getCurrentCollection();
        }
        
        $hiddenFields = new \Feeld\FieldCollection\FieldCollection();
        foreach($currentCollection->getFields() as $field) {
            $constant = new \Feeld\Field\Constant($field->getDataType(), $field->getId(), new \Feeld\Display\HTML\Input('hidden'));
            $constant->setDefault($field->getFilteredValue());
            foreach($field->getValidators() as $validator) {
                $constant->addValidator($validator);
            }
            $hiddenFields->addField($constant);
        }
        return $hiddenFields;
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
        
        return $this->getStatus();
    }
    
    /**
     * Filters (sanitizes) data and validates it, sets the Interview status
     * accordingly
     */
    protected function handleAnswers() {        
        if($this->getCurrentCollection()->validate()->hasPassed()) {
            $this->onValidationSuccess();
        } else {
            /*  If there is at least one validation error, let's assume that 
                all passing fields
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
        }
    }    
}
