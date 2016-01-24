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

/**
 * Interface for all DataTypes
 * 
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
interface DataTypeInterface extends \Wellid\ValidatorHolderInterface {
    /**
     * Returns the default Sanitizer of this DataType
     * 
     * @return \Sanitor\Sanitizer
     */
    public function getSanitizer();
    
    /**
     * Sets the default Sanitizer for this DataType
     * 
     * @return \self
     * @param \Sanitor\Sanitizer $sanitizer
     */
    public function setSanitizer(\Sanitor\Sanitizer $sanitizer);    
    
    /**
     * Returns the last value that has been sanitized in a call to validateValue()
     * Useful for logging, error messages, debugging.
     * 
     * @return mixed
     */
    public function getLastSanitizedValue();
}
