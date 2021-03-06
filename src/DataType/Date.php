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
     * Date format, default is YYYY-MM-DD (Y-m-d)
     * 
     * @link http://php.net/manual/en/function.date.php
     * @var string
     */
    protected $format = 'Y-m-d';
    
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
     * Sets the earliest possible Date
     * 
     * @param string $min
     * @return Date Returns itself for daisy-chaining
     * @throws DataType
     */
    public function setMin($min) {       
        $this->min = $min;
        $this->addValidator(new \Wellid\Validator\MinDate($min, $this->format));
        
        return $this;
    }
    
    /**
     * Sets the latest possible Date
     * 
     * @param string $max
     * @return Date Returns itself for daisy-chaining
     * @throws DataType
     */
    public function setMax($max) {
        $this->max = $max;
        $this->addValidator(new \Wellid\Validator\MaxDate($max, $this->format));
        
        return $this;
    }
    
    /**
     * Sets the stepping of this Date-field (usually 1 day)
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
     * @param string $format Expected (input) date format
     */
    public function __construct(\Sanitor\Sanitizer $sanitizer = null, $format = null) {
        if(is_string($format)) {
            $this->format = $format;
        } elseif(!is_null($format)) {
            throw new DataTypeException('format', 'string', $format);
        }
        
        $this->setSanitizer(is_null($sanitizer)?new \Sanitor\Sanitizer(FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW):$sanitizer);
        $this->addValidator(new DateValidator($this->format));
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
        
        return \DateTime::createFromFormat($this->format, $value);
    }

}
