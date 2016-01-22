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

namespace Feeld\Field\DataType\Boundaries;

/**
 * To be used in Fields that have a minimum and/or maximum character length
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait LengthBoundaries {
    /**
     * Minimum text length
     * 
     * @var integer
     */
    protected $minLength = 0;
    
    /**
     * Maximum text length
     * 
     * @var integer
     */
    protected $maxLength;
    
    /**
     * Sets the minimum number of characters that must be entered into this field
     * 
     * @param integer $min
     * @return LengthBoundariesInterface Returns itself for daisy-chaining
     */
    public function setMinLength($min) {
        $this->addValidator(new \Wellid\Validator\MinLength($min));
        $this->minLength = $min;
        
        return $this;
    }
    
    /**
     * Sets the maximum number of characters that can be entered into this field
     * 
     * @param integer $max
     * @return LengthBoundariesInterface Returns itself for daisy-chaining
     */
    public function setMaxLength($max) {       
        $this->addValidator(new \Wellid\Validator\MaxLength($max));
        $this->maxLength = $max;
        
        return $this;
    }
    
    /**
     * Returns the minimum number of characters that must be entered in this Field
     * 
     * @return int
     */
    public function getMinLength() {
        return $this->minLength;
    }
    
    /**
     * Returns the maximum number of characters that can be entered into this field
     * 
     * @return int
     */
    public function getMaxLength() {
        return $this->maxLength;
    }
    
    /**
     * Returns, whether a maximum length for this field is specified
     * 
     * @return boolean
     */
    public function hasMaxLength() {
        return $this->getMaxLength()!==null;
    }
    
    /**
     * Returns, whether a minimum length for this field is specified
     * 
     * @return boolean
     */
    public function hasMinLength() {
        return $this->getMinLength()!==null;
    }
}
