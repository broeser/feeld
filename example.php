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
    public $hasNothingToDoWithTheForm;
    
    public function __toString() {
        return $this->nickname.' ('.$this->email.') wrote:'.PHP_EOL.$this->text.PHP_EOL;
    }
}

// Data model:
// A FieldCollection that holds all the Fields:
$formForBlogPost = new \Feeld\FieldCollection\FieldCollection();
$formForBlogPost->addFields(
    (new Feeld\Field\Entry((new Feeld\DataType\Str())->setMinLength(3), 'nickname'))->setRequired(),
    (new Feeld\Field\Entry(new Feeld\DataType\Email(), 'email'))->setDefault('mail@example.org'),
    (new Feeld\Field\Entry(new Feeld\DataType\Str(), 'text'))->setRequired()
);
// A ValueMapper to map Fields to properties of an object:
// All Fields of the FieldCollection will use the same ValueMapStrategy and their ID as object's property ("DefaultValueMapper"); form data will be inserted into a new BlogPost() – properties are public, therefore the MAP_PUBLIC-strategy can be used
$formForBlogPost->addDefaultValueMapper(new BlogPost(), Feeld\FieldCollection\ValueMapStrategy::MAP_PUBLIC);

// An ErrorContainer for validation errors:
$validationErrors = new \Feeld\Interview\ErrorContainer();

// UI
$nicknameUI = (new Feeld\Display\HTML\Input('text'))->setAttribute('placeholder', 'Your username');
$emailUI = new Feeld\Display\HTML\Input('email');
$textUI = new Feeld\Display\HTML\Textarea();
$formUI = new Feeld\Display\HTML\Form('post');
$formUI->surround(null, (new \Feeld\Display\HTML\Button('submit'))->setContent('Publish!'));
$errorDiv = new Feeld\Display\HTML\ErrorContainer();
$successDiv = (new Feeld\Display\HTML\Div())->addCssClass('has-success')->setContent('<p>Your blog entry was submitted!</p>');

// Putting data model and UI together
$formForBlogPost->setDisplay($formUI);
$formForBlogPost->setFieldDisplay('nickname', $nicknameUI);
$formForBlogPost->setFieldDisplay('email', $emailUI);
$formForBlogPost->setFieldDisplay('text', $textUI);
$validationErrors->setDisplay($errorDiv);

// "Business" logic: The Interviewer "HTMLForm" is executed
$htmlForm = new \Feeld\Interview\HTMLForm(0, $validationErrors, $formForBlogPost);
$htmlForm->setSuccessDisplay($successDiv);
$htmlForm->execute();

// Display error messages first, in case something goes wrong
print($validationErrors);

// Display success message on successful form submission
print($successDiv);

// Display form fields (if applicable)
print($htmlForm->getCurrentCollection());

// Proof that it worked (not really necessary)
if($htmlForm->getStatus()===Feeld\Interview\InterviewInterface::STATUS_AFTER_INTERVIEW) {
    print('<h1>Most recent blog post</h1>');
    print(nl2br($htmlForm->getCurrentValidAnswers()));
}
?></body></html>