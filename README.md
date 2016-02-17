# Feeld

/ This is a very early work-in-progress without any releases, hardly any tests 
  and without proper documentation /

[![Build Status](https://travis-ci.org/broeser/feeld.svg?branch=master)](https://travis-ci.org/broeser/feeld)
[![codecov.io](https://codecov.io/github/broeser/feeld/coverage.svg?branch=master)](https://codecov.io/github/broeser/feeld?branch=master)
[![License](http://img.shields.io/:license-mit-blue.svg)](http://mit-license.org)
[![SemVer 2.0.0](https://img.shields.io/badge/semver-2.0.0-blue.svg)](http://semver.org/spec/v2.0.0.html)

Feeld provides typed field objects that can be used as building blocks to create 
the data model for CLI questionnaires, HTML forms, and much more. Includes 
sanitization and validation and example UI implementations.

## Goals

- Feeld aims for a strict separation between data model and display, it should 
  be possible to display the same fields in HTML, GTK or the CLI with minimal 
  changes to your code
- Feeld aims to be reasonably extensible with your own DataTypes, Field types, 
  Displays (UI components) and other enhancements
- Feeld should be as easy to learn and as easy to use as possible without 
  loosing flexibility

## Note

If your are only interested in the "sanitization and validation" aspect of Feeld
and not in predefined data types and form building blocks, it might be a better
choice to use broeser/sanitor and broeser/wellid – both packages are a little bit
easier to use then Feeld.

## Installation

Feeld works with PHP 5.6 and 7.0.

The package can be installed via composer:

``composer require broeser/feeld``

## How to use

### DataTypes _– A wrapper around sanitization and validation_

A DataType is a combination of a default sanitizer, default validators and some 
basic methods to specify boundaries (e.g. setMinLength() and setMaxLength()).

These DataTypes are supplied with Feeld in the src/DataType-directory:

 - Boolean
 - Country
 - Date
 - Email
 - File
 - FloatingPoint
 - Integer
 - Str (String)
 - URL

#### Sanitization and validation with data types

A value can be sanitized and validated by using data types.

Example:
```PHP
<?php
$myIntType = new \Feeld\DataType\Integer();
$myIntType->setMinLength(2); // 2 digits
$result = $myIntType->validateValue('f9');
if($result->hasErrors()) {
    print($myIntType->getLastSanitizedValue().' was invalid. This is the first error:'.PHP_EOL);
    print($result->firstError()->getMessage().PHP_EOL);
}
```
In the example, an Integer is being set up with the constraint, that
it has to have at least 2 digits. Then the value 'f9' is validated by calling 
**validateValue()**. Because 'f9' is sanitized to the integer value 9 before 
validation starts, the "at least 2 digits rule" is not met and 
$result->hasErrors() is true. **getLastSanitizedValue()** is a shorthand for
**getSanitizer()->filter($value)**. It will always return the last sanitized 
value that has been given to this DataType via validateValue().

A basic understanding of wellid, the component that Feeld uses for validation,
may be helpful, especially concerning ValidationResults and 
ValidationResultSets. Reading [the wellid README](https://github.com/broeser/wellid)
is recommended.

### Fields

Fields are instances of classes that implement **FieldInterface**. Those classes
are located in src/Field/…

They define types of data entry (e. g. "selecting one of several values", 
"checking a box" or "inputting text". Please note that this has nothing to do 
with the UI of the Field, though: "selecting one of several values" might be 
done by clicking on the value or typing it in; for the UI-component of Feeld, 
see [Displays](#displays).

Each Field must be assigned a DataType on construction. It is optional but
recommended to also assign a string identifier to each field upon construction.
That way it is easier to distinguish different Fields.

Example:

```PHP
<?php
$myStringSelector = new Feeld\Field\Select(new Feeld\DataType\URL(), 'myFunnyField1');
$myStringSelector->setRawValue('http://example.org');
if($myStringSelector->validateBool()) {
    print($myStringSelector->getFilteredValue().' is a valid url!');
} else {
    print('Invalid URL');
}
```

This example defines that the user can select one of multiple URLs. A selection
of http://example.org was made. Because that is a valid url, the message below
is displayed. The message uses the "filtered value" (sanitized value).

For HTML form input or input via an API the rawValueFromInput()-method can be used:

```PHP
<?php
$myStringSelector = new Feeld\Field\Select(new Feeld\DataType\URL());
$myStringSelector->rawValueFromInput(INPUT_REQUEST, 'url');
if($myStringSelector->validateBool()) {
    print($myStringSelector->getFilteredValue().' is a valid url!');
} else {
    print('Invalid URL');
}
```

These Fields are supplied with Feeld:

 - Checkbox     – data entry by checked-or-not-checked-principle/yes-or-no-principle
 - CloakedEntry – cloaked data entry, the UI shall not display the same data as is entered (e.g. password fields)
 - Constant     – user input (if any) is ignored and a constant value is used instead
 - Entry        – default data entry
 - Select       – data entry by selecting one (or more) of several values


If you want to create your own Fields, you can either use the 
**CommonProperties\Field**-trait (in combination with the 
\Wellid\SanitorBridgeTrait if your field shall be sanitizable and validatable)
or extend the abstract class **AbstractField** which uses those traits.

### Displays

Displays are instances of classes implementing **DisplayInterface**. Those 
classes are located in src/Display/…

They can be used to display the UI for Fields (of the field itself, not necessarily
of field values). This can be in form of a question string ('Are you sure [y/N]?'),
in form of HTML ('\<input type="checkbox" name="sure" value="y">'), a 
GtkEntry-widget or any other form you can think of.

A **SymfonyConsoleDisplay** is provided for usage of Feeld with the Symfony
Console component.

Note, that one Display instance only displays one Field, so for a form with two
Fields you'll also need two Displays.

You can stringify Displays ( **\__toString()**, e. g. echo (string)$myDisplay;).
For Displays where a string representation does not make sense, something like
a var_dump may be returned.

While not very useful, Displays can be used completely without Fields:

```PHP
<?php
   $myDisplay = new Feeld\Display\HTML\Input('email');
   print($myDisplay); // will print <input type="email">
```

Usually, Displays are used as UI for Fields though. Setting up an existing 
Display as UI for a Field can be done by calling the 
**setDisplay()**-method:

```PHP
<?php
   $myDisplay = new Feeld\Display\HTML\Input('email');
   print($myDisplay); // will print <input type="email">

   $myField = new Feeld\Field\Entry(new Feeld\DataType\Email());
   $myField->setRequired();
   $myField->setDisplay($myDisplay);

   print($myDisplay); // will – hopefully – print something like 
                      // <input type="email" required>
```

If you prefer an approach that does not couple the Field with the Display at all
you can use **$myDisplay->informAboutStructure($myField)** in the example above
instead of the setDisplay-call.

You can also specify the Display as parameter when constructing a Field:

Example:

```PHP
<?php
$myStringSelector = new Feeld\Field\Select(new Feeld\DataType\URL(), 'yourhomepage', new Feeld\Display\HTML\Input('radio'));
$myStringSelector->addOption('http://example.org');
$myStringSelector->addOption('invalidoption');
$myStringSelector->rawValueFromInput(INPUT_REQUEST, 'url');
if($myStringSelector->validateBool()) {
    print($myStringSelector->getFilteredValue().' is a valid url!');
} else {
    print('Invalid URL. Please select a valid URL!');
    print($myStringSelector);
   /* returns something like <input type="radio" name="" value="http://example.org"><input type="radio" value="invalidoption"> */
}
```

The example defines that the user can select one of multiple URLs. To display
this selection, \<input type="radio">-HTML-tags are used.


### FieldCollections

Fields can be grouped in a **FieldCollection**. If you want to write your own
collection of Fields, make sure to implement **FieldCollectionInterface**. You
can use the **FieldCollectionTrait** to have some basic code.

Fields can be added to a FieldCollection on construction or with the 
**addField()** / **addFields()**-methods. It is possible to retrieve collections
 of mandatory/required Fields ( **getMandatoryFields()**), get a certain Field 
by id ( **getFieldById()**), by class name of its DataType 
( **getFieldsByDataType()**) or by class name of the Field itself.

FieldCollections are Countable and Iterable.

If you also use UI (Displays), you can use the method 
**setFieldDisplay($fieldId, $fieldDisplay)** to specify a Display for a Field
in the collection with the given field-identifier.

It is possible to **validate()** the whole FieldCollection at once. The values
of the validated fields will be stored in an object (\stdClass() by default, can
be configured by assigning a ValueMapper (see below)). Answers can be retrieved 
after validation with **getValidAnswers()**. As the name of the method says, 
only values that have passed validation will be contained in the answer object.

### ValueMappers and ValueMapStrategies

A **ValueMapper** sets properties of an object to a certain value. While the most
important use case is defining how validated values from a FieldCollection shall
be stored, ValueMappers can also be used without FieldCollections.

In this simple example the ValueMapper sets the property "email" of a MyClass-
object, but none of the other properties.
 
```PHP
<?php
use Feeld\FieldCollection\ValueMapper;
use Feeld\FieldCollection\ValueMapStrategy;

class MyClass {
   public $name;
   protected $email;
   private $internalCounter;
}

$valueMapper = new \ValueMapper(new MyClass(), ValueMapStrategy::MAP_REFLECTION, array('email'));

// Sets the email
$valueMapper->set('email' => 'test@example.org');

// Does nothing and returns false, because "name" is not registered with the ValueMapper
$valueMapper->set('name' => 'MyName');
```

The first parameter of the constructor takes the object whose properties should
be changed. The second parameter is the default type of a ValueMapStrategy. This
means that the ValueMapper can use different techniques to set values:
 1. MAP_REFLECTION: Uses Reflection, can be used to set private/protected properties
 2. MAP_PUBLIC: Can be used to set public properties (default)
 3. MAP_SETTER: Uses a setter method (the default for this example would be "setEmail", can be configured)

The third (optional) parameter is an array of all properties that should be
handled by the ValueMapper. Additional properties can be added by using the
**addProperty('name')**-method. That method also allows to use a different 
**ValueMapStrategy** for each property:

```PHP
<?php
use Feeld\FieldCollection\ValueMapper;
use Feeld\FieldCollection\ValueMapStrategy;

class MyClass {
   private $mailAdress;
   public $homepage;
   public $name;

   public function saveEmailAndStuff($mail) {
      $this->mailAddress = $mail;

      if($mail==='red_flag@donotuse.example.org') {
         $message = new InformManager('Someone used the secret email!');
         $message->send();
      }
   }

}

$valueMapper = new ValueMapper(new MyClass(), ValueMapStrategy::MAP_PUBLIC, array(
    'name',
    'url' => 'homepage'
));

$valueMapper->addProperty('email', new ValueMapStrategy(ValueMapStrategy::MAP_SETTER, 'saveEmailAndStuff'));

// calls saveEmailAndStuff with with parameter mail@example.org
$valueMapper->set('email', 'mail@example.org');

// Sets the public property $homepage to http://example.org
$valueMapper->set('url', 'http://example.org');

// Sets the public property name to MyName
$valueMapper->set('name', 'MyName');
```

A ValueMapper can be assigned to a FieldCollection by calling 
**addValueMapper($valueMapper)** on the FieldCollection. 
This should be done before validation.

It is possible to assign an id to a ValueMapper to distinguish several different
ValueMappers: **setId()**, **hasId()** and **getId()** can be used.


### Interviews

Interviews usually build upon FieldCollections. They present each Field 
contained within their FieldCollections to the user, in the form of a question.
The user is invited to answer these questions, the answers are sanitized,
validated and can be stored.

After construction the main entry point of the Interview is the
**execute()**-method.

As soon as an Interview-class is **execute()**ed, it manages the logic behind:
 - inviting an user to answer questions ( **inviteAnswers()**)
 - retrieving the answers from the user ( **retrieveAnswers()**)
 - sanitizing and validating these answers
 - doing different things **onValidationError()** and **onValidationSuccess()**
 - setting a status code, depending on whether the answers were valid or not,
   retrievable via **getStatus()**
 - optionally branch to another set of answers or conclude the interview

You can use the **InterviewInterface** in conjunction with the **InterviewStatusTrait**
 to create your own logic how those steps shall work exactly. If you prefer 
extending an abstract class, you can use **AbstractInterview**.

For a multi-page/branching Interview, use the **TreeInterviewInterface** 
(instead of InterviewInterface) and **TreeInterviewTrait**.
An **AbstractTreeInterview** is provided as well.

There are currently two example implementations of Interviews available:
 - HTMLForm
 - SymfonyConsole

**HTMLForm** poses
questions in the context of an HTML5 form. It handles the above steps in the
following way:
 - inviting an user to answer questions: by displaying them in the source code
 - retrieving the answers from the user: by using filter_input()
 - sanitizing and validating these answers: by using the Fields (and their
   assigned validators/sanitizer) in the FieldCollection that is assigned to the
   Interview
 - onValidationError: All error messages are displayed in an unordered list
   onValidationSuccess: A success message is displayed
 - Status codes: STATUS_VALIDATION_ERROR (invalid data), STATUS_AFTER_INTERVIEW 
   (success) or STATUS_BEFORE_INTERVIEW (form was not submitted yet)

**SymfonyConsole** uses the Symfony Console component to pose the questions.

You can find a working examples of both Interview implementations in the
examples/-directory (example_html5_form.php and example_symfony_console.php).
The latter can be run by:

```
php example_symfony_console.php run
```


## Feeld?

It's a pun on „That feels right“ and „Field“.