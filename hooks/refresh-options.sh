#!/bin/sh

# This script refreshes the database options to add new options and remove old ones.
# install in $GITDIR/hooks:
# ln -s hooks/refresh-options.sh $GITDIR/hooks/post-merge

WP=$(which wp)

$WP bdsl refresh
