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
 * Used by data sources for Displays (Fields and FieldCollections)
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait DisplayDataSourceTrait {    
    /**
     * Display of this data source
     * 
     * @var DisplayInterface
     */
    protected $display;
    
    /**
     * Whether the associated Display (if any) has been refreshed at least once
     * 
     * @var boolean
     */
    private $hasRefreshedDisplayAtLeastOnce = false;

    /**
     * Returns the Display assigned to this data source
     * 
     * @return DisplayInterface
     */
    public function getDisplay() {
        return $this->display;
    }
    
    /**
     * Sets the Display
     * 
     * @param DisplayInterface $display
     */
    public function setDisplay(DisplayInterface $display) {
        $this->display = $display;

        return $this;
    }
    
    /**
     * Informs the assigned Display (if any) of the current structure of this
     * Field
     */
    public function refreshDisplay() {
        $this->getDisplay()->informAboutStructure($this);
        $this->hasRefreshedDisplayAtLeastOnce = true;
    }
    
    /**
     * Returns a string representation of the Display assigned to this data
     * source
     * 
     * @return string
     */
    public function __toString() {
        if(!$this->hasRefreshedDisplayAtLeastOnce) {
            $this->refreshDisplay();
        }
        return (string)$this->getDisplay();
    }
}
