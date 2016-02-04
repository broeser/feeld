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

use \Wellid\Validator\Date as DateValidator;
use \Wellid\Exception\DataType as DataTypeException;
/**
 * Description of Date
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Date implements DataTypeInterface {
    use DataTypeTrait;
    
    /**
     * Minimum value, format YYYY-MM-DD
     * 
     * @var string 
     */
    protected $min;
    
    /**
     * Maximum value, format YYYY-MM-DD
     * 
     * @var string 
     */
    protected $max;
    
    /**
     * Allowed steps (default is 1 day)
     * 
     * @var integer 
     */
    protected $step = 1;
    
    /**
     * Sets the HTML-min-attribute of this Date-field
     * 
     * @param string $min
     * @return Date Returns itself for daisy-chaining
     * @throws DataType
     */
    public function setMin($min) {
        if((new DateValidator())->validate($min)->isError()) {
            throw new DataTypeException('min', 'date', $min);
        }
        
        $this->min = $min;
        
        return $this;
    }
    
    /**
     * Sets the HTML-max-attribute of this Date-field
     * 
     * @param string $max
     * @return Date Returns itself for daisy-chaining
     * @throws DataType
     */
    public function setMax($max) {
        if((new DateValidator())->validate($max)->isError()) {
            throw new DataTypeException('max', 'date', $max);
        }
        
        $this->max = $max;
        
        return $this;
    }
    
    /**
     * Sets the HTML-step-attribute of this Date-field
     * 
     * @param integer $step
     * @return Date Returns itself for daisy-chaining
     * @throws DataType
     */
    public function setStep($step) {
        if(!is_int($step)) {
            throw new DataTypeException('step', 'date', $step);
        }
        
        $this->step = $step;
        
        return $this;
    }
    
    /**
     * Return earliest date
     * 
     * @return string
     */
    public function getMin() {
        return $this->min;
    }
    
    /**
     * Return latest date
     * 
     * @return string
     */
    public function getMax() {
        return $this->max;
    }
    
    /**
     * Return stepping in days
     * 
     * @return int
     */
    public function getStep() {
        return $this->step;
    }
    
    /**
     * Constructor
     * 
     * @param \Sanitor\Sanitizer $sanitizer
     */
    public function __construct(\Sanitor\Sanitizer $sanitizer = null) {
        $this->setSanitizer(is_null($sanitizer)?new \Sanitor\Sanitizer(FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW):$sanitizer);
        $this->addValidator(new DateValidator());
    }

    /**
     * Returns the last sanitized value in a type fitting this DataType
     * 
     * @param string $value
     * @return \DateTime
     */
    public function transformSanitizedValue($value = null) {
        if(is_null($value)) {
            $value = $this->getLastSanitizedValue();
        }
        
        return new \DateTime($value);
    }

}
