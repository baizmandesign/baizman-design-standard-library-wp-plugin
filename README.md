# baizman design standard library - wp plugin

This is a standard library for my clients' websites. It is a common set of frequently requested or useful WordPress features in a customizable interface.

## features

+ log and view 404 errors.
+ incorporate google analytics.
+ sanitize post content (e.g., strip out illegal html tags, blank lines, double-spaces, etc.).
+ add many enhancements to the WordPress dashboard.
  + hide unused dashboard links.
  + set dashboard background colors on development and staging sites.
  + display custom branding.
  + display global notices.
  + add fixed table headers on dashboard indexical pages.
+ much more.

## installation instructions

Add the plugin via the typical fashion and activate it.

## hook installation

The file `hooks/refresh-options.sh` should be executed after `git pull` to sync the configuration array keys with the database in case they have been modified. To install the hook, create a symbolic link:

```shell
$ ln -s hooks/refresh-options.sh .git/hooks/post-merge
```
