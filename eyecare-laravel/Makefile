DOCKER_COMPOSE = docker compose
EXEC = $(DOCKER_COMPOSE) exec php
ARTISAN = $(EXEC) php artisan

.PHONY: build up down restart shell artisan migrate seed test logs

build:
	$(DOCKER_COMPOSE) build

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

restart: down up

shell:
	$(EXEC) sh

artisan:
	$(ARTISAN) $(cmd)

migrate:
	$(ARTISAN) migrate --force

seed:
	$(ARTISAN) db:seed --force

migrate-fresh:
	$(ARTISAN) migrate:fresh --force --seed

test:
	$(ARTISAN) test

logs:
	$(DOCKER_COMPOSE) logs -f
