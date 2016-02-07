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

/**
 * Example implementation of InterviewInterface: a HTML form handler
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class HTMLForm extends Interview {
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
    
    const PREFIX_FIELD_PAGE = 'page_';
    const PREFIX_FIELD_FORM = 'form_';
    
    /**
     * Constructor
     * 
     * @param mixed $id If several forms are on one page, use this id to differentiate them
     * @param \Feeld\FieldCollection\FieldCollectionInterface ...$fieldCollections
     */
    public function __construct($id = 0, \Feeld\FieldCollection\FieldCollectionInterface ...$fieldCollections) {
        $this->addInternalFields();
        parent::__construct(parent::VALIDATE_PER_COLLECTION, ...$fieldCollections);
        $this->method = INPUT_POST;
        $this->setId(self::PREFIX_FIELD_FORM.$id);
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
            $pageNumber = new \Feeld\Field\Constant(new \Feeld\DataType\Integer(), self::PREFIX_FIELD_PAGE.$this->getId(), new \Feeld\Display\HTML\Input('hidden'));
            $pageNumber->setDefault($i);
            $collection->addField($pageNumber);
            $collection->addField((new \Feeld\Field\Constant(new \Feeld\DataType\Str(new \Sanitor\Sanitizer(FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)), $this->getId(), new \Feeld\Display\HTML\Input('hidden')))->setDefault($this->getUniqueID()));
            $lastCollection = $collection;
            if($i>0) {
                $collection->addCollection($this->transformToConstants($lastCollection));
            }
            $i++;
        }      
    }
    
    /**
     * Returns an unique ID for this HTMLForm
     * 
     * @return string
     */
    protected function getUniqueID() {  
        if($this->getStatus()===self::STATUS_AFTER_INTERVIEW && !is_null($this->uniqueID())) {
            $answers = $this->getCurrentCollection()->getValidAnswers();
            $key = $this->getId();
            $this->uniqueID = $answers->$key;
        } elseif(is_null($this->uniqueID())) {
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
        print($this->getCurrentCollection());
    }

    /**
     * In case there was a validation error, let's invite the user again to
     * answer the questions
     * 
     * @param \Feeld\FieldInterface $lastField
     */
    public function onValidationError(\Feeld\FieldInterface $lastField = null) {
        $this->listErrors();
        $this->inviteAnswers();
    }
    
    /**
     * Example.
     * 
     * Lists validation errors
     * You should probably override this method
     */
    public function listErrors() {
        $errMessageContainer = new \Feeld\Display\HTML\Element('ul');
        $errMessage = array();
        foreach($this->getCurrentCollection()->validate()->getErrorMessages() as $message) {
            $errMessage[] = (new \Feeld\Display\HTML\Element('li'))->setContent($message);
        }
        $errMessageContainer->setContent(implode('', $errMessage));
        $errMessageIntro = (new \Feeld\Display\HTML\Element('p'))->setContent('An error occurred:')->addCssClass('error');
        
        print($errMessageIntro.$errMessageContainer);
    }

    /**
     * In case everything was fine, let's return a thank you note to the user
     * 
     * @param \Feeld\FieldInterface $lastField
     */
    public function onValidationSuccess(\Feeld\FieldInterface $lastField = null) {
        $this->getUniqueID();
        $this->thankYouNote();
    }
    
    /**
     * Example.
     * 
     * Prints a thank you for answering the questions
     * You should probably override this method with your own.
     * 
     * @param string $text
     */
    public function thankYouNote($text = 'Thank you for answering all the questions!') {
        $thankYou = new \Feeld\Display\HTML\Element('p');
        $thankYou->setContent($text);
        print($thankYou);
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
}
