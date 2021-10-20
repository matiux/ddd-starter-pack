SHELL=/bin/bash

%:
    @:

ARGS = `arg="$(filter-out $@,$(MAKECMDGOALS))" && echo $${arg:-${1}}`

staged-files:
	$(eval STAGED_FILES := $(shell git diff --name-only --cached --diff-filter=ACMR -- '*.php' | sed 's| |\\ |g'))
	@echo $(STAGED_FILES)

install-dependencies:
	composer install --prefer-dist --no-progress

## Phpunit
phpunit-with-coverage:
	./vendor/bin/phpunit --verbose --testdox --colors=always --coverage-text --coverage-clover ./coverage.xml

phpunit-no-coverage:
	./vendor/bin/phpunit --exclude-group learning --colors=always --testdox -vvv

## Psalm
psalm:
	./vendor/bin/psalm -c ./psalm.xml --no-cache $(ARGS)

security-analysis: ## run static analysis security checks
	./vendor/bin/psalm -c psalm.xml --no-cache --taint-analysis

static-analysis:  ## run static analysis checks
	./vendor/bin/psalm -c psalm.xml --no-cache --show-info=true --no-cache

type-coverage:
	./vendor/bin/psalm -c psalm.xml --no-cache --shepherd --stats

## Coding Standard
coding-standard:
	./vendor/bin/php-cs-fixer $(ARGS)

coding-standard-single:
	./vendor/bin/php-cs-fixer fix --show-progress=dots --config=.php-cs-fixer.dist.php $(ARGS)

coding-standard-fix:
	$(eval STAGED_FILES := $(shell git diff --name-only --cached --diff-filter=ACMR -- '*.php' | sed 's| |\\ |g'))
	./vendor/bin/php-cs-fixer fix --show-progress=dots --config=.php-cs-fixer.dist.php $(STAGED_FILES)

coding-standard-check:
	./vendor/bin/php-cs-fixer fix --show-progress=dots --config=.php-cs-fixer.dist.php --dry-run $(ARGS)