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
namespace Feeld\Display\HTML;

/**
 * Display for displaying validation error messages
 * Behaves like a NoDisplay if there are no validation errors
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class ErrorContainer extends Element implements \Feeld\Display\DisplayInterface {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('div');
        $this->addCssClass('has-error validationerrors');
    }

    /**
     * Takes information from the Field and uses it in this display
     * 
     * @param DisplayDataSourceInterface $field
     */
    public function informAboutStructure(\Feeld\Display\DisplayDataSourceInterface $field) {
        if(!$field instanceof \Feeld\Interview\ErrorContainer) {
            throw new \Wellid\Exception\DataType('field', 'ErrorContainer', $field);
        }
        
        $errorMessages = $errorMessagesLi = array();
        foreach($field->getErrorMessages() as $entry) {
            $errorMessages[] = '<span class="bg-danger error">'.htmlspecialchars($entry).'</span>';
            $errorMessagesLi[] = '<li>'.htmlspecialchars($entry).'</li>';
        }
        
        if(count($errorMessages)===0) {
            $this->setInvisible();
            return;
        }
        
        $this->setVisible();
        
        if(count($errorMessages)===1) {
            $this->setContent($errorMessages[0]);
            return;
        }
        
        $this->setContent('<ul class="bg-danger error">'.implode('', $errorMessagesLi).'</ul>');
    }

}
