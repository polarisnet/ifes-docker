#!/bin/bash

# Start the first process
/etc/startphpfpm -D
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start startphpfpm: $status"
  exit $status
fi

# Start the second process
/etc/startnginx -D
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start startnginx: $status"
  exit $status
fi