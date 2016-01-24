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

namespace Feeld;

/**
 * Classes that implement FieldInterface (short: Fields) define types of data
 * entry (e. g. by selecting one of several values, by checking a box or by
 * inputting a text). (Please note that this has nothing to do with the UI of
 * the Field, "selecting one of several values" might be done by clicking on
 * the value or typing it in; for the UI-component of Feeld, see DisplayInterface)
 * 
 * Currently all Fields have optional default values, an optional identifier and
 * may be mandatory. That's why this interface extends those interfaces.
 * 
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
interface FieldInterface extends \Wellid\SanitorBridgeInterface, \Wellid\ValidatableInterface,
 Field\CommonProperties\DefaultValueInterface, Field\CommonProperties\IdentifierInterface, Field\CommonProperties\RequiredInterface {
     /**
     * Returns the Display assigned to this Field. If no display was assigned
     * to this Field, the class NoDisplay can be used.
     * 
     * @return \Feeld\Display\DisplayInterface
     */
    public function getDisplay();
    
    /**
     * Assigns a Display to this Field
     * 
     * @param \Feeld\Display\DisplayInterface $display
     * @return FieldInterface
     */
    public function setDisplay(\Feeld\Display\DisplayInterface $display);
    
    /**
     * Informs the assigned Display (if any) of the current structure of this
     * Field
     */
    public function refreshDisplay();
    
    /**
     * Returns the DataType of this Field
     * 
     * @return \Feeld\DataType\DataTypeInterface
     */
    public function getDataType();
}
