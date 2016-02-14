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
 * Description of ValueMapStrategy
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class ValueMapStrategy { 
    /**
     * Ignore this property
     */
    const MAP_IGNORE = 0;
    
    /**
     * Usa a public property with the name $name
     */
    const MAP_PUBLIC = 1;
    
    /**
     * Use a setter method with the name $name
     */
    const MAP_SETTERS = 2;
    
    /**
     * Use reflection to set value
     */
    const MAP_REFLECTION = 4;
 
    /**
     * One of the MAP_PUBLIC or MAP_SETTERS constants
     * 
     * @var int
     */
    protected $type;
    
    /**
     * Name of the public property or setter method
     * 
     * @var string
     */
    protected $name;
    
    /**
     * Constructor
     * 
     * @param int $type
     * @param string $name
     * @throws \Wellid\Exception\DataType
     * @throws \Wellid\Exception\DataFormat
     */
    public function __construct($type, $name) {
        if(!is_int($type)) {
            throw new \Wellid\Exception\DataType('type', 'int', $type);
        }
        
        if(!in_array($type, self::getSupportedStrategies())) {
            throw new \Wellid\Exception\DataFormat('type', 'supported MAP_-constant', $type);
        }
        
        if(!is_string($name)) {
            throw new \Wellid\Exception\DataType('name', 'string', $name);
        }
        
        $this->type = $type;
        $this->name = $name;
    }
    
    /**
     * Sets a value in an object
     * 
     * @param object $object
     * @param mixed $value
     */
    public function set($object, $value) {
        switch($this->type) {
            case self::MAP_IGNORE:
            break;
            case self::MAP_PUBLIC:
                $object->{$this->name} = $value;
            break;
            case self::MAP_SETTERS:
                $object->{$this->name}($value);
            break;
            case self::MAP_REFLECTION:
                $reflect = new \ReflectionClass($object);
                $property = $reflect->getProperty($this->name);
                $property->setAccessible($this->name);
                $property->setValue($object, $value);
        }
    }

    /**
     * Returns an array of supported mapping strategies
     * 
     * @return int[]
     */
    public static function getSupportedStrategies() {
        return array(self::MAP_IGNORE, self::MAP_PUBLIC, self::MAP_SETTERS, self::MAP_REFLECTION);
    }
}
