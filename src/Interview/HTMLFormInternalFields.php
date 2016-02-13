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

namespace Feeld\Interview;

/**
 * Description of HTMLFormInternalFields
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class HTMLFormInternalFields {
    /**
     * UID of this HTMLForm
     * 
     * @var string
     */
    private $uid;
    
    /**
     * Pagenumber of this multi-page HTMLForm, 0-indexed
     * 
     * @var int
     */
    private $pageNumber;
    
    /**
     * Sets the UID of this HTMLForm
     * 
     * @param string $uid
     */
    public function setUID($uid) {
        $this->uid = $uid;
    }
    
    /**
     * Gets the UID of this HTMLForm
     * 
     * @return string
     */
    public function getUID() {
        return $this->uid;
    }
    
    /**
     * Sets the pagenumber of this multi-page HTMLForm, 0-indexed
     * 
     * @param int $pageNumber
     */
    public function setPageNumber($pageNumber) {
        $this->pageNumber = $pageNumber;
    }
    
    /**
     * Gets the pagenumber of this multi-page HTMLForm, 0-indexed
     * 
     * @return int
     */
    public function getPageNumber() {
        return $this->pageNumber;
    }
}
