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
 * Interface for classes that offer FieldCollections to an user for data entry
 * 
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
interface InterviewInterface {
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
     * Returns the current status of the Interview
     * One of the STATUS_-constants
     * 
     * @return int
     */
    public function getStatus();
    
    /**
     * Retrieves at least one answer to at least one question
     * 
     * @return boolean Return true, if at least one answer was retrieved, false
     * otherwise
     */    
    public function retrieveAnswers();
    
    /**
     * Invites the user to answer at least one question.
     * 
     * This could be done by simple displaying the question(s).
     */
    public function inviteAnswers();
    
    /**
     * Called when an invalid value exists
     * 
     * @param \Feeld\FieldInterface $lastField the field that caused a
     * validation error; NULL if the FieldCollection's ValidationResultSet shall
     * be used instead
     */
    public function onValidationError(\Feeld\FieldInterface $lastField = null);
    
    /**
     * Called when an invalid value does not exist
     * 
     * @param \Feeld\FieldInterface $lastField the valid field; NULL if the 
     * whole FieldCollection is valid
     */
    public function onValidationSuccess(\Feeld\FieldInterface $lastField = null);
    
    /**
     * Executes the Interview in the following manner:
     * - If at least one answer was given, the answer(s) are validated
     * - If no answers were given, the user is invited to answer the question(s),
     *   e.g. by displaying them to the user
     * - In either case the current status of the Interview is returned
     * 
     * @param int $pageNumber Set the number of the page you want to execute for
     *  multi-page-forms; NOTE: first page is number 0
     * @return int One of the STATUS_...-constants
     */
    public function execute($pageNumber = 0);
}
