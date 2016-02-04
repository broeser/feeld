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

namespace Feeld\Display;

/**
 * Interface for everything that can be the data source of a display
 * 
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
interface DisplayDataSourceInterface {
    /**
     * Returns the Display assigned to this data source. If no display was 
     * assigned to this data source, the class NoDisplay can be used.
     * 
     * @return DisplayInterface
     */
    public function getDisplay();
    
    /**
     * Assigns a Display to this Field
     * 
     * @param DisplayInterface $display
     * @return DisplayDataSourceInterface
     */
    public function setDisplay(DisplayInterface $display);
    
    /**
     * Informs the assigned Display (if any) of the current structure of this
     * Field
     */
    public function refreshDisplay();
}
