
PHP_DOCKER_TAG ?= 7.2-cli-alpine
COMPOSER_DOCKER_TAG ?= 1.6.2

USER_ID = $(shell id -u)
GROUP_ID = $(shell id -g)

define executable
chmod u+x $1
endef

define export-file
FILE=`mktemp` && trap 'rm -f $$FILE' 0 1 2 3 15 && ( echo 'cat <<EOF'; cat "$1"; echo 'EOF') > $$FILE && export ARGUMENTS='$$@' && $(RM) $2 && . $$FILE > $2
endef

define ownerCorrection
sudo chown -R ${USER_ID}:${GROUP_ID} $1
endef

var/%:
	@mkdir -p $@

bin:
	@mkdir -p bin

bin/phpmd: | bin
	@export DOCKER_COMMAND="run --rm -w /src --volume ${PWD}:/src php:${PHP_DOCKER_TAG}" \
	&& export BINARY_OPTIONS="php -f vendor/bin/phpmd ./ text ./build/config/phpmd.xml --exclude vendor/,Tests/tmp/,build/ " \
	&& $(call export-file,env/bin.tpl,bin/phpmd)
	@$(call executable,bin/phpmd)

bin/phpcs: | bin
	@export DOCKER_COMMAND="run --rm -w /src --volume ${PWD}:/src php:${PHP_DOCKER_TAG}" \
	&& export BINARY_OPTIONS="php -f vendor/bin/phpcs -- --encoding=UTF-8 --standard=check-style.xml " \
	&& $(call export-file,env/bin.tpl,bin/phpcs)
	@$(call executable,bin/phpcs)

bin/doc: | bin var/doc
	@export DOCKER_COMMAND="run --rm -w /src --volume ${PWD}:/src php:${PHP_DOCKER_TAG}" \
	&& export BINARY_OPTIONS="php -f vendor/bin/kitab -- compile --configuration-file=.kitab.target.html.php --output-directory var/doc lib " \
	&& $(call export-file,env/bin.tpl,bin/doc)
	@$(call executable,bin/doc)

bin/composer: | bin
	@export DOCKER_COMMAND="run --rm --interactive --tty --volume ${PWD}:/app composer:${COMPOSER_DOCKER_TAG}" \
	&& $(call export-file,env/bin.tpl,bin/composer)
	@$(call executable,bin/composer)

bin/atoum: | bin
	@export DOCKER_COMMAND="run --rm -w /src --volume ${PWD}:/src php:${PHP_DOCKER_TAG}" \
	&& export BINARY_OPTIONS="php -f vendor/bin/atoum --" \
	&& $(call export-file,env/bin.tpl,bin/atoum)
	@$(call executable,bin/atoum)

vendor: | bin/composer
	./bin/composer install
	$(call ownerCorrection, vendor)

.PHONY: install
install: vendor bin/atoum bin/phpcs bin/phpmd bin/doc ## install dependencies and create binaries
	@echo 'all good'

.PHONY: doc
doc: vendor bin/doc ## Render the doc
	./bin/doc

.PHONY: vendorCorrectOwner
vendorCorrectOwner:
	$(call ownerCorrection, vendor)

.PHONY: qualityCheck
qualityCheck: bin/phpcs bin/phpmd ## Launch quality controls
	./bin/phpcs --encoding=UTF-8 --standard=check-style.xml lib
	./bin/phpcs --encoding=UTF-8 --standard=check-style.xml specs
	./bin/phpmd ./ text ./build/config/phpmd.xml --exclude vendor/,bin/,build/

.PHONY: test
test: bin/atoum ## Launch tests
	./bin/atoum

.PHONY: help
help: ## Display this help.
	@printf "$$(cat $(MAKEFILE_LIST) | egrep -h '^[^:]+:[^#]+## .+$$' | sed -e 's/:[^#]*##/:/' -e 's/\(.*\):/\\033[92m\1\\033[0m:/' | sort -d | column -c2 -t -s :)\n"

