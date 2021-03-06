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
 * A Text Field
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Str implements DataTypeInterface, Boundaries\LengthBoundariesInterface {
    use DataTypeTrait;
    
    /* Text fields can have a minimum and maximum length */
    use Boundaries\LengthBoundaries;
    
    /**
     * Constructor
     * 
     * @param \Sanitor\Sanitizer $sanitizer
     */
    public function __construct(\Sanitor\Sanitizer $sanitizer = null) {
        $this->setSanitizer(is_null($sanitizer)?new \Sanitor\Sanitizer(FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW):$sanitizer);
    }

    /**
     * Returns the last sanitized value in a type fitting this DataType
     * 
     * @param string $value
     * @return string
     */
    public function transformSanitizedValue($value = null) {
        if(is_null($value)) {
            $value = $this->getLastSanitizedValue();
        }
        
        return (string)$value;
    }
}
