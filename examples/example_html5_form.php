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
require_once __DIR__.'/../vendor/autoload.php';

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="bootstrap.min.css">
        <title>Blog Example</title>
    </head>
    <body class="container">
        <h1>Blog Example</h1>
<?php
use Feeld\Display\HTML;
use Feeld\Interview;
use FeeldUsageExamples\BootstrapFormGroup as Group;
use FeeldUsageExamples\BootstrapDiv as Div;

// Data model:
// A FieldCollection that holds all the Fields.
// Have a look into the BlogPostand BlogPostFieldCollection-classes for more 
// details
$formForBlogPost = new FeeldUsageExamples\BlogPostFieldCollection();

// An ErrorContainer for validation errors:
$validationErrors = new Interview\ErrorContainer();

// UI
$nicknameUI = new Group(
                (new HTML\Label('Your Name'))->addCssClass('col-sm-2'), 
                (new Div((new HTML\Input('text'))->setAttribute('placeholder', 'Your username')))->addCssClass('col-sm-10')
              );

$emailUI = new Group(
                (new HTML\Label('Your Email'))->addCssClass('col-sm-2'),
                (new Div(new HTML\Input('email')))->addCssClass('col-sm-10')
           );

$textUI = new Group(
                (new HTML\Label('Your Text'))->addCssClass('col-sm-2'),
                (new Div(new HTML\Textarea))->addCssClass('col-sm-10')
          );

$formUI = (new HTML\Form('post'))->addCssClass('form-horizontal');
$errorDiv = new HTML\ErrorContainer();
$successDiv = (new Div())->addCssClass('has-success')->setContent('<p>Your blog entry was submitted!</p>');
$formUI->appendChildren($nicknameUI, $emailUI, $textUI);
$formUI->appendChild((new HTML\Button('submit'))->setContent('Publish!'));

// Putting data model and UI together
$formForBlogPost->setDisplay($formUI);
$formForBlogPost->setFieldDisplay('nickname', $nicknameUI);
$formForBlogPost->setFieldDisplay('email', $emailUI);
$formForBlogPost->setFieldDisplay('text', $textUI);
$validationErrors->setDisplay($errorDiv);

// The Interviewer "HTMLForm" is executed
$htmlForm = new Interview\HTMLForm(0, $validationErrors, $formForBlogPost);
$htmlForm->setSuccessDisplay($successDiv);
$htmlForm->execute();

// Display error messages first, in case something goes wrong
print($validationErrors);

// Display success message on successful form submission
print($successDiv);

// Display form fields (if applicable)
print($htmlForm->getCurrentCollection());

// Proof that it worked (not really necessary)
if($htmlForm->getStatus()===Interview\InterviewInterface::STATUS_AFTER_INTERVIEW) {
    $article = new HTML\Element('article');
    $article->addCssClass('blog');
    $article->appendChild((new HTML\Element('h2'))->setContent('Most recent blog post'));
    $article->appendChild(new HTML\TextNode(nl2br($htmlForm->getCurrentValidAnswers())));
    print($article);
}
?></body></html>