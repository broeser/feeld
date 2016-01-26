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

namespace Feeld\Display\CLI;
use Feeld\Field\CommonProperties\DefaultValueInterface;
use Feeld\Field\CommonProperties\OptionsInterface;
/**
 * Description of Default-CLI-Display
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class DefaultCLI implements \Feeld\Display\DisplayInterface {
    /**
     * A short question to be asked
     * 
     * @var string
     */
    protected $question;
    
    /**
     * Long description of the short question
     * 
     * @var string
     */
    protected $moreInfo;
    
    /**
     * A hint what should be entered, what other users have entered, what is
     * usually entered or which options are available
     * 
     * @var string
     */
    protected $hint;
    
    /**
     * Returns a string representation of the display
     * 
     * @return string
     */
    public function __toString() {
        return $this->question.'? '.(is_null($this->hint)?'':'['.$this->hint.'] ');
    }

     /**
     * Informs the display about the structure of a Field.
     * 
     * Each time the Field structure changes for some reason, this method shall
     * be called.
     *
     * The display changes its hint (string between square brackets []) 
     * accordingly
     * 
     * @param \Feeld\FieldInterface $field
     */
    public function informAboutStructure(\Feeld\FieldInterface $field) {
        if($field instanceof DefaultValueInterface && $field->hasDefault()) {
            $this->hint = $field->getDefault();
        }
        
        if($field instanceof OptionsInterface) {
            if($field instanceof DefaultValueInterface && $field->hasDefault()) {
                $tmpOptions = array();
                foreach($field->getOptions() as $option) {
                    if($field->getDefault()===$option) {
                        $tmpOptions[] = strtoupper($option);
                    } else {
                        $tmpOptions[] = strtolower($option);
                    }
                }
                $this->hint = implode('/', $tmpOptions);
            } else {
                $this->hint = implode('/', $field->getOptions());
            }
        }
    }
}
