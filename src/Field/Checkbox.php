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
namespace Feeld\Field;

/**
 * A "yes or no"-field
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Checkbox extends \Feeld\AbstractField implements CommonProperties\OptionsInterface {   
    use CommonProperties\Options;

    /**
     * Constructor
     * 
     * @param \Feeld\Field\DataType\DataTypeInterface $dataType
     * @param array $options
     * @param mixed $id
     * @param \Feeld\Display\DisplayInterface $display
     * @throws \Wellid\Exception\DataType
     */
    public function __construct(DataType\DataTypeInterface $dataType = null, array $options = array('yes' => 1, 'no' => 0), $id = null, \Feeld\Display\DisplayInterface $display = null) {
        if(count($options)!==2) {
            throw new \Wellid\Exception\DataType('options', 'array with two values', $options);
        }
        
        if(is_null($dataType)) {
            $dataType = new \Feeld\DataType\Boolean(new \Sanitor\Sanitizer(FILTER_SANITIZE_NUMBER_INT));
            $options = array('yes' => 1, 'no' => 0);
        }
        
        parent::__construct($dataType, $id, $display);
        
        foreach($options as $key => $value) {
            $this->addOption($key, $value);
        }
    }
    
    /**
     * Returns the description of the Option that corresponds to the TRUE/checked
     * state
     * 
     * @return string
     * @throws Exception
     */
    public function getTrueOption() {
        foreach($this->getOptions() as $key => $val) {
            $boolVal = filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            
            if($boolVal===null) {
                throw new Exception('Could not determine Option for TRUE');
            } elseif($boolVal) {
                return $key;
            }
        }
    }
}
