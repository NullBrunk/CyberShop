.DEFAULT_GOAL = help
port ?= 80
apiport ?= 8000

serv: ## Start the PHP server on port 80
	php artisan serve --port=$(port) --host=0.0.0.0&

api: ##  Start the API 
	php artisan serve --port=$(apiport) --host=0.0.0.0&

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-10s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


