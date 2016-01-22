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
namespace Feeld\Display;
/**
 * Constructs a HTML-representation of a Field
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class HTMLBuilder  {
    /**
     * Field
     * 
     * @var \Feeld\FieldInterface
     */
    protected $field;
    
    /**
     * HTML-Element
     * 
     * @var \Feeld\Display\HTML\Element
     */
    protected $element;
    
    /**
     * How many options there should be before a radiobutton becomes a select
     * 
     * @var type 
     */
    protected $radioToSelectThreshold = 3;
    
    /**
     * How many characters there should be, before a single line text becomes a
     * textarea
     * 
     * @var type 
     */
    protected $textToTextAreaThreshold = 255;
    
    /**
     * Constructor
     * 
     * @param \Feeld\FieldInterface $field
     */
    public function __construct(\Feeld\FieldInterface $field) {
        $this->field = $field;
    }
    
    /**
     * 
     * 
     * @param int $value
     */
    public function setThreshold($value) {
        $this->radioToSelectThreshold = $value;
        $this->textToTextAreaThreshold = $value;
    }
    
    /**
     * Creates and returns an HTML-Element from the given Field
     * (without any attributes yet, though)
     * 
     * @return \Feeld\Display\HTML\Element
     */
    public function createHTMLElement() {
        if($this->field instanceof \Feeld\Field\Constant) {
            $this->element = new HTML\Input('hidden');
        } elseif($this->field instanceof \Feeld\Field\CloakedEntry) {
            $this->element = new HTML\Input('password');
        } elseif($this->field instanceof \Feeld\Field\Checkbox) {
            $this->element = new HTML\Input('checkbox');
        } elseif($this->field instanceof \Feeld\Field\Select) {           
            if($this->field->isMultipleChoice() || count($this->field->getOptions())>$this->radioToSelectThreshold) {
                $this->element = new HTML\Select();
            } else {
                $this->element = new HTML\Input('radio');
            }
        } else {
            if(get_class($this->field->getDataType())==='Feeld\Field\DataType\String') {
                if(($this->field->hasMinLength() && $this->field->getMinLength()>=$this->textToTextAreaThreshold)
                    || ($this->field->hasMaxLength() && $this->field->getMaxLength()>$this->textToTextAreaThreshold)
                    || ($this->field->hasDefault() && mb_strlen($this->field->getDefault())>=$this->textToTextAreaThreshold)) {
                    $this->element = new HTML\Textarea();
                } else {
                    $this->element = new HTML\Input('text');
                }
            } else {
                switch(get_class($this->field->getDataType())) {
                    case 'Feeld\Field\DataType\URL':
                        $this->element = new HTML\Input('url');
                    break;
                    case 'Feeld\Field\DataType\Integer':
                    case 'Feeld\Field\DataType\Float':
                        $this->element = new HTML\Input('number');
                    break;
                    case 'Feeld\Field\DataType\File':
                        $this->element = new HTML\Input('file');
                    break;                                    
                    case 'Feeld\Field\DataType\Email':
                        $this->element = new HTML\Input('email');
                    break;
                    case 'Feeld\Field\DataType\Date':
                        $this->element = new HTML\Input('date');
                    break;                                   
                    case 'Feeld\Field\DataType\Country':
                        $this->element = new HTML\Input('text');
                    break;                                                    
                }
            }
        }
        return $this->element;
    }
    
    /**
     * Sets attributes
     */
    public function createHTMLElementWithAttributes() {
        if(!$this->element instanceof HTML\Element) {
            $this->createHTMLElement();
        }
        
        $dataType = $this->field->getDataType();
        
        if($this->field instanceof \Feeld\Field\CommonProperties\IdentifierInterface && $this->field->hasId()) {
            $this->element->setAttribute('id', $this->field->getId());
        }
        
        if($this->field instanceof \Feeld\Field\CommonProperties\DefaultValueInterface && $this->field->hasDefault()) {
            if($this->element instanceof HTML\Textarea) {
                $this->element->setContent($this->field->getDefault());
            } else {
                $this->element->setAttribute('value', $this->field->getDefault());
            }
        }
        
        if($this->field instanceof \Feeld\Field\CommonProperties\MultipleChoiceInterface && $this->field->isMultipleChoice()) {
            if($this->element instanceof \Feeld\Field\Select || $dataType instanceof \Feeld\Field\DataType\Email || $dataType instanceof \Feeld\Field\DataType\File) {
                $this->element->setAttribute('multiple', 'multiple');
            }
        }
        
        if($this->field instanceof \Feeld\Field\CommonProperties\RequiredInterface && $this->field->isRequired()) {
            $this->element->setAttribute('required', 'required');
        }
        
        if($dataType instanceof \Feeld\Field\DataType\Boundaries\NumericBoundariesInterface || $dataType instanceof \Feeld\Field\DataType\Date) {
            if($dataType->hasMax()) $this->element->setAttribute ('max', $dataType->getMax ());
            if($dataType->hasMin()) $this->element->setAttribute ('min', $dataType->getMin ());
            if($dataType->hasStep()) $this->element->setAttribute ('step', $dataType->getStep ());
        }
        
        if($dataType instanceof \Feeld\Field\DataType\Boundaries\LengthBoundariesInterface) {
            if($dataType->hasMaxLength()) $this->element->setAttribute ('maxlength', $dataType->getMaxLength ());
            if($dataType->hasMinLength()) $this->element->setAttribute ('minlength', $dataType->getMinLength ());
        }
        
        if($dataType instanceof \Feeld\Field\DataType\File) {
            if(!is_null($dataType->getAccept())) $this->element->setAttribute ('accept', $dataType->getAccept ());
        }
        
        return $this->element;
    }


    /**
     * Returns a string representation of the HTML element that corresponds to 
     * the Field
     * 
     * @return string
     */
    public function __toString() {
        return (string)$this->createHTMLElementWithAttributes();
    }
}
