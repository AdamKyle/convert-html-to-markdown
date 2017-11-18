# Convert HTMl File To Markdown

This is a small PHP script that can be run from terminal to collect and parse all `.html` files and convert them to `.md` files while retaining the order of the
file structure intact.

- We require absolute path's to both inout and output.
- PHP 7 is required.

## To use:

clone, `composer install`, `php ConvertHTML /absolute/path/to/.html_files /absolute/path/to/desired/output`

Thats it. This will recursively look at all files in a directory, create the appropriate data structures, use that to then create and store the files.

 **DO NOT USE THIS ON A SERVER!** Meant for personal use only. Was created for me to quickly convert hundreds of HTML files to makrdown.

 Some massaging of said markdown may be required.
