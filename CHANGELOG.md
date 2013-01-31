PHP Library Changes
==================

##Release 1.7 Januaray 2013
- Updated instances of `str_replace()` with `str::replace()` for consistancy
- Updated instances of `preg_match` & `preg_replace` with appropriate `ex` class methods for consistancy
- Added `ex::matchsingle()` after evaluating the results of `ex::match()` under a single search environment.
- Switched `$format` & `$datetime` variables in `timedate::format()`

##Release 1.6 January 2013
- Added `db::procedure()` To help list any saved MySQL Procedures.
- Added `req::ajax()` As provided by [David Turner](http://www.davidturner.name/) to detect when a request is being made by asynchronous JavaScript.
- Alteration to `str::striphtml()` To use a local declaration of allowed tags instead of a global tag declaration.

##Release 1.5 January 2013
- `content::get()`- Removed `str::sanetize()`, Can be called separately.
- Removed strip_tags from `str::sanetize()`.
- Added `str::striphtml()` To remove HTML tags from a string.
- Added `str::replace()` To normalize str_replace in line with toolbox

##Release 1.4 December 2012
- Additional Functionality Added by [David Turner](http://www.davidturner.name/)
	- `str::hexcolor()` Which will generate a hex colour code from a given string
	- `str::curly()` Which will help they styling of quotations in a string, by switching them for their curly counterparts
	- Improved stability in `db::insert()`

##Minor Release 1.3.1 December 2012
- Error with `db::join()` varable `$table_a` error.

##Release 1.3 December 2012
- Added new `visitor` class.
   - `visitor::browser()` Will return the visitors browser information
   - `visitor::ip()` Will return visitors IP address
   - `visitor::os()` Will return visitors Operating System title

##Release 1.2 November 2012

- Added table method into html class `html::table()`.
- Updated `db::insert()` to accept multidimension array only values & array for labels.
- Added `video::` class.
   - `video::youtube()` Will parse and return an embed URL for YouTube links
   - `video::vimeo()` Will parse and return an embed URL for Vimeo links
   - `video::embed()` Will embed an iframe onto page for a given embed URL


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


##Release 1.0 July 2012

Initial release after converting several versions of functions files into class method calls.
