$Id: README.txt,v 1.1 2010/07/01 19:19:12 andrewlevine Exp $

Last Changed is a module which simply keeps track of the last time any record
of a certain type changes. By default the module supports nodes, comments, and
users, but other types can be added as well by implementing hook_last_changed().

Last changed is useful in cases where you simply need to know the last time a
record was edited, for example in order to generate incremental updates or to
query the last records of multiple types that have changed.

This module includes Views support.

If you are looking to record actions that have been taken on your website to
display to users, you might want to use the Activity module:
http://drupal.org/project/activity
If you are looking to save revisions you might want to use Drupal's built in
node revisions support.