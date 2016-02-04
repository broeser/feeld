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
 * To be used in Fields that can have a default value
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait DefaultValue {
    /**
     * Default value
     * 
     * @var mixed
     */
    protected $default;
    
    /**
     * Default value
     * 
     * @return mixed
     */
    public function getDefault() {
        return $this->default;
    }
    
    /**
     * Sets a default value
     * 
     * @param mixed $value
     * @throws \Exception
     * @return DefaultValueInterface
     */
    public function setDefault($value) {
        $defaultValue = $this->getSanitizer()->filter($value);
        $result = $this->validateValue($defaultValue)->firstError();
        if($result instanceof \Wellid\ValidationResult) {
            throw new \Exception('Default value could not be set: '.$result->getMessage(), $result->getCode());
        }
        $this->default = $defaultValue;
        
        return $this;
    }
    
    /**
     * Returns whether this has a default value
     * 
     * @return boolean
     */
    public function hasDefault() {
        return($this->getDefault()!==null);
    }
}
