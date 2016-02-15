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

namespace Feeld\FieldCollection;

/**
 * Description of ValueMapper
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class ValueMapper  implements \Feeld\Field\CommonProperties\IdentifierInterface {
    use \Feeld\Field\CommonProperties\Identifier;
    
    /**
     * Object
     * 
     * @var object
     */
    protected $object;
    
    /**
     * Array in the format propertyName => ValueMapStrategy
     * 
     * @var ValueMapStrategy[]
     */
    protected $mappedProperties;
    
    /**
     * Default ValueMapStrategy-type (one of the MAP_...-constants)
     * 
     * @var int
     */
    protected $defaultStrategy;
    
    /**
     * Constructor
     * 
     * @param object $object
     * @param int $defaultStrategy
     * @param string[] $mappedProperties
     * @throws \Wellid\Exception\DataType
     * @throws \Wellid\Exception\DataFormat
     */
    public function __construct($object, $defaultStrategy = ValueMapStrategy::MAP_PUBLIC, array $mappedProperties = array()) {
        if(!is_object($object)) {
            throw new \Wellid\Exception\DataType('object', 'object', $object);
        }
        
        if(!is_int($defaultStrategy)) {
            throw new \Wellid\Exception\DataType('defaultStrategy', 'int', $defaultStrategy);
        }
        
        if(!in_array($defaultStrategy, ValueMapStrategy::getSupportedStrategies())) {
            throw new \Wellid\Exception\DataFormat('defaultStrategy', 'supported MAP_-constant', $defaultStrategy);
        }
        
        $this->object = $object;
        $this->defaultStrategy = $defaultStrategy;
        
        foreach($mappedProperties as $fieldId => $propertyName) {
            if(!is_string($fieldId)) {
                $this->addProperty($propertyName);
            } else {
                $this->mappedProperties[$fieldId] = $this->createDefaultValueMapStrategy($propertyName);
            }
        }
    }
    
    /**
     * Returns a ValueMapStrategy that matches the default type specified
     * upon construction of this ValueMapper
     * 
     * @param string $propertyName
     * @return \Feeld\FieldCollection\ValueMapStrategy
     */
    private function createDefaultValueMapStrategy($propertyName) {
        return new ValueMapStrategy($this->defaultStrategy, $this->defaultStrategy===ValueMapStrategy::MAP_SETTERS?('set'.ucfirst($propertyName)):$propertyName);
    }
    
    /**
     * Adds a property to this ValueMapper
     *
     * @param string $fieldId Identifies the property
     * @param \Feeld\FieldCollection\ValueMapStrategy $vmStrategy Optional ValueMapStrategy, if omitted the default strategy of this ValueMapper is used
     * @throws \Wellid\Exception\DataType
     */
    public function addProperty($fieldId, \Feeld\FieldCollection\ValueMapStrategy $vmStrategy = null) {
        if(!is_string($fieldId)) {
            throw new \Wellid\Exception\DataType('fieldId', 'string', $fieldId);
        }
        
        if(is_null($vmStrategy)) {
            $vmStrategy = $this->createDefaultValueMapStrategy($fieldId);
        }
        
        $this->mappedProperties[$fieldId] = $vmStrategy;
    }
    
    /**
     * Returns whether this ValueMapper has a certain property
     * 
     * @param string $property
     * @return boolean
     * @throws \Wellid\Exception\DataType
     */
    public function hasProperty($property) {
        if(!is_string($property)) {
            throw new \Wellid\Exception\DataType('property', 'string', $property);
        }
        
        return isset($this->mappedProperties[$property]);
    }
    
    /**
     * Sets a property to a certain value or returns false if the property is
     * not known
     * 
     * @param string $property
     * @param string $value
     * @return boolean
     */
    public function set($property, $value) {
        if(!$this->hasProperty($property)) {
            return false;
        }
        $this->mappedProperties[$property]->set($this->object, $value);
        
        return true;
    }
    
    /**
     * Returns the object
     * 
     * @return object
     */
    public function getObject() {
        return $this->object;
    }
}
