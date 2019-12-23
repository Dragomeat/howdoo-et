#!/usr/bin/make

docker_compose_bin = docker-compose

configs = .env

.PHONY : help init start stop
.DEFAULT_GOAL := help

help: ## Show help text
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

---------------: ## Development tasks ---------------

init: configs start ## Initialize project
	@echo "********************************************************************\n\
	* This project was successfully initialized. Let's go to coding :) *\n\
	********************************************************************\n"

configs: $(configs) ## Enable development mode and generate secrets
	@echo "Config files have been created. Want to continue with default values? [Y/n]"
	@read line; if [ $$line == "n" ]; then echo Aborting; exit 1 ; fi

start: ## Run project in background
	${docker_compose_bin} up --build -d

stop: ## Stop project
	${docker_compose_bin} stop

$(configs):
	test -s $@ || cp $@.example $@
