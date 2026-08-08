SHELL := /bin/bash
-include .env
export

# Default: open SSH session
.DEFAULT_GOAL := ssh

# Minimal, modern SSH config
SSH_USER ?= karl
HOST ?= $(PRODUCTION)
SSH ?= ssh
SSH_ARGS ?=

.PHONY: ssh publish resume-pdf

ssh:
	@if [ -z "$(HOST)" ]; then \
		echo "Error: HOST not set. Set PRODUCTION in .env or pass HOST=example.com." >&2; \
		exit 1; \
	fi
	$(SSH) $(SSH_ARGS) $(SSH_USER)@$(HOST)

# Prepare a blog post: assets + OG card. Optional: SYNDICATE=1
# Usage: make publish SLUG=release-governance
#        make publish SLUG=release-governance SYNDICATE=1
publish:
	@if [ -z "$(SLUG)" ]; then \
		echo "Error: SLUG is required. Example: make publish SLUG=release-governance" >&2; \
		exit 1; \
	fi
	@if [ "$(SYNDICATE)" = "1" ]; then \
		php artisan post:publish "$(SLUG)" --syndicate; \
	else \
		php artisan post:publish "$(SLUG)"; \
	fi

resume-pdf:
	php artisan resume:pdf
