#!/bin/bash

set -e

exec docker ${DOCKER_COMMAND} ${BINARY_OPTIONS} "${ARGUMENTS}"
