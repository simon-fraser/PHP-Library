PHP Library
==================

A collection of function code over the years turned into class and method based calls.

Some user documentation to follow, but for the meantime, the PHP Library is based on and uses simple PHP5 Object Oriented syntax, [http://php.net/manual/en/language.oop5.php].

Report any issues encountered or any possible features that could be helpful.


Latest Update
==================

##Release 1.7 Januaray 2013
- Updated instances of `str_replace()` with `str::replace()` for consistancy
- Updated instances of `preg_match` & `preg_replace` with appropriate `ex` class methods for consistancy
- Added `ex::matchsingle()` after evaluating the results of `ex::match()` under a single search environment.
- Switched `$format` & `$datetime` variables in `timedate::format()`