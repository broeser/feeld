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
namespace Feeld\Field\CommonProperties;

/**
 * To be used in any field where a selection out of several options can be made
 * 
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait Options {
	/**
     * Selectable options
     * 
     * @var array
     */
    protected $options = array();
	
    /**
     * Returns all selectable options
     * 
     * @return array
     */
    public function getOptions() {
        return $this->options;
    }
    
	/**
     * Adds an option
     * 
     * @param mixed $text
     * @param mixed $value internal value
     * @return OptionsInterface Returns itself for daisy-chaining
     */
    public function addOption($text, $value = null) {
        if(is_null($value)) {
            $this->options[] = $text;
        } else {
            $this->options[$value] = $text;
        }

        return $this;
	}
	
	/**
	 * Adds an array of options in the format value1 => text1, value2 => text2,...
	 * 
	 * @param string[] $options
     * @param boolean $valueIsText Whether the same value shall be used for $value as for $text
     * @return OptionsInterface Returns itself for daisy-chaining
	 */
	public function addOptions(array $options, $valueIsText = false) {
		foreach($options as $value => $text) {
			$this->addOption($text, $valueIsText?$text:$value);
		}
		
		return $this;
	}
}

