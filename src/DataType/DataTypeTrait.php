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

namespace Feeld\DataType;

use Wellid\ValidationResultSet;

/**
 * Interface for all DataTypes
 * 
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait DataTypeTrait {
    use \Wellid\ValidatorHolderTrait;
    
    /**
     * Sanitizer
     * 
     * @var \Sanitor\Sanitizer 
     */
    protected $sanitizer;
    
    /**
     * Always contains the last value sanitized value from a call to 
     * validateValue()
     * 
     * @var mixed
     */
    protected $lastSanitizedValue;
    
    /**
     * Returns the default Sanitizer of this DataType
     * 
     * @return \Sanitor\Sanitizer
     */
    public function getSanitizer() {
        return $this->sanitizer;
    }

    /**
     * Sets the Sanitizer
     * 
     * @return \self
     * @param \Sanitor\Sanitizer $sanitizer
     */
    public function setSanitizer(\Sanitor\Sanitizer $sanitizer) {
        $this->sanitizer = $sanitizer;
        
        return $this;
    }  
    
    /**
     * Validates a value against all given Validators
     * 
     * @param mixed $value
     * @return ValidationResultSet
     */
    public function validateValue($value) {
        $validationResultSet = new ValidationResultSet();
        $this->lastSanitizedValue = $this->getSanitizer()->filter($value);
        foreach($this->validators as $validator) {
            $validationResultSet->add($validator->validate($this->lastSanitizedValue));
        }
        return $validationResultSet;
    }
    
    /**
     * Returns the last value that has been sanitized in a call to validateValue()
     * Useful for logging, error messages, debugging.
     * 
     * @return mixed
     */
    public function getLastSanitizedValue() {
        return $this->lastSanitizedValue;
    }
}
