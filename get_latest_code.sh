#!/bin/sh

mv system/config.php ../config-temp.php

git fetch --all
git reset --hard origin/master

mv ../config-temp.php system/config.php