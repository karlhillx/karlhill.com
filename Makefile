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

.PHONY: ssh

ssh:
	@if [ -z "$(HOST)" ]; then \
		echo "Error: HOST not set. Set PRODUCTION in .env or pass HOST=example.com." >&2; \
		exit 1; \
	fi
	$(SSH) $(SSH_ARGS) $(SSH_USER)@$(HOST)
