#!/bin/bash
set -e

tail -fn 0 /var/log/apache2/*.log &

/usr/sbin/apache2ctl -D FOREGROUND
