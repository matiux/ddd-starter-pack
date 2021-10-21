SHELL := /bin/bash

%:
    @:

ARGS = `arg="$(filter-out $@,$(MAKECMDGOALS))" && echo $${arg:-${1}}`

staged-files:
	$(eval STAGED_FILES := $(shell git diff --name-only --cached --diff-filter=ACMR -- '*.php' | sed 's| |\\ |g'))
	@echo $(STAGED_FILES)

install-dependencies:
	composer install --prefer-dist --no-progress

## Phpunit
coverage:
	@export XDEBUG_MODE=coverage; \
	./vendor/bin/phpunit \
		--verbose \
		--testdox \
		--colors=always \
		--coverage-text \
		--coverage-clover=coverage.xml \
		--coverage-html .coverage \
		--exclude-group learning; \
	export XDEBUG_MODE=off

phpunit:
	@./vendor/bin/phpunit \
		--exclude-group learning \
		--colors=always \
		--testdox \
		--verbose \
		$(ARGS)

## Psalm
psalm:
	@./vendor/bin/psalm -c ./psalm.xml --no-cache $(ARGS)

security-analysis: ## run static analysis security checks
	@./vendor/bin/psalm -c psalm.xml --no-cache --taint-analysis

static-analysis:  ## run static analysis checks
	@./vendor/bin/psalm -c psalm.xml --no-cache --show-info=true

type-coverage:
	@./vendor/bin/psalm -c psalm.xml --no-cache --shepherd --stats

## Database
schema-create:
	@vendor/bin/doctrine orm:schema-tool:create --no-interaction

## Coding Standard
coding-standard:
	./vendor/bin/php-cs-fixer $(ARGS)

coding-standard-fix:
	./vendor/bin/php-cs-fixer fix --verbose --show-progress=dots --config=.php-cs-fixer.dist.php -- $(ARGS)

coding-standard-fix-staged:
	$(eval STAGED_FILES := $(shell git diff --name-only --cached --diff-filter=ACMR -- '*.php' | sed 's| |\\ |g'))
	@./vendor/bin/php-cs-fixer fix --verbose --show-progress=dots --config=.php-cs-fixer.dist.php -- $(STAGED_FILES)

coding-standard-check:
	@./vendor/bin/php-cs-fixer fix --verbose --show-progress=dots --config=.php-cs-fixer.dist.php --dry-run -- $(ARGS)

coding-standard-check-staged:
	$(eval STAGED_FILES := $(shell git diff --name-only --cached --diff-filter=ACMR -- '*.php' | sed 's| |\\ |g'))
	@./vendor/bin/php-cs-fixer fix --verbose --show-progress=dots --config=.php-cs-fixer.dist.php --dry-run -- $(STAGED_FILES)