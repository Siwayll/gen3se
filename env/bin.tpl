#!/bin/bash

set -e

exec docker-compose run --rm ${DOCKER_SERVICE} ${BINARY_OPTIONS} \$@
