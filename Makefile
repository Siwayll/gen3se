
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
	@export DOCKER_SERVICE="php-cli" \
	&& export BINARY_OPTIONS="php -f vendor/bin/phpmd ./src text ./phpmd.xml" \
	&& $(call export-file,env/bin.tpl,bin/phpmd)
	@$(call executable,bin/phpmd)

bin/phpcs: | bin
	@export DOCKER_SERVICE="php-cli" \
	&& export BINARY_OPTIONS="php -f vendor/bin/phpcs -- --encoding=UTF-8 --standard=check-style.xml " \
	&& $(call export-file,env/bin.tpl,bin/phpcs)
	@$(call executable,bin/phpcs)

bin/phpcbf: | bin
	@export DOCKER_SERVICE="php-cli" \
	&& export BINARY_OPTIONS="php -f vendor/bin/phpcbf -- --encoding=UTF-8 --standard=check-style.xml " \
	&& $(call export-file,env/bin.tpl,bin/phpcbf)
	@$(call executable,bin/phpcbf)

bin/doc: | bin var/doc
	@export DOCKER_SERVICE="php-cli" \
	&& export BINARY_OPTIONS="php -f vendor/bin/kitab -- compile --configuration-file=.kitab.target.html.php --output-directory var/doc src " \
	&& $(call export-file,env/bin.tpl,bin/doc)
	@$(call executable,bin/doc)

bin/composer: | bin
	@export DOCKER_SERVICE="composer" \
	&& $(call export-file,env/bin.tpl,bin/composer)
	@$(call executable,bin/composer)

bin/atoum: | bin
	@export DOCKER_SERVICE="php-cli" \
	&& export BINARY_OPTIONS="php -f specs/units/runner.php --" \
	&& $(call export-file,env/bin.tpl,bin/atoum)
	@$(call executable,bin/atoum)

bin/behat: | bin
	@export DOCKER_SERVICE="php-cli" \
	&& export BINARY_OPTIONS="php -f vendor/bin/behat --" \
	&& $(call export-file,env/bin.tpl,bin/behat)
	@$(call executable,bin/behat)

vendor: | bin/composer
	./bin/composer install
	$(call ownerCorrection, vendor)

.PHONY: install
install: vendor bin/atoum bin/behat bin/phpcs bin/phpmd bin/doc ## install dependencies and create binaries
	@echo 'all good'

.PHONY: doc
doc: vendor bin/doc ## Render the doc
	./bin/doc
	$(call ownerCorrection, var/doc)

.PHONY: vendorCorrectOwner
vendorCorrectOwner:
	$(call ownerCorrection, vendor)

.PHONY: qualityCheck
qualityCheck: bin/phpcs bin/phpmd ## Launch quality controls
	./bin/phpcs -s src
	./bin/phpcs specs
	./bin/phpmd

.PHONY: test
test: bin/atoum bin/behat ## Launch tests
	./bin/atoum
	.bin/behat

.PHONY: help
help: ## Display this help.
	@printf "$$(cat $(MAKEFILE_LIST) | egrep -h '^[^:]+:[^#]+## .+$$' | sed -e 's/:[^#]*##/:/' -e 's/\(.*\):/\\033[92m\1\\033[0m:/' | sort -d | column -c2 -t -s :)\n"

