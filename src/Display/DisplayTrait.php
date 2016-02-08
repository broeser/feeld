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
 * Description of DisplayTrait
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait DisplayTrait {
    /**
     * Whether this Display is visible
     * 
     * @var boolean
     */
    protected $visible = true;
    
    /**
     * Makes this display invisible
     */
    public function setInvisible() {
        $this->visible = false;
    }

    /**
     * Makes this display visible
     * Displays are visible by default
     */
    public function setVisible() {
        $this->visible = true;
    }
    
    /**
     * Returns whether this Display is visible
     * 
     * @return boolean
     */
    public function isVisible() {
        return $this->visible;
    }
}
