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

namespace Feeld\Display;

/**
 * Classes implementing DisplayInterface (short: Displays) are used to display
 * Fields (the UI of the field, not their values!). This can be in form of a
 * question string ('Are you sure [y/N]?'), in form of HTML 
 * ('<input type="checkbox" name="sure" value="y">'), a GtkEntry-widget or any 
 * other form you can think of
 * 
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
interface DisplayInterface {
    /**
     * Informs the display about the structure of a Field. The display may
     * decide to change its layout depending on this information, e. g. it might
     * add a note "This is a required field" for required fields.
     * 
     * It is allowed to ignore the information.
     * 
     * Each time the Field structure changes for some reason, this method shall
     * be called.
     * 
     * @param DisplayDataSourceInterface $field
     */
    public function informAboutStructure(DisplayDataSourceInterface $field);
        
    /**
     * Makes this display invisible
     */
    public function setInvisible();
    
    
    /**
     * Makes this display visible
     * Displays are visible by default
     */
    public function setVisible();
    
    /**
     * Returns a string representation of the display
     * 
     * @return string
     */
    public function __toString();
}
