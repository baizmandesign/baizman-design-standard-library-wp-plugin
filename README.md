# baizman design - wp plugin standard library

This is a standard library for my clients' websites. It should be included as a submodule via git.

## installation instructions

1. Add the submodule to the root of the plugin folder.
   ```
   $ cd [plugin folder]
   $ git submodule add baizmandesign.bitbucket.org:baizmandesign/baizman-design-wp-plugin-standard-library.git baizman-design-lib
   ```
2. Load the library via your main plugin file.

   Add the following line near the top of your plugin file after the comment header.

   ```
   require_once ('baizman-design-lib/standard-library.php') ;
   ```
