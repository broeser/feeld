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
 * Trait for an Interview that is branchable or has multiple pages of
 * questions (the terms page number and branch are interchangeable in this
 * context)
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait TreeInterviewTrait {
    use InterviewStatusTrait;
    
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
     * Return current valid answers
     * 
     * @return object|object[]
     */
    public function getCurrentValidAnswers() {
        $answers = $this->getCurrentCollection()->getValidAnswers();
        if(isset($answers[FieldCollectionInterface::VALUE_MAPPER_DEFAULT_ID])) {
            return $answers[FieldCollectionInterface::VALUE_MAPPER_DEFAULT_ID];
        }
        
        return $answers;
    }
}
