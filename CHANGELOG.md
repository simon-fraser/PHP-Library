Sym PHP Library Changes
==================

##Release 1.0 July 2012

Inital release after converting several versions of functions files into class method calls.


##Release 1.1 November 2012

A long awaited update to the library system, updates as follow:

- Added FTP support (alpha tested).
- Updated string encode functions, baseencode -> encode, basedecode -> decode.
- Updated sanetize function to move acceptable HTML strings into global config value.
- Add timedate class, with format and validate method.
- Add password class with encrypt and memorable methods.
- Update titlecase in str class, was running duplicate against camelcase