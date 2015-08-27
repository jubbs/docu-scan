#!/bin/sh

mv system/config.php ../config-temp.php

git fetch --all
git reset --hard origin/master

mv ../config-temp.php system/config.php

chmod -R a+x ./*
chown -R www-data:www-data ./*