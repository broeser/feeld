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

namespace FeeldUsageExamples;

use Feeld\Display\HTML\Select;
use Feeld\Display\HTML\Textarea;
use Feeld\Display\HTML\Input;

/**
 * Description of BootstrapDiv
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class BootstrapDiv extends \Feeld\Display\HTML\Div {
    public function __construct(\Feeld\Display\HTML\Element ...$elements) {
        parent::__construct();
        foreach($elements as $element) {
            if($element instanceof Input || $element instanceof Select || $element instanceof Textarea) {
                $element->addCssClass('form-control');
            }
            $this->appendChild($element);
        }
    }
}
