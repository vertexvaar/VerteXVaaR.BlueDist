## Show this help
help:
	echo "$(EMOJI_interrobang) Makefile version $(VERSION) help "
	echo ''
	echo 'About this help:'
	echo '  Commands are ${BLUE}blue${RESET}'
	echo '  Targets are ${YELLOW}yellow${RESET}'
	echo '  Descriptions are ${GREEN}green${RESET}'
	echo ''
	echo 'Usage:'
	echo '  ${BLUE}make${RESET} ${YELLOW}<target>${RESET}'
	echo ''
	echo 'Targets:'
	awk '/^[a-zA-Z\-\_0-9]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")+1); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf "  ${YELLOW}%-${TARGET_MAX_CHAR_NUM}s${RESET} ${GREEN}%s${RESET}\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

## Creates a backup of the database
mysql-dump:
	echo "$(EMOJI_floppy_disk) Dumping the database"
	mkdir -p $(SQLDUMPSDIR)
	docker-compose exec -u 1000:1000 mysql bash -c "mysqldump -u$(MYSQL_USER) -p$(MYSQL_PASSWORD) --add-drop-database --create-options --extended-insert --no-autocommit --quick --default-character-set=utf8 $(MYSQL_DATABASE) | gzip > /$(SQLDUMPSDIR)/$(SQLDUMPFILE)"

## Restores the database from the backup file defined in .env
mysql-restore:
	echo "$(EMOJI_robot) Restoring the database"
	docker-compose exec mysql bash -c 'DUMPFILE="/$(SQLDUMPSDIR)/$(SQLDUMPFILE)"; if [[ "$${DUMPFILE##*.}" == "sql" ]]; then cat $$DUMPFILE; else zcat $$DUMPFILE; fi | mysql --default-character-set=utf8 -u$(MYSQL_USER) -p$(MYSQL_PASSWORD) $(MYSQL_DATABASE)'

## Stop all containers
stop:
	echo "$(EMOJI_stop) Shutting down"
	docker-compose stop
	sleep 0.4
	docker-compose down --remove-orphans

## Removes all containers and volumes
destroy: stop
	echo "$(EMOJI_litter) Removing the project"
	docker-compose down -v --remove-orphans

## Starts docker-compose up -d
start:
	echo "$(EMOJI_up) Starting the docker project"
	docker-compose up -d --build

## Starts composer-install
composer:
	echo "$(EMOJI_package) Installing composer dependencies"
	docker-compose exec php composer $(ARGS)

## Starts composer-install
composer-install:
	echo "$(EMOJI_package) Installing composer dependencies"
	docker-compose exec php composer install

## Create necessary directories
create-dirs:
	echo "$(EMOJI_dividers) Creating required directories"
	mkdir -p $(SQLDUMPSDIR)

## Starts composer-install
composer-install-production:
	echo "$(EMOJI_package) Installing composer dependencies (without dev)"
	docker-compose exec php composer install --no-dev -ao

## Create SSL certificates for dinghy and starting project
create-certificate:
	echo "$(EMOJI_secure) Creating SSL certificates for dinghy http proxy"
	mkdir -p $(HOME)/.dinghy/certs/
	PROJECT=$$(echo "$${PWD##*/}" | tr -d '.'); \
	if [[ ! -f $(HOME)/.dinghy/certs/$$PROJECT.docker.key ]]; then { openssl req -x509 -newkey rsa:2048 -keyout $(HOME)/.dinghy/certs/$$PROJECT.docker.key -out $(HOME)/.dinghy/certs/$$PROJECT.docker.crt -days 365 -nodes -subj "/C=US/ST=Oregon/L=Portland/O=Company  Name/OU=Org/CN=$$PROJECT.docker" -config <(cat /etc/ssl/openssl.cnf <(printf "[SAN]\nsubjectAltName=DNS:$$PROJECT.docker")) -reqexts SAN -extensions SAN; } fi
	if [[ ! -f $(HOME)/.dinghy/certs/${HOST}.key ]]; then { openssl req -x509 -newkey rsa:2048 -keyout $(HOME)/.dinghy/certs/${HOST}.key -out $(HOME)/.dinghy/certs/${HOST}.crt -days 365 -nodes -subj "/C=US/ST=Oregon/L=Portland/O=Company  Name/OU=Org/CN=${HOST}" -config <(cat /etc/ssl/openssl.cnf <(printf "[SAN]\nsubjectAltName=DNS:${HOST}")) -reqexts SAN -extensions SAN; } fi

## Initialize the docker setup
init-docker: create-dirs create-certificate
	echo "$(EMOJI_rocket) Initializing docker environment"
	docker-compose pull
	docker-compose up -d --build
	docker-compose exec -u0 php chown -R 1000:1000 /opt/

## To start an existing project incl. rsync from fileadmin, uploads and database dump
install-project: stop add-hosts-entry init-docker composer-install mysql-restore
	echo "---------------------"
	echo ""
	echo "The project is online $(EMOJI_thumbsup)"
	echo ""
	echo 'Stop the project with "make stop"'
	echo ""
	echo "---------------------"
	make urls

## Print Project URIs
urls:
	PROJECT=$$(echo "$${PWD##*/}" | tr -d '.'); \
	SERVICES=$$(docker-compose ps --services | grep '$(SERVICELIST)'); \
	LONGEST=$$(($$(echo -e "$$SERVICES\nFrontend:" | wc -L 2> /dev/null || echo 15)+2)); \
	echo "$(EMOJI_telescope) Project URLs:"; \
	echo ''; \
	printf "  %-$${LONGEST}s %s\n" "Frontend:" "https://$(HOST)/"; \
	for service in $$SERVICES; do \
		printf "  %-$${LONGEST}s %s\n" "$$service:" "https://$$service.$$PROJECT.docker/"; \
	done;

## Create the hosts entry for the custom project URL (non-dinghy convention)
add-hosts-entry:
	echo "$(EMOJI_monkey) Creating Hosts Entry (if not set yet)"
	SERVICES=$$(command -v getent > /dev/null && echo "getent ahostsv4" || echo "dscacheutil -q host -a name"); \
	if [ ! "$$($$SERVICES $(HOST) | grep 127.0.0.1 > /dev/null; echo $$?)" -eq 0 ]; then sudo bash -c 'echo "127.0.0.1 $(HOST)" >> /etc/hosts; echo "Entry was added"'; else echo 'Entry already exists'; fi;

## Log into the PHP container
login-php:
	echo "$(EMOJI_elephant) Logging into the PHP container"
	docker-compose exec php bash

## Log into the mysql container
login-mysql:
	echo "$(EMOJI_dolphin) Logging into MySQL Container"
	docker-compose exec mysql bash

## Log into the httpd container
login-httpd:
	echo "$(EMOJI_helicopter) Logging into HTTPD Container"
	docker-compose exec httpd bash

include .env

# SETTINGS
TARGET_MAX_CHAR_NUM := 25
MAKEFLAGS += --silent
SHELL := /bin/bash
VERSION := 1.0.0
ARGS = $(filter-out $@,$(MAKECMDGOALS))

# COLORS
GREEN  := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
BLUE   := $(shell tput -Txterm setaf 4)
WHITE  := $(shell tput -Txterm setaf 7)
RESET  := $(shell tput -Txterm sgr0)

# EMOJIS (some are padded right with whitespace for text alignment)
EMOJI_litter := "🚮️"
EMOJI_interrobang := "⁉️ "
EMOJI_floppy_disk := "💾️"
EMOJI_dividers := "🗂️ "
EMOJI_up := "🆙️"
EMOJI_receive := "📥️"
EMOJI_robot := "🤖️"
EMOJI_stop := "🛑️"
EMOJI_package := "📦️"
EMOJI_secure := "🔐️"
EMOJI_explodinghead := "🤯️"
EMOJI_rocket := "🚀️"
EMOJI_plug := "🔌️"
EMOJI_leftright := "↔️ "
EMOJI_upright := "↗️ "
EMOJI_thumbsup := "👍️"
EMOJI_telescope := "🔭️"
EMOJI_monkey := "🐒️"
EMOJI_elephant := "🐘️"
EMOJI_dolphin := "🐬️"
EMOJI_helicopter := "🚁️"
EMOJI_broom := "🧹"
EMOJI_controlknobs := "🎛️"

%:
	@:
