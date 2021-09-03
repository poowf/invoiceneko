#!/bin/sh
set -e

# Check for ENV_DATA environment variable
if [ -n "$ENV_DATA" ]
then
  php buildEnvData.php
  unset ENV_DATA
  exec "$@"
else
  exec "$@"
fi
