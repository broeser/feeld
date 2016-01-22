# feeld
Feeld provides typed field objects that can be used as building blocks to create CLI questionnaires, HTML forms, and much more. Includes sanitization and validation.

All fields implement **FieldInterface**. The different field types (e. g. text entry, selection) can be found in src/Field/. Each field is assigned a DataType (src/Field/DataType).

Fields can be grouped in a **FieldCollection**.

Please ignore the Display/…-namespace for now, it is an experiment that has not gone very far yet.

