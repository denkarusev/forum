SHELL=/bin/bash -e

.DEFAULT_GOAL := help

-include .env

ifeq ($(PROJECT_INTERFACE),)
	PROJECT_INTERFACE := $(shell ./scripts/get-free-interface.sh)
endif
ifeq ($(PROJECT_ID),)
# Абсолютный путь до последнего включенного (include) файла
	ABS_FILE_PATH := $(abspath $(lastword $(MAKEFILE_LIST)))
	PROJECT_ID := $(notdir $(patsubst %/,%,$(dir $(ABS_FILE_PATH))))
endif
ifeq ($(PROJECT_DOMAIN),)
	PROJECT_DOMAIN := "${PROJECT_ID}.local"
endif

export PROJECT_ID
export PROJECT_DOMAIN
export PROJECT_INTERFACE

help: ## Справка
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Билд проекта
	@docker-compose build --build-arg UID=$$(id -u) --build-arg GID=$$(id -g) php
	@docker-compose build --build-arg UID=$$(id -u) --build-arg GID=$$(id -g) nginx
	@docker-compose build --build-arg UID=$$(id -u) --build-arg GID=$$(id -g) mariadb
	@docker-compose build --build-arg UID=$$(id -u) --build-arg GID=$$(id -g) adminer

prepare-app: composer-install key-generate db-fresh passport-keys clear-cache ## Первый запуск
	@echo -e "Make: App is completed. \n"

up: ## Запуск проекта
	@./scripts/ports-free-check.sh
	@docker-compose up -d --remove-orphans

down: ## Остановка проекта
	@docker-compose down

restart: down up

composer-install: ## Установка composer
	@docker-compose exec --user www-data php composer install -d /var/www/html

key-generate: ## Генерирование ключа приложения
	@docker-compose exec --user www-data php php artisan key:generate

passport-keys: ## Генерирование ключей для passport
	@docker-compose exec --user www-data php php artisan passport:install

clear-cache:
	@docker-compose exec --user www-data php php artisan config:clear

env: ## Генерация .env файла проекта
	@test -f .env && echo -ne "\n# ===== Скопированно с .env " >> .env.backup \
		&& date --iso-8601=seconds | tr -d "\n" >> .env.backup \
		&& echo -ne " ====="  >> .env.backup \
		&& cat .env >> .env.backup \
		&& echo "Предыдущая конфигурация сохранена в .env.backup" \
		|| echo
	@echo -e "\n# Домен на котором будет доступен проект" > .env
	@echo "PROJECT_DOMAIN=${PROJECT_DOMAIN}" >> .env
	@echo '# Интерфейс (IP адрес) на котором будут слушать докеры проекта' >> .env
	@echo "PROJECT_INTERFACE=${PROJECT_INTERFACE}" >> .env
	@echo >> .env
	@cat .env.example >> .env
	@echo ".env создан"

install: ## Установка проекта
	@grep '${PROJECT_DOMAIN}' /etc/hosts 1>/dev/null || sudo sh -c "echo '${PROJECT_INTERFACE} ${PROJECT_DOMAIN}' >> /etc/hosts"
	@echo '${PROJECT_DOMAIN} записан в /etc/hosts и будет доступен по адресу http://${PROJECT_DOMAIN}'

db-fresh: ## Пересборка БД + сидеры
	@docker-compose exec --user www-data php php artisan migrate:fresh --seed --force

clear: ## Сброс всех видов кэшей
	@docker-compose exec --user www-data php php artisan optimize:clear
	@docker-compose exec --user www-data php php artisan config:clear
	@docker-compose exec --user www-data php php artisan route:clear
	@docker-compose exec --user www-data php php artisan event:clear
	@docker-compose exec --user www-data php php artisan view:clear
	@docker-compose exec --user www-data php php artisan cache:clear

test: clear ## Запуск тестов
	@docker-compose exec --user www-data php php artisan test

bash: ## Запуск консоли
	@docker-compose exec --user www-data php bash

create-test-db: ## Создание тестовой базы данных
	@docker-compose exec mariadb mysql -u$(DB_USERNAME) -p$(DB_ROOT_PASSWORD) -e 'CREATE DATABASE ${DB_DATABASE}_test'
	@echo "База данных $(DB_DATABASE)_test создана"

default: help
