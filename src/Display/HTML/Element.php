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
class Element {
    /**
     * Attributes as key => value pairs, e. g. accesskey, tabindex, spellcheck,
     * autofocus, disabled, style etc.
     * 
     * @var string[]
     */
    protected $attributes = array();    
    
    /**
     * Name of this HTMLElement
     * 
     * @var string
     */
    protected $nodeName;
    
    /**
     * CSS classes of this element
     * 
     * @var string[]
     */
    protected $cssClasses;
    
    /**
     * Content
     * 
     * @var string
     */
    protected $content;
    
    /**
     * Sets a CSS class for this Element
     * 
     * @param string $class
	 * @return Element Returns itself for daisy-chaining
     */
    public function addCssClass($class) {
        foreach(explode(' ', $class) as $singleClass) {
            $this->cssClasses[$singleClass] = trim($singleClass);
        }
        $this->attributes['class'] = implode(' ', $this->cssClasses);
        
        return $this;
    }
    
    /**
     * Returns whether this is a void element
     * 
     * @link http://w3c.github.io/html-reference/syntax.html#void-element List of void elements
     * @return boolean
     */
    public function isVoid() {
        return in_array(strtolower($this->nodeName), array('area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'));
    }
    
    /**
     * Constructor
     * 
     * @param string $nodeName
     */
    public function __construct($nodeName) {
        $this->nodeName = $nodeName;
    }
    
    /**
     * Returns a string representation of this element (HTML)
     */
    public function __toString() {
        $attributes = '';
        foreach($this->attributes as $key => $value) {
            $attributes .= ' '.$key.'="'.htmlspecialchars($value).'"';
        }
        
        return '<'.$this->nodeName.$attributes.'>'.($this->isVoid()?'':$this->content.'</'.$this->nodeName.'>');
    }
    
    /**
     * Sets an HTML attribute
     * 
     * @param string $key
     * @param string $value
     * @return Element
     */
    public function setAttribute($key, $value) {
        $this->attributes[$key] = $value;
        
        return $this;
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
