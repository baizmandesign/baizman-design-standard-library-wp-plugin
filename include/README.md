# include folder

Drop in any PHP file into this folder, and it will be automagically loaded by standard-library.php. The reasoning behind this is to be able to segment the functionality of the code into discreet modules, and drop in or remove modules on demand.

## local customizations

Create a file named `local-config.php` and add constants found in `_constants.php` to over-ride the default settings.