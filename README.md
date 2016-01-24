# Feeld
Feeld provides typed field objects that can be used as building blocks to create 
the data model for CLI questionnaires, HTML forms, and much more. Includes 
sanitization and validation and example UI implementations.

## Goals

- feeld aims for a strict separation between data model and display, it should 
  be possible to display the same fields in HTML, GTK or the CLI with minimal 
  changes to your code
- feeld aims to be reasonably extensible with your own DataTypes, Field types, 
  Displays (UI components) and other enhancements
- feeld should be as easy to learn and as easy to use as possible without 
  loosing flexibility

## Note

If your are only interested in the "sanitization and validation" aspect of Feeld
and not in predefined data types and form building blocks, it might be a better
choice to use broeser/sanitor and broeser/wellid – both packages are a little bit
easier to use then Feeld.

## Installation

The package is called broeser/feeld and can be installed via composer:

`composer require broeser/feeld`

## How to use

### DataTypes _– A wrapper around sanitization and validation_

A DataType is a combination of a default sanitizer, default validators and some 
basic methods to specify boundaries (e.g. setMinLength() and setMaxLength()).

These DataTypes are supplied with feeld:

 - Country
 - Date
 - Email
 - File
 - Float
 - Integer
 - String
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
are are located in src/Field/…

They define types of data entry (e. g. "selecting one of several values", 
"checking a box" or "inputting text". Please note that this has nothing to do 
with the UI of the Field, though: "selecting one of several values" might be 
done by clicking on the value or typing it in; for the UI-component of Feeld, 
see [Displays](#displays).

Each Field must be assigned a DataType on construction.

Example:

```PHP
<?php
$myStringSelector = new Feeld\Field\Select(new Feeld\DataType\URL());
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

These Fields are supplied with feeld:

 - Checkbox     – data entry by checked-or-not-checked-principle/yes-or-no-principle
 - CloakedEntry – cloaked data entry, the UI shall not display the same data as is entered (e.g. password fields)
 - Constant     – user input (if any) is ignored and a constant value is used instead
 - Entry        – default data entry
 - Select       – data entry by selecting one (or more) of several values

### Displays

Displays are instances of classes implementing **DisplayInterface**. Those 
classes are located in src/Display/…

They can be used to display the UI for Fields (of the field itself, not necessarily
of field values). This can be in form of a question string ('Are you sure [y/N]?'),
in form of HTML ('<input type="checkbox" name="sure" value="y">'), a 
GtkEntry-widget or any other form you can think of.

Note, that one Display instance only displays one Field, so for a form with two
Fields you'll also need two Displays.

You can stringify Displays (**__toString()**, e. g. echo (string)$myDisplay;).
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
**informAboutStructure()**-method:

```PHP
<?php
   $myDisplay = new Feeld\Display\HTML\Input('email');
   print($myDisplay); // will print <input type="email">

   $myField = new Feeld\Field\Entry(new Feeld\DataType\Email());
   $myField->setRequired();

   $myDisplay->informAboutStructure($myField);
   print($myDisplay); // will – hopefully – print something like 
                      // <input type="email" required>
```

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
this selection, <input type="radio">-HTML-tags are used.

Notice, that in the last example, there is no call to **informAboutStructure()**.
You can either manually trigger that call by calling **refreshDisplay()** on the 
Field. The call to informAboutStructure() is also made _the first time_ the 
**\__toString()**-method of a Field is called – but only in case you did never
manually call refreshDisplay(). This saves you one line of code in case you
are using Displays that have a string representation.



The whole Display-part of Feeld is still very much work in progress.

### FieldCollections

Fields can be grouped in a **FieldCollection**. If you want to write your own
collection of Fields, make sure to implement **FieldCollectionInterface**. You
can use the **FieldCollectionTrait** to have some basic code.

## Feeld?

It's a pun on „That feels right“ and „Field“.