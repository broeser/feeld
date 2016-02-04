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
 * Example implementation of InterviewInterface, a HTML form handler
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class HTMLForm extends Interview {
    /**
     * Either INPUT_POST or INPUT_GET
     * 
     * @var int
     */
    protected $method;
    
    /**
     * Constructor
     */
    public function __construct(\Feeld\FieldCollection\FieldCollectionInterface ...$fieldCollections) {
        $i = 0;
        foreach($fieldCollections as $collection) {
            $pageNumber = new \Feeld\Field\Constant(new \Feeld\DataType\Integer(), 'page_'.$this->getId(), new \Feeld\Display\HTML\Input('hidden'));
            $pageNumber->setDefault($i);
            $collection->addField($pageNumber);
            $i++;
        }
        parent::__construct(parent::VALIDATE_PER_COLLECTION, ...$fieldCollections);
        $this->method = INPUT_POST;
    }
    
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
        $this->inviteAnswers();
    }

    /**
     * In case everything was fine, let's return a thank you note to the user
     * 
     * @param \Feeld\FieldInterface $lastField
     */
    public function onValidationSuccess(\Feeld\FieldInterface $lastField = null) {
        $thankYou = new \Feeld\Display\HTML\Element('p');
        $thankYou->setContent('Thank you for answering all the questions!');
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
     * Retrieves answers and returns whether there are any
     * 
     * @return boolean
     */
    public function retrieveAnswers() {
        if(!$this->methodMatches() || $this->getCurrentCollection()->getFieldById('page_'.$this->getId())->getFilteredValue()!==$this->currentCollectionId) return false;
        
        foreach($this->getCurrentCollection()->getFields() as $field) {
            if($field instanceof \Feeld\Field\CommonProperties\IdentifierInterface && $field->hasId()) {
                $field->rawValueFromInput($this->getMethod(), $field->getId());
            }
        }
        
        return true;
    }
}
