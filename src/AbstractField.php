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
 * Base class for all Fields
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
abstract class AbstractField implements FieldInterface {
    use \Wellid\SanitorBridgeTrait, Field\CommonProperties\Field;
    
    /**
     * Constructor
     * 
     * @param \Feeld\DataType\DataTypeInterface $dataType
     * @param mixed $id
     * @param \Feeld\Display\DisplayInterface $display
     */
    public function __construct(\Feeld\DataType\DataTypeInterface $dataType, $id = null, \Feeld\Display\DisplayInterface $display = null) {
        $this->setDisplay(is_null($display)?new \Feeld\Display\NoDisplay():$display);
        
        $this->dataType = $dataType;
        $this->setId($id);
        
        foreach($dataType->getValidators() as $validator) {
            $this->addValidator($validator);
        }
        $this->setSanitizer($dataType->getSanitizer());
    }    
}
