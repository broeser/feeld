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

namespace Feeld\DataType\Boundaries;

/**
 * To be used in Fields that have a minimum and/or maximum value
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait NumericBoundaries {
    /**
     * Minimum value
     * 
     * @var integer|float
     */
    protected $min = null;
    
    /**
     * Maximum value
     * 
     * @var integer|float
     */
    protected $max = null;
    
    /**
     * Allowed stepping
     * 
     * @var integer 
     */
    protected $step;
    
    /**
     * Sets the minimum value
     * 
     * @param integer|float $min
     * @return NumericBoundariesInterface Returns itself for daisy-chaining
     * @throws \Wellid\Exception\DataType
     */
    public function setMin($min) {
        if(!is_numeric($min)) {
            throw new \Wellid\Exception\DataType('min', 'number', $min);
        }
        
        $this->addValidator(new \Wellid\Validator\Min($min));
        $this->min = $min;
        
        return $this;
    }
    
    /**
     * Sets the maximum value
     * 
     * @param integer|float $max
     * @return NumericBoundariesInterface Returns itself for daisy-chaining
     * @throws \Wellid\Exception\DataType
     */
    public function setMax($max) {
        if(!is_numeric($max)) {
            throw new \Wellid\Exception\DataType('max', 'number', $max);
        }
        
        $this->addValidator(new \Wellid\Validator\Max($max));
        $this->max = $max;
        
        return $this;
    }
    
    /**
     * Sets the step
     * 
     * @param integer|float $step
     * @return NumericBoundariesInterface Returns itself for daisy-chaining
     * @throws \Wellid\Exception\DataType
     */
    public function setStep($step) {
        if(!is_numeric($step)) {
            throw new \Wellid\Exception\DataType('step', 'number', $step);
        }
        
        $this->step = $step;
        
        return $this;
    }

    /**
     * Returns the maximum
     * 
     * @return int|float
     */
    public function getMax() {
        return $this->max;
    }

    /**
     * Returns the minimum
     * 
     * @return int|float
     */
    public function getMin() {
        return $this->min;
    }

    /**
     * Returns the optional stepping
     * 
     * @return int|float
     */
    public function getStep() {
        return $this->step;
    }
    
    /**
     * Return whether the optional stepping is active
     * 
     * @return boolean
     */
    public function hasStep() {
        return $this->getStep()!==null;
    }
    
    /**
     * Return whether a minimum is defined
     * 
     * @return boolean
     */
    public function hasMin() {
        return $this->getMin()!==null;
    }
    
    /**
     * Return whether a maximum is defined
     * 
     * @return boolean
     */
    public function hasMax() {
        return $this->getMax()!==null;
    }
}
