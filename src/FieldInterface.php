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

namespace Feeld;

/**
 * Interface for all Fields
 * 
 * Currently all Fields have optional default values, an optional identifier and
 * may be mandatory. That's why this interface extends those interfaces.
 * 
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
interface FieldInterface extends \Sanitor\SanitizableInterface, \Wellid\ValidatableInterface,
 Field\CommonProperties\DefaultValueInterface, Field\CommonProperties\IdentifierInterface, Field\CommonProperties\RequiredInterface {
    
    /**
     * Use this method to obtain a field value from GET, POST, etc.
     * 
     * @param int $type INPUT_POST, INPUT_GET, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV, INPUT_REQUEST or INPUT_SESSION
     * @param string $variableName
     */
    public function rawValueFromInput($type, $variableName);
    
    /**
     * Use this method to obtain a field value from CLI, etc.
     * 
     * @param mixed $rawValue
     */
    public function setRawValue($rawValue);
}
