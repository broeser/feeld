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

namespace Feeld\Field\CommonProperties;

/**
 * Used in all Fields
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait Field {
    /**
     * Bundles traits that are often used together
     */
    use DefaultValue, Identifier, Required;
    
    /*
     * A Field can be a data source for a Display
     */
    use \Feeld\Display\DisplayDataSourceTrait;
    
    /**
     * DataType of this field
     * 
     * @var \Feeld\DataType\DataTypeInterface
     */
    protected $dataType;
    
    /**
     * Returns the DataType of this field
     * 
     * @return \Feeld\DataType\DataTypeInterface
     */
    public function getDataType() {
        return $this->dataType;
    }
}
