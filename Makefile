APP_NAME=coop-auth
VERSION=0.0.1

# get extra arguments and filter out commands from args
args = $(filter-out $@,$(MAKECMDGOALS))

.PHONY: help
all: help
## help: Show this help message
help: Makefile
	@echo
	@echo " Choose a command to run in "$(APP_NAME)":"
	@echo
	@sed -n 's/^##//p' $< | column -t -s ':' |  sed -e 's/^/ /'
	@echo

# default that allows accepting extra args
%:
    @:

.PHONY: dev
## dev: Build and Run in dev mode
dev:
	@./dev.sh

.PHONY: composer-install
## composer-install: install composer dependencies
composer-install:
	@docker run --rm --interactive --tty --volume ${PWD}:/app composer:2.3.10 install

.PHONY: composer-update
## composer-update: update composer dependencies
composer-update:
	@docker run --rm --interactive --tty --volume ${PWD}:/app composer:2.3.10 update