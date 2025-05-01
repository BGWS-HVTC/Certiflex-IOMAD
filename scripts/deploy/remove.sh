#!/usr/bin/env bash

set -e

rm -rf /var/www/html


if [[ -f "/deploycrons" ]]; then
  rm -rf /etc/cron.d/job-* || true
fi