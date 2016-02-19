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

use \Feeld\Field\CommonProperties\IdentifierInterface;

/**
 * HTML Element
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Element extends HTMLBuildingBlock implements IdentifierInterface {
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
     * Children
     * 
     * @var HTMLBuildingBlock[]
     */
    protected $children = array();
    
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
        if(!$this->isVisible()) {
            return '';
        }
        
        $attributes = '';
        foreach($this->attributes as $key => $value) {
            $attributes .= ' '.$key.'="'.htmlspecialchars($value).'"';
        }
        
        return '<'.$this->nodeName.$attributes.'>'.($this->isVoid()?'':implode('', $this->children).'</'.$this->nodeName.'>');
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
     * Sets the content of this element, removing all previously existing content
     * 
     * @param string $content
     * @return Element
     */
    public function setContent($content) {
        if(!is_string($content)) {
            throw new \Wellid\Exception\DataType('content', 'string', $content);
        }
        
        $this->children = array(new TextNode($content));
        
        return $this;
    }
    
    /**
     * Appends child
     * 
     * @param HTMLBuildingBlock $element
     * @return Element
     */
    public function appendChild(HTMLBuildingBlock $element) {
        if($element instanceof IdentifierInterface && $element->hasId()) {
            $this->children[$element->getId()] = $element;
        } else {
            $this->children[] = $element;
        }
        
        return $this;        
    }
    
    /**
     * Returns a child Element by id or null
     * If it can't find an Element immediately, it re-creates the children-
     * array to see, if an id appeared
     * 
     * @param string $id
     * @return Element
     * @throws \Wellid\Exception\DataType
     */
    public function getChildById($id) {
        if(!is_string($id)) {
            throw new \Wellid\Exception\DataType('id', 'string', $id);
        }
        
        if(isset($this->children[$id])) {
            return $this->children[$id];
        }
        
        $newArr = array();
        foreach($this->children as $child) {
            if($child instanceof IdentifierInterface && $child->hasId()) {
                $newArr[$child->getId()] = $child;
            } else {
                $newArr[] = $child;
            }
        }
        $this->children = $newArr;
        
        if(isset($this->children[$id])) {
            return $this->children[$id];
        }
        
        return null;
    }
    
    /**
     * Appends children
     * 
     * @param HTMLBuildingBlock ...$elements
     * @return Element
     */
    public function appendChildren(HTMLBuildingBlock ...$elements) {
        foreach($elements as $element) {
            $this->appendChild($element);
        }
        
        return $this;
    }
    
    /**
     * Prepends content
     * 
     * @param HTMLBuildingBlock $element
     * @return Element
     */
    public function prependChild(HTMLBuildingBlock $element) {
        if($element instanceof IdentifierInterface && $element->hasId()) {
            $this->children = array_merge(array($element->getId() => $element), $this->children);
        } else {
            $this->children = array_merge(array($element), $this->children);
        }        
        
        return $this;        
    }

    /**
     * Returns the value of the id-attribute of this element (or null)
     * 
     * @return string
     */
    public function getId() {
        if(!$this->hasId()) {
            return null;
        }
        
        return $this->attributes['id'];
    }

    /**
     * Returns whether this Element has an id-attribute
     * 
     * @return boolean
     */
    public function hasId() {
        return isset($this->attributes['id']);
    }

    /**
     * Sets the id-attribute of this Element
     * 
     * @param string $id
     */
    public function setId($id) {
        $this->setAttribute('id', $id);
    }

}
