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

namespace Feeld\Display\CLI;

use Symfony\Component\Console\Question\Question as SymfonyQuestion;

/**
 * Description of SymfonyConsoleDisplay
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class SymfonyConsoleDisplay extends DefaultCLI {
    /**
     * The SymfonyQuestion used internally
     * 
     * @var \Symfony\Component\Console\Question\Question 
     */
    protected $symfonyQuestion;
    
    /**
     * Copy of the data source for internal use
     * 
     * @var \Feeld\Display\DisplayDataSourceInterface
     */
    private $field;
    
    /**
     * Informs the display about the structure of a Field.
     * 
     * Each time the Field structure changes for some reason, this method shall
     * be called.
     *
     * @param \Feeld\Display\DisplayDataSourceInterface $field
     * @throws Exception
     */
    public function informAboutStructure(\Feeld\Display\DisplayDataSourceInterface $field) {
        parent::informAboutStructure($field);
        
        $this->field = $field;
        
        if($field instanceof \Feeld\Field\Entry) {
            $this->symfonyQuestion = new SymfonyQuestion((string)$this, ($field instanceof DefaultValueInterface && $field->hasDefault())?$field->getDefault():null);
        } elseif($field instanceof \Feeld\Field\Select) {
            $this->symfonyQuestion = new \Symfony\Component\Console\Question\ChoiceQuestion((string)$this, array_keys($field->getOptions()), ($field instanceof DefaultValueInterface && $field->hasDefault())?$field->getDefault():null);
            if($field instanceof \Feeld\Field\CommonProperties\MultipleChoiceInterface && $field->isMultipleChoice()) {
                $field->symfonyQuestion->setMultiselect(true);
            }
        } elseif($field instanceof \Feeld\Field\CloakedEntry) {
            $this->symfonyQuestion = new SymfonyQuestion((string)$this, ($field instanceof DefaultValueInterface && $field->hasDefault())?$field->getDefault():null);
            $this->symfonyQuestion->setHidden(true);
        } elseif($field instanceof \Feeld\Field\Constant) {
            throw new Exception('Constants are currently not supported in SymfonyConsoleDisplay');
        } elseif($field instanceof \Feeld\Field\Checkbox) {
            $this->symfonyQuestion = new \Symfony\Component\Console\Question\ConfirmationQuestion((string)$this, ($field instanceof DefaultValueInterface && $field->hasDefault())?$field->getDefault():true, '/^'.strtolower(substr($this->getTrueOption(),0,1)).'/i');
        }
        
        $this->symfonyQuestion->setNormalizer(function($value) {
            return $this->field->getSanitizer()->filter($value);
        });
        
        $this->symfonyQuestion->setValidator(function($value) {
            $validationResultSet = $this->field->validateValue($value);
            if($validationResultSet->hasErrors()) {
                $this->field->clearValidationResult();
                throw new \Exception(implode(PHP_EOL, $validationResultSet->getErrorMessages()));
            }

            return $this->field->getFilteredValue();
        });
        
        $this->symfonyQuestion->setMaxAttempts(null);
    }
    
    /**
     * Returns a string representation of the display
     * 
     * @return string
     */
    public function __toString() {
        if(!$this->visible) {
            return '';
        }
        
        return $this->question.'? '.(is_null($this->hint)?'':'<fg=white;options=bold>['.$this->hint.']</> ');
    } 
    
    /**
     * Returns the SymfonyQuestion
     * 
     * @return \Symfony\Component\Console\Question\Question 
     */
    public function getSymfonyQuestion() {
        return $this->symfonyQuestion;
    }    
}
