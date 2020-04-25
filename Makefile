# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
#                                                                                 #
# Pando 2020 — NOTICE OF MIT LICENSE                                              #
# @copyright 2019-2020 (c) Paolo Combi (https://combi.li)                         #
# @link    https://github.com/colapiombo/pando                                    #
# @author  Paolo Combi <paolo@combi.li>                                           #
# @license https://github.com/colapiombo/pando/blob/master/LICENSE (MIT License)  #
#                                                                                 #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


# PROJECT_NAME defaults to name of the current directory.
# in the project name dont set '-'
# https://github.com/docker/libnetwork/issues/929#issuecomment-184129857
# should not to be changed if you follow GitOps operating procedures.
PROJECT_NAME = $(notdir $(PWD))

THIS_FILE := $(lastword $(MAKEFILE_LIST))

# Retrieve the command used to manage the Docker environment
COMPOSER = docker-compose run --rm -u $$(id -u) composer
TEST = docker-compose run --rm -u $$(id -u) php

# Retrieve the Makefile used to manage the Docker environment
COMPOSE_FILE	:= docker-compose.yml
# export such that its passed to shell functions for Docker to pick up.
export PROJECT_NAME

start: ## Start the environment
	docker-compose stop
	docker-compose up -d --remove-orphans

stop: ## Stop the environment
	docker-compose stop

rebuild:
	# force a rebuild by passing --no-cache
	docker-compose build --no-cache

build:
	# only build the container. Note, docker does this also if you apply other targets.
	docker-compose stop
	docker-compose build

prune:
	# clean all that is not actively used
	docker-compose stop
	docker system prune -af

githooks: ## set the environment for check with lint
	echo "make lint" > .git/hooks/pre-push
	chmod +x .git/hooks/pre-push

vendor: ## run composer install
	$(COMPOSER) install

lint: ## check the code with lint
	$(COMPOSER) install
	$(COMPOSER) composer lint

lintfix: ## fix the code with lint
	$(COMPOSER) install
	$(COMPOSER) composer lint:fix

phpcs: ## check the code with php-cs-fixer & lint
	$(COMPOSER) install
	$(COMPOSER) composer lint:php-cs-fixer

phpcsfix: ## fix the code with php-cs-fixer & lint
	$(COMPOSER) install
	$(COMPOSER) composer lint:php-cs-fixer:fix

test: ## test with phpunit
	$(COMPOSER) install
	$(TEST) php vendor/bin/phpunit


.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) \
		| sed -e 's/^.*Makefile://g' \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' \
		| sed -e 's/\[32m##/[33m/'