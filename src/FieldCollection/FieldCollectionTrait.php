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
use Feeld\FieldInterface;
use Feeld\Field\CommonProperties\IdentifierInterface;
use Wellid\ValidationResultSet;
/**
 * Description of FieldCollection
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait FieldCollectionTrait {
    use \Wellid\Cache\CacheValidationResultSetTrait, \Feeld\Display\DisplayDataSourceTrait;
    
    /**
     * Array of Fields in this FieldCollection
     * 
     * @var FieldInterface[]
     */
    protected $fields;
    
    /**
     * ValueMapper
     * 
     * @var ValueMapper[]
     */
    protected $valueMapper = array();
    
    /**
     * Internal Iterator index
     * 
     * @var integer
     */
    private $position = 0;
    
    /**
     * Adds several Fields to the FieldCollection
     * 
     * @param FieldInterface ...$fields
     * @return FieldCollection Returns itself for daisy-chaining
     */
    public function addFields(FieldInterface ...$fields) {
        foreach($fields as $field) {
            $this->addField($field);
        }
        
        return $this;
    }
    
    /**
     * Adds a Field to the FieldCollection
     * 
     * @param FieldInterface $field
     * @return FieldCollection Returns itself for daisy-chaining
     */
    public function addField(FieldInterface $field) {
        $this->fields[] = $field;
        
        return $this;
    }
    
    /**
     * Returns all fields
     * 
     * @return FieldInterface[]
     */
    public function getFields() {
        return $this->fields;
    }
    
    /**
     * Retrieves a Field by unique identifier
     * 
     * @param string $id
     * @return FieldInterface
     */    
    public function getFieldById($id) {
        foreach($this->getFields() as $field) {
            if($field instanceof IdentifierInterface && $field->hasId() && $field->getId()===$id) {
                return $field;
            }
        }
        
        return null;
    }
    
    /**
     * Sets a ValueMapper defaulting to mapping all Fields of this FieldCollection
     * with the same ValueMapStrategy
     * 
     * @param object $object
     * @param int $strategy
     */
    public function addDefaultValueMapper($object, $strategy = ValueMapStrategy::MAP_PUBLIC) {
        $mappedProperties = array();
        foreach($this->getFields() as $field) {
            if($field instanceof IdentifierInterface && $field->hasId()) {
                $mappedProperties[] = $field->getId();
            }
        }
        $vm = new ValueMapper($object, $strategy, $mappedProperties);
        $vm->setId(FieldCollectionInterface::VALUE_MAPPER_DEFAULT_ID);
        $this->addValueMapper($vm);
    }
    
    /**
     * Validates all Fields and returns a ValidationResultSet
     * 
     * @return ValidationResultSet
     */
    public function validate() {
        if($this->isValidationCacheEnabled() && $this->lastValidationResult instanceof ValidationResultSet) {
            return $this->lastValidationResult;
        }
        
        if(count($this->valueMapper)<1) {
            $this->addDefaultValueMapper(new \stdClass());
        }
        
        $validationResultSet = new ValidationResultSet();
        foreach($this->fields as $field) {
            $set = $field->validate();
            $validationResultSet->addSet($set);
            if($set->hasPassed()) {
                $fieldId = $field->getId();
                $value = $field->getDataType()->transformSanitizedValue($field->getFilteredValue());
                foreach($this->valueMapper as $vm) {
                    $vm->set($fieldId, $value);
                }
            }
        }
        
        $this->lastValidationResult = $validationResultSet;
        
        return $validationResultSet;
    }
    
    /**
     * Returns an object containing all valid answers as public properties
     * identified by the id of the corresponding Field
     * If more then one ValueMapper is assigned, an array of objects will be
     * returned
     * 
     * NOTE: Won't work if called before validate()
     * 
     * @return object|object[]
     */
    public function getValidAnswers() {
        if(count($this->valueMapper)===1) {
            $vm = end($this->valueMapper);
            return $vm->getObject();
        }
        
        $retArray = array();
        foreach($this->valueMapper as $vm) {
            if($vm->hasId()) {
                $retArray[$vm->getId()] = $vm->getObject();
            } else {
                $retArray[] = $vm->getObject();    
            }
        }
        
        return $retArray;
    }

    /**
     * Returns all Fields of a certain data type
     * 
     * @param string $class
     * @return FieldCollection
     */
    public function getFieldsByDataType($class) {
        $retCollection = new FieldCollection();
        foreach($this->getFields() as $field) {
            if(get_class($field->getDataType())===$class) {
                $retCollection->addField($field);
            }
        }
        return $retCollection;        
    }
    
    
    /**
     * Sets a ValueMapper
     * 
     * @param \Feeld\FieldCollection\ValueMapper $valueMapper
     */
    public function addValueMapper(ValueMapper $valueMapper) {
        if(!$valueMapper->hasId()) {
            $this->valueMapper[] = $valueMapper;
        } else {
            $this->valueMapper[$valueMapper->getId()] = $valueMapper;
        }
    }   
    
    /* Countable-methods */
    
    /**
     * Returns the number of fields in this collection
     * 
     * @return int
     */
    public function count() {
        return count($this->fields);
    }    
}
