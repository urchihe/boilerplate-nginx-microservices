# .ALLPREFIX = "   "
# Include .env.test if it exists
ifneq (, $(wildcard ./.env.test))
	include .env.test
	export
endif

# Include .env if it exists
ifneq (, $(wildcard ./.env))
	include .env
	export
endif

# Include .env.prod if it exists
ifneq (, $(wildcard ./.env.prod))
	include .env.prod
	export
endif
# Target to create .env file from .env.example
create-env:
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo ".env file created from .env.example"; \
	else \
		echo ".env file already exists. Skipping."; \
	fi

#connect to php container
.PHONY: user-servicebash
user-servicebash: ;\
	docker compose exec user-service bash

#connect to rabbitmq container
.PHONY: rqbash
rqbash: ;\
	docker compose exec rabbitmq bash

#Launch migration
.PHONY: migrate
migrate: ;\
	docker compose exec user-service composer --run console doctrine:migrate:migrate -n

#up docker composer
up: create-env ;\
	DOCKER_BUILDKIT=1 docker compose up -d

#down docker composer
down: ;\
	docker compose down

# stronger down (remove volume / image / orphans)
.PHONY: fdown
fdown: ;\
   docker compose down -v --remove-orphans

# stronger up (recreate all container and rebuild the image)
fup: ;\
    DOCKER_BUILDKIT=1 docker compose up -d --force-recreate --build

#Launch  rabbitmq management UI
.PHONY: rqmui
rqmui: create-env ;\
	docker compose exec rabbitmq rabbitmq-plugins enable rabbitmq_management

# Soft Restart
.PHONY: restart
restart: down up

# Hard restart
.PHONY: frestart
frestart: fdown fup


.PHONY: dumpautoload
dumpautoload: ;\
	docker compose exec user-service composer -- dumpautoload

#
# Theses are static analyses + tests
#

.PHONY: phpmd
phpmd: ;\
	docker compose exec user-service composer -- run phpmd

.PHONY: cs-fix
cs-fix: ;\
	docker compose exec user-service composer -- run cs-fix


.PHONY: cs-check
cs-check: ;\
	docker compose exec user-service composer -- run cs-check

.PHONY: phpstan
phpstan: ;\
	docker compose exec user-service composer -- run phpstan

# Run all CI tools
.PHONY: ci
ci: cs-fix phpstan phpmd

.PHONY: test-user
test-user: ;\
	docker compose exec user-service composer run test

.PHONY: test-notification
test-notification: ;\
	docker compose exec notification-service composer run test

.PHONY: consume
consume: ;\
	docker compose exec user-service composer -- run console messenger:consume async -vv
