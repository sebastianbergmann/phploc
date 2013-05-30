phploc
======

This is the list of changes for the phploc 2.0 release series.

phpcpd 2.0.0
------------

* Implemented #4: Count code outside of classes or functions.
* Implemented #19: Count number of static/non-static method calls (as well as accesses to static/non-static attributes).
* Added the `--git-repository` switch to calculate the software metrics for each revision of a Git repository.
* Added `--names-exclude` switch to exclude filenames using glob patterns.
* Replaced the Non-Comment Lines of Code (NCLOC) metric with the Logical Lines of Code (LLOC) metric.
* Removed the ability to count the Executable Lines of Code (ELOC) and the optional dependency on Bytekit.
* The [Version](http://github.com/sebastianbergmann/version) component is now used to manage the version number.
