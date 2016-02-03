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
namespace Feeld\FieldCollection;

/**
 * Description of FieldCollection
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class FieldCollection implements FieldCollectionInterface, \Iterator {
    use FieldCollectionTrait;
    
    /**
     * Returns all Fields of a certain class
     * 
     * @param string $class
     * @return FieldCollection
     */
    public function getFieldsByClass($class) {
        $retCollection = new FieldCollection();
        foreach($this->getFields() as $field) {
            if(get_class($field)===$class) {
                $retCollection->addField($field);
            }
        }
        return $retCollection;
    }
    
    /**
     * Returns all required/mandatory Fields
     * 
     * @return FieldCollection
     */
    public function getMandatoryFields() {
        $retCollection = new FieldCollection();
        foreach($this->getFields() as $field) {
            if($field instanceof Field\CommonProperties\RequiredInterface && $field->isRequired()) {
                $retCollection->addField($field);
            }
        }
        return $retCollection;
    }
    
    /*
     * Iterator-methods
     */
    
    /**
     * Returns the current (Iterator) Field
     * 
     * @return FieldInterface
     */
    public function current() {
        return $this->fields[$this->position];
    }

    /**
     * Returns the current Iterator position
     * 
     * @return integer
     */
    public function key() {
        return $this->position;
    }

    /**
     * Increases the Iterator position
     */
    public function next() {
        ++$this->position;
    }

    /**
     * Rewinds the Iterator position
     */
    public function rewind() {
        $this->position = 0;
    }

    /**
     * Returns whether a certain entry is set (Iterator-interface)
     * 
     * @return boolean
     */
    public function valid() {
        return isset($this->fields[$this->position]);
    }
}
