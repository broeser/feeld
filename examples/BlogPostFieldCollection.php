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

use Feeld\Field;
use Feeld\DataType;
use Feeld\FieldCollection\ValueMapStrategy;

/**
 * A FieldCollection that holds all the Fields necessary to create or edit a
 * BlogPost
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class BlogPostFieldCollection extends \Feeld\FieldCollection\FieldCollection {
    public function __construct() {
        parent::__construct();
        
        // Add the Fields that are part of this FieldCollection and necessary
        // to create/edit a blog post
        // 
        // This is independent of Display/UI
        $this->addFields(
            (new Field\Entry((new DataType\Str())->setMinLength(3), 'nickname'))->setRequired(),
            (new Field\Entry(new DataType\Email(), 'email'))->setDefault('mail@example.org'),
            (new Field\Entry(new DataType\Str(), 'text'))->setRequired()
        );
        
        // A ValueMapper to map Fields to properties of an object:
        // All Fields of the FieldCollection will use the same ValueMapStrategy and their ID as object's property ("DefaultValueMapper")
        // form data will be inserted into a new BlogPost() – properties are public, therefore the MAP_PUBLIC-strategy can be used
        $this->addDefaultValueMapper(new BlogPost(), ValueMapStrategy::MAP_PUBLIC);
    }
}
