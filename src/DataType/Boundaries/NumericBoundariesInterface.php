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
 * To be used in Fields that have a minimum and/or maximum numeric value
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
interface NumericBoundariesInterface {
    /**
     * Sets the minimum value
     * 
     * @param int|float $min
     * @return NumericBoundariesInterface Returns itself for daisy-chaining
     */
    public function setMin($min);
    
    /**
     * Sets the maximum value
     * 
     * @param int|float $max
     * @return NumericBoundariesInterface Returns itself for daisy-chaining
     */
    public function setMax($max);
    
    /**
     * Returns the minimum value
     * 
     * @return int|float
     */
    public function getMin();
    
    /**
     * Returns the maximum value
     * 
     * @return int|float
     */
    public function getMax();
    
    /**
     * Returns the optional stepping of this field, e. g. 1 for integer fields
     * 
     * @return int|float
     */
    public function getStep();
    
    /**
     * Sets the optional stepping of this field, e. g. 1 for integer fields
     * 
     * @param int|float $step
     * @return NumericBoundariesInterface Returns itself for daisy-chaining
     */
    public function setStep($step);
    
    /**
     * Return whether the optional stepping is active
     * 
     * @return boolean
     */
    public function hasStep();
    
    /**
     * Return whether a minimum is defined
     * 
     * @return boolean
     */
    public function hasMin();
    
    /**
     * Return whether a maximum is defined
     * 
     * @return boolean
     */
    public function hasMax();
}
