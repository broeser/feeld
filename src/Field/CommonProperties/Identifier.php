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
 * To be used in fields that have an identifier
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait Identifier {
    /**
     * And unique identifier for this field.
     * 
     * Probably string or int are your best choices, the data type however is
     * not enforced.
     * 
     * @var string|int
     */
    protected $id;
    
    /**
     * Returns an ID
     * 
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Sets an ID
     * 
     * @param mixed $id
     * @return IdentifierInterface
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    /**
     * Returns whether this has an ID
     * 
     * @return boolean
     */
    public function hasId() {
        return $this->getId()!==null;
    }
}
