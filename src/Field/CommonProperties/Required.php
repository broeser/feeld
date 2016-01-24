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
 * Trait for everything that can be marked as required (e. g. field must
 * not be left blank)
 * 
 * No specific validator is enforced, because depending on data type, presentation
 * and policy, the meaning of "required" might differ
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait Required {
    /**
     * Whether this is a mandatory field
     * 
     * @var boolean
     */
    protected $required = false;
    
    /**
     * Marks this as required/mandatory
     * 
     * @return RequiredInterface Returns itself for daisy-chaining
     */
    public function setRequired() {
        $this->required = true;

        return $this;
    }
    
    /**
     * Returns whether this is a required/mandatory field
     * 
     * @return boolean
     */
    public function isRequired() {
        return $this->required;
    }
}