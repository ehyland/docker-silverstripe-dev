#!/bin/bash
set -e

# service hhvm start
/etc/init.d/hhvm start

nginx -g "daemon off;"
