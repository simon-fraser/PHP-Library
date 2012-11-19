PHP Library Changes
==================

##Release 1.0 July 2012

Initial release after converting several versions of functions files into class method calls.


##Release 1.1 November 2012

A long awaited update to the library system, updates as follow:

- Added FTP support, `ftp::` (alpha tested).
- Updated string encode functions, baseencode -> encode, basedecode -> decode.
   - `str::encode();`
   - `str::decode();`
- Updated sanitize function to move acceptable HTML strings into global config value.
- Add `timedate::` class, with `format()` and `valid()` method.
- Add `password::` class with `encrypt()` and `memorable()` methods.
- Update `str::titlecase()`, was running duplicate against camelcase.
- Added `str::numbers($str);` to convert numbers to number words.