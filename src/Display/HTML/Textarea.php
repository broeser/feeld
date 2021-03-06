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
use Feeld\Field\CommonProperties\DefaultValueInterface;
use Feeld\Field\CommonProperties\IdentifierInterface;
use Feeld\Field\CommonProperties\RequiredInterface;
use Feeld\Display\DisplayDataSourceInterface;
use Feeld\DataType\Boundaries\LengthBoundariesInterface;
/**
 * Description of Textarea
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Textarea extends Element implements HTMLDisplayInterface {
    public function __construct() {
        parent::__construct('textarea');
    }
    
    /**
     * Takes information from the Field and uses it in this display
     * 
     * @param DisplayDataSourceInterface $field
     */
    public function informAboutStructure(DisplayDataSourceInterface $field) {
        /**
         * Use the Field identifier (if given) as default id-attribute for
         * this HTML element
         */
        if($field instanceof IdentifierInterface && $field->hasId()) {
            $this->setAttribute('id', $field->getId());
            $this->setAttribute('name', $field->getId());
        }
               
        if($field instanceof RequiredInterface && $field->isRequired()) {
            $this->setAttribute('required', 'required');
        }

        if($field instanceof DefaultValueInterface && $field->hasDefault()) {
            $this->setContent($field->getDefault());
        }
        
        if($field->getDataType() instanceof LengthBoundariesInterface) {
            if($field->getDataType()->hasMaxLength()) {
                $this->setAttribute('maxlength', $field->getDataType()->getMaxLength());
            }
            
            if($field->getDataType()->hasMinLength()) {
                $this->setAttribute('minlength', $field->getDataType()->getMinLength());
            }
        }
    }    
}
