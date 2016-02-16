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

use Wellid\Exception\NotFound;
use Wellid\Exception\DataType;
use Feeld\FieldCollection\FieldCollectionInterface;

/**
 * Interface for an Interview that is branchable or has multiple pages of
 * questions (the terms page number and branch are interchangeable in this
 * context)
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
interface TreeInterviewInterface extends InterviewInterface{
    /**
     * Returns the FieldCollection that is currently active on this page/branch
     * 
     * @return FieldCollectionInterface
     */
    public function getCurrentCollection();
    
    /**
     * Skips to another page/branch
     * 
     * @param int $pageNumber
     * @throws DataType
     * @throws NotFound
     */
    public function skipToCollection($pageNumber);
    
    /**
     * Skips to the FieldCollection of the next page
     */
    public function nextCollection();
    
    /**
     * Return valid answers of the current page/branch
     * 
     * @return object|object[]
     */
    public function getCurrentValidAnswers();
}
