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
 * Description of FieldCollection
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
interface FieldCollectionInterface extends \Countable, \Feeld\Display\DisplayDataSourceInterface {
    const VALUE_MAPPER_DEFAULT_ID = 'default';    
    
    /**
     * Adds several Fields to the FieldCollection
     * 
     * @param Feeld\FieldInterface ...$fields
     * @return FieldCollection Returns itself for daisy-chaining
     */
    public function addFields(\Feeld\FieldInterface ...$fields);
    
    /**
     * Adds a Field to the FieldCollection
     * 
     * @param Feeld\FieldInterface $field
     * @return FieldCollection Returns itself for daisy-chaining
     */
    public function addField(\Feeld\FieldInterface $field);
    
    /**
     * Returns all fields
     * 
     * @return Feeld\FieldInterface[]
     */
    public function getFields();
    
    /**
     * Retrieves a Field by unique identifier
     * 
     * @param string $id
     * @return \Feeld\FieldInterface
     */    
    public function getFieldById($id);
    
    /**
     * Validates all Fields against the assigned Validators
     * 
     * @return \Wellid\ValidationResultSet
     */
    public function validate();
    
    /**
     * Returns all Fields of a certain data type in a FieldCollection
     * 
     * @param string $class
     * @return FieldCollection
     */
    public function getFieldsByDataType($class);   
    
    /**
     * Returns an object containing all valid answers as public properties
     * identified by the id of the corresponding Field
     * 
     * If more then one ValueMappers are assigned, an array of objects is
     * returned
     * 
     * @return object|object[]
     */
    public function getValidAnswers();
    
    /**
     * Sets a ValueMapper
     * 
     * @param \Feeld\FieldCollection\ValueMapper $valueMapper
     */
    public function addValueMapper(ValueMapper $valueMapper);    
}
