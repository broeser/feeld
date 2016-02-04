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
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Blog Example</title>
    </head>
    <body>
        <h1>Blog Example</h1>
<?php
class BlogPost {
    public $nickname;
    public $email;
    public $text;
    
    public function __toString() {
        return $this->nickname.' ('.$this->email.') wrote:'.PHP_EOL.$this->text.PHP_EOL;
    }
}

// Data model
$formForBlogPost = new \Feeld\FieldCollection\FieldCollection(null, new BlogPost());
$formForBlogPost->addFields(
    (new Feeld\Field\Entry((new Feeld\DataType\String())->setMinLength(3), 'nickname'))->setRequired(),
    (new Feeld\Field\Entry(new Feeld\DataType\Email(), 'email'))->setDefault('mail@example.org'),
    (new Feeld\Field\Entry(new Feeld\DataType\String(), 'text'))->setRequired()
);

// UI
$nicknameUI = (new Feeld\Display\HTML\Input('text'))->setAttribute('placeholder', 'Your username');
$emailUI = new Feeld\Display\HTML\Input('email');
$textUI = new Feeld\Display\HTML\Textarea();
$formUI = new Feeld\Display\HTML\Form('post');

// Putting data model and UI together
$formForBlogPost->setDisplay($formUI);
$formForBlogPost->setFieldDisplay('nickname', $nicknameUI);
$formForBlogPost->setFieldDisplay('email', $emailUI);
$formForBlogPost->setFieldDisplay('text', $textUI);

// "Business" logic: The Interviewer "HTMLForm" is executed
$htmlForm = new \Feeld\Interview\HTMLForm($formForBlogPost);
$htmlForm->execute();
if($htmlForm->getStatus()===Feeld\Interview\Interview::STATUS_AFTER_INTERVIEW) {
    print('<h1>Most recent blog post</h1>');
    print(nl2br($htmlForm->getCurrentCollection()->getValidAnswers()));
}
?></body></html>