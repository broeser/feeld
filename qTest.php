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
require_once __DIR__.'/vendor/autoload.php';

$myIntType = new \Feeld\DataType\Integer();
$myIntType->setMinLength(2); // 2 digits
$result = $myIntType->validateValue('f9');
if($result->hasErrors()) {
    print($myIntType->getLastSanitizedValue().' was invalid. This is the first error:'.PHP_EOL);
    print($result->firstError()->getMessage().PHP_EOL);
}

$myStringSelector = new Feeld\Field\Select(new Feeld\DataType\URL());
$myStringSelector->setRawValue('http://example.org');

if($myStringSelector->validateBool()) {
    print($myStringSelector->getFilteredValue().' is a valid url!'.PHP_EOL);
} else {
    print('Invalid URL'.PHP_EOL);
}
 
$myIntType = new \Feeld\DataType\Integer();
$myIntType->setMinLength(2); // 2 digits
$valueToValidate = 'f9';
$result = $myIntType->validateValue($valueToValidate);
if($result->hasErrors()) {
    print($myIntType->getSanitizer()->filter($valueToValidate).' is invalid. First error message: '.PHP_EOL);
    print($result->firstError()->getMessage().PHP_EOL);
}

$myStringSelector = new Feeld\Field\Select(new Feeld\DataType\URL(), 'yourhomepage', new Feeld\Display\HTML\Input('radio'));
$myStringSelector->addOption('http://example.org');
$myStringSelector->addOption('invalidoption');
$myStringSelector->rawValueFromInput(INPUT_REQUEST, 'url');
if($myStringSelector->validateBool()) {
    print($myStringSelector->getFilteredValue().' is a valid url!');
} else {
    print('Invalid URL');
}
