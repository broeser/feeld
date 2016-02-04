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
     * An object containing all valid answers of this FieldCollection
     * 
     * @var object
     */
    protected $validAnswers;
    
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
     */
    public function addField(FieldInterface $field) {
        $this->fields[] = $field;
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
     * Validates all Fields and returns a ValidationResultSet
     * 
     * @return ValidationResultSet
     */
    public function validate() {
        if($this->isValidationCacheEnabled() && $this->lastValidationResult instanceof ValidationResultSet) {
            return $this->lastValidationResult;
        }
        
        $validationResultSet = new ValidationResultSet();
        foreach($this->fields as $field) {
            $set = $field->validate();
            $validationResultSet->addSet($set);
            if($set->hasPassed() && $field instanceof IdentifierInterface && $field->hasId()) {
                $fieldId = $field->getId();
                $this->validAnswers->$fieldId = $field->getDataType()->transformSanitizedValue($field->getFilteredValue());
            }
        }
        
        $this->lastValidationResult = $validationResultSet;
        
        return $validationResultSet;
    }
    
    /**
     * Returns an object containing all valid answers as public properties
     * identified by the id of the corresponding Field
     * 
     * NOTE: Won't work if called before validate()
     * 
     * @return object
     */
    public function getValidAnswers() {
        return $this->validAnswers;
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
