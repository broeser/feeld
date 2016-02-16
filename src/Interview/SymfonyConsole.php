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

use \Symfony\Component\Console;
use \Feeld\FieldCollection\FieldCollectionInterface;

/**
 * Description of SymfonyConsoleInterview
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class SymfonyConsole extends AbstractTreeInterview {
    /**
     * CLI Input
     * 
     * @var Console\Input\InputInterface
     */
    protected $input;
    
    /**
     * CLI Output
     * 
     * @var Console\Output\OutputInterface
     */
    protected $output;
    
    /**
     * Symfony QuestionHelper used to ask Questions
     * 
     * @var Console\Helper\QuestionHelper
     */
    protected $questionHelper;
    
    /**
     * Field that is currently presented
     * 
     * @var \Feeld\FieldInterface
     */
    protected $currentField;

    /**
     * Constructor
     * 
     * @param Console\Input\InputInterface $input
     * @param Console\Output\OutputInterface $output
     * @param Console\Helper\QuestionHelper $questionHelper
     * @param FieldCollectionInterface ...$fieldCollections
     */
    public function __construct(Console\Input\InputInterface $input, Console\Output\OutputInterface $output, Console\Helper\QuestionHelper $questionHelper, FieldCollectionInterface ...$fieldCollections) {
        parent::__construct(...$fieldCollections);
        $this->input = $input;
        $this->output = $output;
        $this->questionHelper = $questionHelper;
    }
    
    /**
     * Invites the user to answer at least one question.
     */
    public function inviteAnswers() {
        $this->output->writeln('Please answer the following questions truthfully');
    }

    /**
     * Called when an invalid value exists
     * 
     * @param \Feeld\FieldInterface $lastField the field that caused a
     * validation error; NULL if the FieldCollection's ValidationResultSet shall
     * be used instead
     */
    public function onValidationError(\Feeld\FieldInterface $lastField = null) {
        $this->status = InterviewInterface::STATUS_RECOVERABLE_ERROR;        
        $this->output->writeln('An error occurred. This should not happen.');
    }

    /**
     * Called when an invalid value does not exist
     * 
     * @param \Feeld\FieldInterface $lastField the valid field; NULL if the 
     * whole FieldCollection is valid
     */
    public function onValidationSuccess(\Feeld\FieldInterface $lastField = null) {
        $this->output->writeln('Thank you for your answer.');
    }

    /**
     * Retrieves at least one answer to at least one question
     * 
     * @return boolean Return true, if at least one answer was retrieved, false
     * otherwise
     */
    public function retrieveAnswers() {
        $this->currentField->refreshDisplay();
        $answer = $this->questionHelper->ask($this->input, $this->output, $this->currentField->getDisplay()->getSymfonyQuestion());
        
        if($this->currentField instanceof \Feeld\Field\CommonProperties\IdentifierInterface && $this->currentField->hasId()) {
            foreach($this->getCurrentCollection()->getValueMapper() as $vm) {
                $vm->set($this->currentField->getId(), $answer);
            }
        }
        
        return true;
    }

    /**
     * Executes the Interview in the following manner:
     * - If no answers were given, the user is invited to answer the question(s),
     *   e.g. by displaying them to the user
     * - If at least one answer was given, the answer(s) are validated
     * - In either case the current status of the Interview is returned
     * 
     * @param int $pageNumber Set the number of the page you want to execute for
     *  multi-page-forms; NOTE: first page is number 0
     * @return int One of the STATUS_...-constants
     */
    public function execute($pageNumber = 0) {
        $this->skipToCollection($pageNumber);
        
        $this->inviteAnswers();
        
        foreach($this->getCurrentCollection()->getFields() as $field) {
            $this->currentField = $field;
            try {
                $this->retrieveAnswers();
            } catch (Exception $ex) {
                $this->onValidationError($field);
            }
            $this->onValidationSuccess($field);
        }
        
        $this->status = InterviewInterface::STATUS_AFTER_INTERVIEW;
        
        return $this->getStatus();
    }

}
