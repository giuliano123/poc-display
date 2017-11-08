ENV=dev
FIG=docker-compose
PARAMETERS=$(call create-parameters,$(ENV))
CONSOLE=$(PARAMETERS) php bin/console
COMPOSER=$(PARAMETERS) composer

.DEFAULT_GOAL := help
.PHONY: help init start stop restart cc clear clean db-model db-migration assets init-test test deps deps-update build perm check-tag check-int docker-tag docker-deploy

help:
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

##
## Project setup
##---------------------------------------------------------------------------

init:		    ## Install the project
init: perm app/config/parameters.yml deps build

start:		    ## Start the project with docler
start: cc
	$(FIG) up -d

stop:		    ## Remove docker containers
	$(FIG) kill
	$(FIG) rm -v --force

##
## Environnement
##---------------------------------------------------------------------------

cc:		    ## Remove the cache
	rm -rf app/cache/*

clear:		    ## Remove all the cache, the logs, the sessions and the built assets
clear: perm cc
	rm -rf var/logs/*
	rm -rf web/css/*
	rm -rf web/js/*

clean:		    ## Clear and remove dependencies
clean: clear
	rm -rf vendor

##
## Database
##---------------------------------------------------------------------------

db-model:	    ## Build propel model
db-model:
	$(CONSOLE) propel:build --verbose --env=$(ENV)

db-migration:	    ## Build propel migration file
db-migration:
	$(CONSOLE) propel:migration:generate-diff --verbose --env=$(ENV)

##
## Assets
##---------------------------------------------------------------------------

assets:		    ## Generate assets
assets:
	$(CONSOLE) assets:install --env=$(ENV)
	$(CONSOLE) assetic:dump --env=$(ENV)

##
## Déploiement
##---------------------------------------------------------------------------

deploy-int: 	    ## Build and deploy a clean image for integration
deploy-int: check-tag check-int clean git-reset git-tag deps db-model build docker-tag docker-deploy

deploy-prod: 	    ## Build and deploy a clean image for production
deploy-prod: check-tag check-prod check-master clean git-reset git-tag deps db-model build docker-tag docker-deploy

##
## Tests
##---------------------------------------------------------------------------

init-test:	    ## Setup project tests
init-test: vendor db-model phpunit.xml
	$(eval USERNAME := $(shell whoami | tr a-z A-Z))
	sed -i "s/<LOGIN>/$(USERNAME)/g" phpunit.xml
	SYMFONY__LOGIN_UP=$(USERNAME) $(CONSOLE) propel:database:drop --force --env=test --connection=default
	SYMFONY__LOGIN_UP=$(USERNAME) $(CONSOLE) propel:database:create --env=test --connection=default
	SYMFONY__LOGIN_UP=$(USERNAME) $(CONSOLE) propel:sql:build --env=test --connection=default
	SYMFONY__LOGIN_UP=$(USERNAME) $(CONSOLE) propel:sql:insert --env=test --connection=default --force


test:		    ## Run the PHP tests
test:
	bin/phpunit

##
## Dependencies
##---------------------------------------------------------------------------

deps:		    ## Install the project PHP dependencies
deps: vendor

deps-update:	    ## Update the project PHP dependencies
deps-update:
	$(COMPOSER) update --prefer-source --profile

##


# Internal rules

perm:
	-chmod 777 -R var/logs/
	-chmod 777 -R var/cache/
	-chmod 777 -R var/sessions/

git-reset:
	git fetch --prune --all
	git reset HEAD --hard
	git clean -fdx

git-tag:
	$(MAKE) BRANCH=$(call get-branch) git-tag-master

git-tag-master: check-tag
ifeq ($(BRANCH),master)
	git tag -a v$(TAG) -m "$(TAG)" ||:
	git push origin --tags
endif

check-master:
	$(MAKE) BRANCH=$(call get-branch) git-check-master

git-check-master:
ifneq ($(BRANCH), master)
	$(error you must be on master branch)
endif

build:
ifeq ($(ENV), dev)
	$(FIG) build
else
	$(FIG) -f docker-compose-$(ENV).yml build
endif

docker-tag:
	docker tag poc/application:latest localhost:5000/poc/application:$(TAG)

docker-deploy:
	docker push localhost:5000/poc/application:$(TAG)

check-tag:
ifndef TAG
	$(error TAG is undefined. ex: make **** -e TAG=1.0.0 )
endif

check-int:
ifneq ($(ENV), int)
	$(error ENV must be set to int. ex: make **** -e ENV=int)
endif

check-prod:
ifneq ($(ENV), prod)
	$(error ENV must be set to prod. ex: make **** -e ENV=prod)
endif

define create-parameters
	$(eval DEBUG := $(if $(filter $(1),dev),1,0)) \
	$(eval PREFIX := $(if $(filter $(1),prod),,$(1))) \
	$(eval DOMAIN := $(if $(filter $(1),prod),poc.tf1.fr,poc.tf1.fr)) \
	$(eval ADMIN_DOMAIN := $(if $(filter $(1),dev),etf1.tf1.fr,tf1.fr)) \
	SYMFONY_DEBUG=$(DEBUG) \
	SYMFONY__FRONTEND_URL=http://$(PREFIX)www.$(DOMAIN) \
	SYMFONY__FRONTEND_HOSTNAME=$(PREFIX)www.$(DOMAIN) \
	SYMFONY__STATIC_HOSTNAME=s.$(PREFIX)www.$(DOMAIN) \
	SYMFONY__BACKEND_HOSTNAME=poc.$(PREFIX)backoffice.$(ADMIN_DOMAIN) \
	SYMFONY__DATABASE_HOST=devmutmys701-adm.dedale.tf1.fr \
	SYMFONY__DATABASE_PORT=4363 \
	SYMFONY__DATABASE_NAME=DEVDCIM701 \
	SYMFONY__DATABASE_USER=dev701_usr \
	SYMFONY__DATABASE_PASSWORD=dev701pwd \
	SYMFONY__REDIS_HOST=redis \
	SYMFONY__REDIS_PORT=6379 \
	SYMFONY__GRAYLOG_HOST=graylog \
	SYMFONY__GRAYLOG_PORT=12201 \
	SYMFONY__SECRET=15ef9csqcsq*/4dqs9hbdj- \
	SYMFONY__ASSETS_VERSION=1 \
	APPLICATION_ENV=$(ENV) \
	PORT=80
endef

define get-branch
$(shell git rev-parse --abbrev-ref HEAD)
endef

# Rules from files

vendor: composer.lock
ifeq ($(ENV), dev)
	$(COMPOSER) install --prefer-source --profile --ignore-platform-reqs
else
	$(COMPOSER) install --no-dev --prefer-dist --no-suggest --no-scripts
	$(COMPOSER) dump-autoload -o --no-dev
	$(COMPOSER) run build-bootstrap --no-dev
endif

composer.lock: composer.json
	@echo compose.lock is not up to date.

app/config/parameters.yml: app/config/parameters.yml.dist
	cp app/config/parameters.yml.dist app/config/parameters.yml

phpunit.xml: phpunit.xml.dist
	cp phpunit.xml.dist phpunit.xml