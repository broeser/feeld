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
 * Description of Label
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Label extends Element {
    /**
     * Constructor
     * 
     * @param HTMLDisplayInterface$fieldDisplayElement
     * @param string $labelText
     * @throws \Wellid\Exception\DataType
     * @throws \Wellid\Exception\NotFound
     */
    public function __construct(HTMLDisplayInterface $fieldDisplayElement, $labelText = null) {
        if($fieldDisplayElement instanceof Form || $fieldDisplayElement instanceof ErrorContainer) {
            throw new \Wellid\Exception\DataType('fieldDisplayElement', 'may not be Form or ErrorContainer', $fieldDisplayElement);
        }
        
        if(!isset($fieldDisplayElement->attributes['id']) || $fieldDisplayElement['id']==='') {
            throw new \Wellid\Exception\NotFound('id', 'fieldDisplayElement');
        }
        
        parent::__construct('label');
        $this->setAttribute('for', $fieldDisplayElement->attributes['id']);
        if(!is_null($labelText)) {
            $this->setContent($labelText);
        }
    }
}
