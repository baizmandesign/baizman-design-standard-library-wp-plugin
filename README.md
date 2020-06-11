# baizman design standard library - wp plugin

This is a standard library for my clients' websites. It is a set of frequently used WordPress features in a customizable interface.

## installation instructions

Add the plugin via the typical fashion and activate it.

## hook installation

The file `hooks/refresh-options.sh` should be executed after `git pull` to sync the configuration array keys with the database in case they have been modified. To install the hook, create a symbolic link:

```shell
$ ln -s hooks/refresh-options.sh .git/hooks/post-merge
```
