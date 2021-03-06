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

use Feeld\Display\DisplayDataSourceInterface;
/**
 * Description of Form
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Form extends Element implements \Feeld\Display\DisplayInterface {
    /**
     * DataSource
     * 
     * @var \Feeld\FieldCollection\FieldCollectionInterface
     */
    private $dataSource;
    
    /**
     * Constructor
     */
    public function __construct($method = 'post') {
        parent::__construct('form');
        $this->setAttribute('method', $method);
    }
    
    /**
     * Takes information from the FieldCollection and uses it in this display
     * 
     * @param \Feeld\FieldCollection\FieldCollectionInterface $field
     * @throws \Wellid\Exception\DataType
     */
    public function informAboutStructure(DisplayDataSourceInterface $field) {
        if(!$field instanceof \Feeld\FieldCollection\FieldCollectionInterface) {
            throw new \Wellid\Exception\DataType('field', 'FieldCollectionInterface', $field);
        }
        
        $this->dataSource = $field;
        
        if(count($field->getFieldsByDataType('Feeld\DataType\File'))>0) {
            $this->setAttribute('enctype', 'multipart/form-data');
        }
    }
    
    /**
     * Makes sure that alle Field Displays are updated before a string
     * representation of the Form containing those Fields are returned
     * 
     * @return string
     */
    public function __toString() {
        foreach($this->dataSource->getFields() as $field) {
            $field->refreshDisplay();
            
            /* Make sure that all hidden fields will be displayed somewhere */            
            if($field instanceof \Feeld\Field\CommonProperties\IdentifierInterface && $field->hasId() && is_null($this->getChildById($field->getId()))) {
                $fieldDisplay = $field->getDisplay();
                if($fieldDisplay instanceof Input && isset($fieldDisplay->attributes['type']) && $fieldDisplay->attributes['type']==='hidden') {
                    $this->prependChild($field->getDisplay());
                }
            }
        }
        
        return parent::__toString();
    }
}
