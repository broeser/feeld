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
namespace Feeld\Display\HTML;

/**
 * HTML Element
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class TextNode extends HTMLBuildingBlock {
    /**
     * Text content
     * 
     * @var string
     */
    protected $content;
    
    /**
     * Constructor
     * 
     * @param string $content
     */
    public function __construct($content = '') {
        $this->content = $content;
    }
    
    /**
     * Returns a string representation of this textNode
     */
    public function __toString() {       
        return $this->content;
    }
    
    /**
     * Sets the content
     * 
     * @param string $content
     * @return Element
     */
    public function setContent($content) {
        $this->content = $content;
        
        return $this;
    }
}
