-include .env

DC?=docker compose
PHP_CLI=docker_php-cli

.PHONY: help
help:
	@echo "\
Usage: \n\
	make { up Поднять приложение с нуля \n\
		 | down Остановить приложение \n\
		 | migrate Применить миграции \n\
	} \n\
"

.PHONY: up
up:	dc-up init migrate add-domain-to-hosts print-url

.PHONY: down
down:	dc-down

.PHONY: dc-up
dc-up:
	${DC} up --build --force-recreate -d

.PHONY: dc-down
dc-down:
	${DC} down

.PHONY: init
init:
	./docker_php-cli ./init --env=Development --overwrite=All --delete=All


.PHONY: migrate
migrate:
	./docker_php-cli ./yii migrate --interactive=0

.PHONY: print-url
print-url:
	@echo "\
	Frontend: http://${EXTERNAL_HOST}:${EXTERNAL_NGINX_PORT} \n\
"

.PHONY: flush-all-cache
flush-all-cache:
	./docker_php-cli ./yii cache/flush-all

.PHONY: add-domain-to-hosts
add-domain-to-hosts:
	@grep -qF "127.0.0.1 ${EXTERNAL_HOST}" /etc/hosts || sudo bash -c 'echo -e "\n\n127.0.0.1 ${EXTERNAL_HOST}\n" >> /etc/hosts'