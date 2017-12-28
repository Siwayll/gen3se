define executable
chmod u+x $1
endef

define export-file
FILE=`mktemp` && trap 'rm -f $$FILE' 0 1 2 3 15 && ( echo 'cat <<EOF'; cat "$1"; echo 'EOF') > $$FILE && export ARGUMENTS='$$@' && $(RM) $2 && . $$FILE > $2
endef

bin:
	mkdir bin

bin/phpmd: bin
	export DOCKER_COMMAND="run --rm -w /src --volume ${PWD}:/src php:7.2-cli-alpine3.7" \
	&& export BINARY_OPTIONS="php -f vendor/bin/phpmd ./ text ./build/config/phpmd.xml --exclude vendor/,Tests/tmp/,build/ " \
	&& $(call export-file,env/bin.tpl,bin/phpmd) \
	&& $(call executable,bin/phpmd)

bin/phpcs: bin
	export DOCKER_COMMAND="run --rm -w /src --volume ${PWD}:/src php:7.2-cli-alpine3.7" \
	&& export BINARY_OPTIONS="php -f vendor/bin/phpcs -- --encoding=UTF-8 --standard=check-style.xml " \
	&& $(call export-file,env/bin.tpl,bin/phpcs) \
	&& $(call executable,bin/phpcs)

bin/composer: bin
	export DOCKER_COMMAND="run --rm --interactive --tty --volume ${PWD}:/app composer:1.5" && $(call export-file,env/bin.tpl,bin/composer) && $(call executable,bin/composer)

bin/atoum: bin
	export DOCKER_COMMAND="run --rm -w /src --volume ${PWD}:/src php:7.2-cli-alpine3.7" \
	&& export BINARY_OPTIONS="php -f vendor/bin/atoum --" \
	&& $(call export-file,env/bin.tpl,bin/atoum) \
	&& $(call executable,bin/atoum)

.PHONY: install
install: bin/composer
	./bin/composer install
