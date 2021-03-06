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
 * Description of Integer
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Integer implements DataTypeInterface, Boundaries\LengthBoundariesInterface, Boundaries\NumericBoundariesInterface {
    use DataTypeTrait;
    
    /* Integers may have numeric (max 6) and length (max 2 digits) boundaries */
    use Boundaries\LengthBoundaries, Boundaries\NumericBoundaries;

    /**
     * Constructor
     * 
     * @param mixed $id
     * @param \Sanitor\Sanitizer $sanitizer
     */
    public function __construct(\Sanitor\Sanitizer $sanitizer = null) {  
        $this->setStep(1);
        $this->setSanitizer(is_null($sanitizer)?new \Sanitor\Sanitizer(FILTER_SANITIZE_NUMBER_INT):$sanitizer);
        $this->addValidator(new \Wellid\Validator\Integer());
    }

    /**
     * Returns the last sanitized value in a type fitting this DataType
     * 
     * @param string $value
     * @return int
     */
    public function transformSanitizedValue($value = null) {
        if(is_null($value)) {
            $value = $this->getLastSanitizedValue();
        }
        
        return (int)$value;
    }

}
