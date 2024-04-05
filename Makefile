# .ALLPREFIX = "   "
# Load .env variable (all .env are added if available)
ifneq (, $(wildcard ./.env.test))
	include .env
	export
endif
ifneq (, $(wildcard ./.env))
	include .env
	export
endif
ifneq (, $(wildcard ./.env.pro))
	include .env.prod
	export
endif

#connect to php container
.PHONY: appbash
appbash: ;\
	docker compose exec app bash

#connect to rabbitmq container
.PHONY: rqbash
rqbash: ;\
	docker compose exec rabbitmq bash

#Launch migration
.PHONY: migrate
migrate: ;\
	docker compose exec app composer --run console doctrine:migrate:migrate -n

#Launch  rabbitmq management UI
.PHONY: rqmui
rqmui: ;\
	docker compose exec rabbitmq composer --run console rabbitmq-plugins enable rabbitmq_management;

#up docker composer
up: ;\
	DOCKER_BUILDKIT=1 docker compose up -d

#down docker composer
down: ;\
	docker compose down

#Launch  rabbitmq management UI
.PHONY: rqmui
rqmui: ;\
	docker compose exec rabbitmq composer --run console rabbitmq-plugins enable rabbitmq_management;