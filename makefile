# Setup ————————————————————————————————————————————————————————————————————————————————————————————————————————————————
PROJECT_PREFIX=ddd-starter-pack

# Static ———————————————————————————————————————————————————————————————————————————————————————————————————————————————
.DEFAULT_GOAL := help
PHP_IMAGE=$(PROJECT_PREFIX)-php
NODEJS_IMAGE=conventional-commit-nodejs
PROJECT_NAME=$(shell basename $$(pwd) | tr '[:upper:]' '[:lower:]')
PHP_USER=utente
WORKDIR=/var/www/app
PROJECT_TOOL=$(WORKDIR)/tools/bin/project/project
PROJECT_TOOL_RELATIVE=./tools/bin/project/project

# Docker conf ——————————————————————————————————————————————————————————————————————————————————————————————————————————
PHP_DOCKERFILE=./docker/php/Dockerfile

ifeq ($(wildcard ./docker/docker-compose.override.yml),)
	COMPOSE_OVERRIDE=
else
	COMPOSE_OVERRIDE=-f ./docker/docker-compose.override.yml
endif

COMPOSE=docker compose --file ./docker/docker-compose.yml $(COMPOSE_OVERRIDE) -p $(PROJECT_NAME)

COMPOSE_EXEC=$(COMPOSE) exec -u $(PHP_USER) -w $(WORKDIR)
COMPOSE_EXEC_PHP=$(COMPOSE_EXEC) $(PHP_IMAGE)
COMPOSE_EXEC_PHP_NO_PSEUSO_TTY=$(COMPOSE_EXEC) -T $(PHP_IMAGE)

COMPOSE_RUN=$(COMPOSE) run --rm

# Docker commands ——————————————————————————————————————————————————————————————————————————————————————————————————————
.PHONY: up
up: ## Up dei container
	$(COMPOSE) up $$ARG

.PHONY: upd
upd: ## Up dei container in modalità demone
	$(COMPOSE) up -d $$ARG

.PHONY: build-php
build-php: ## Build del container PHP
	docker build -f $(PHP_DOCKERFILE) --tag $(PHP_IMAGE) $$ARG .

.PHONY: enter
enter: ## Entra nel container php come utente
	$(COMPOSE_EXEC_PHP) /bin/zsh

.PHONY: enter-root
enter-root: ## Entra nel container php come root
	$(COMPOSE) exec -u root $(PHP_IMAGE) /bin/zsh

.PHONY: down
down: ## Down dei container
	$(COMPOSE) down $$ARG

.PHONY: purge
purge: ## Down dei container e pulizia di immagini e volumi
	$(COMPOSE) down --rmi=all --volumes --remove-orphans

.PHONY: log
log: ## Log dei container docker
	$(COMPOSE) logs -f

.PHONY: ps
ps: ## Lista dei container
	@$(COMPOSE) ps

.PHONY: compose
compose: ## Wrapper a docker compose
	@$(COMPOSE) $$ARG

# Commitlint commands ——————————————————————————————————————————————————————————————————————————————————————————————————

.PHONY: conventional
conventional: ## chiama conventional commit per validare l'ultimo commit message
	$(COMPOSE_RUN) -T $(NODEJS_IMAGE) commitlint -e --from=HEAD -V

# PHP commands —————————————————————————————————————————————————————————————————————————————————————————————————————————

.PHONY: composer
composer: ## Wrapper a composer. Es: make composer ARG=update
	$(COMPOSE_EXEC_PHP) composer $$ARG

.PHONY: php-run
php-run: ## Esegue comandi all'interno del container PHP
	$(COMPOSE_EXEC_PHP) $$ARG

# CS Fixer commands ————————————————————————————————————————————————————————————————————————————————————————————————————

.PHONY: coding-standard-fix
coding-standard-fix: ## Fix della formattazione. Senza parametri formatta tutto. Es: make coding-standard-fix ARG="./file.php"
	$(COMPOSE_EXEC_PHP) $(PROJECT_TOOL) coding-standard-fix $$ARG

.PHONY: coding-standard-check-staged
coding-standard-check-staged: ## Verifica formattazione solo dei file in git stage
	$(COMPOSE_EXEC_PHP_NO_PSEUSO_TTY) $(PROJECT_TOOL) coding-standard-check-staged

.PHONY: coding-standard-fix-staged
coding-standard-fix-staged: ## Fix della formattazione solo dei file in git stage
	$(COMPOSE_EXEC_PHP_NO_PSEUSO_TTY) $(PROJECT_TOOL) coding-standard-fix-staged

# Test commands ————————————————————————————————————————————————————————————————————————————————————————————————————————

.PHONY: phpunit
phpunit: ## Esegue tutta la suite di test generando anche il badge. Oppure uno specifico: make phpunit ARG=./test.php
	$(COMPOSE_EXEC_PHP_NO_PSEUSO_TTY) $(PROJECT_TOOL) phpunit $$ARG

.PHONY: coverage
coverage: ## Esegue tutta la suite di test e verifica la coverage generando anche il badge
	$(COMPOSE_EXEC_PHP) $(PROJECT_TOOL) coverage

.PHONY: create-schema
create-schema: ## Create database schema for test execution
	$(COMPOSE_EXEC_PHP) $(PROJECT_TOOL) doctrine-schema-create

# Static analysis ——————————————————————————————————————————————————————————————————————————————————————————————————————

.PHONY: psalm
psalm: ## Esegue l'analisi statica su tutto il progetto. Oppure uno specifico file: make psalm ARG=./file.php
	$(COMPOSE_EXEC_PHP_NO_PSEUSO_TTY) $(PROJECT_TOOL) psalm $$ARG

.PHONY: psalm-taint
psalm-taint: ## Esegue i controlli di sicurezza basati su psalm
	$(COMPOSE_EXEC_PHP_NO_PSEUSO_TTY) $(PROJECT_TOOL) psalm-taint $$ARG

# Dependencies vulnerabilities —————————————————————————————————————————————————————————————————————————————————————————

.PHONY: check-deps-vulnerabilities
check-deps-vulnerabilities: ## Check per le vulnerabilità sulle dipendenze
	$(COMPOSE_EXEC_PHP_NO_PSEUSO_TTY) $(PROJECT_TOOL) check-deps-vulnerabilities

# Deptrac ——————————————————————————————————————————————————————————————————————————————————————————————————————————————

.PHONY: deptrac-table-all
deptrac-table-all: ## Esegue deptrac per la verifica dei vincoli architetturali
	$(COMPOSE_EXEC_PHP_NO_PSEUSO_TTY) $(PROJECT_TOOL) deptrac-table-all $$ARG

# Infection ————————————————————————————————————————————————————————————————————————————————————————————————————————————

.PHONY: infection
infection: ## Esegue infection per test di mutazione
	$(COMPOSE_EXEC_PHP_NO_PSEUSO_TTY) $(PROJECT_TOOL) infection


# Project autopilot command ————————————————————————————————————————————————————————————————————————————————————————————

.PHONY: project
project: ## Wrapper per invocare il tool project all'interno del container php. Usare make project ARG=shortlist per la lista delle operazioni
	if [ ! -f ${PROJECT_TOOL_RELATIVE} ]; then \
		$(COMPOSE_EXEC_PHP) composer install; \
    fi; \
	$(COMPOSE_EXEC_PHP_NO_PSEUSO_TTY) $(PROJECT_TOOL) $$ARG

.PHONY: help
help:	## Show this help
	@grep -hE '^[A-Za-z0-9_ \-]*?:.*##.*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'