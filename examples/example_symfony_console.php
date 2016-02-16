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

// run via
// 
// php example_symfony_console.php run

/**
 * Example for using Symfony Console Component with Feeld
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class SymfonyCommand extends Symfony\Component\Console\Command\Command {    
    /**
     * Executes the command
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {        
        // Data model:
        // A FieldCollection that holds all the Fields.
        // Have a look into the BlogPostand BlogPostFieldCollection-classes for more 
        // details
        $formForBlogPost = new FeeldUsageExamples\BlogPostFieldCollection();

        // UI
        $nicknameUI = new \Feeld\Display\CLI\SymfonyConsoleDisplay();
        $emailUI = new \Feeld\Display\CLI\SymfonyConsoleDisplay();
        $textUI = new \Feeld\Display\CLI\SymfonyConsoleDisplay();

        // Putting data model and UI together
        $formForBlogPost->setFieldDisplay('nickname', $nicknameUI);
        $formForBlogPost->setFieldDisplay('email', $emailUI);
        $formForBlogPost->setFieldDisplay('text', $textUI);
        
        // The Interviewer "SymfonyConsole" is executed
        $interview = new Feeld\Interview\SymfonyConsole($input, $output, $this->getHelper('question'), $formForBlogPost);
        $interview->execute();

        // Proof that it worked (not really necessary)
        if($interview->getStatus()===Feeld\Interview\InterviewInterface::STATUS_AFTER_INTERVIEW) {
            $output->writeln('Most recent blog post:');
            $output->writeln((string)$formForBlogPost->getValidAnswers());
        }
    }
}

// Setting up the Application itself, not part of the example
$app = new \Symfony\Component\Console\Application('Feeld Example');
$app->add(new SymfonyCommand('run'));
$app->run();