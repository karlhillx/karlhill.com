SHELL:=/bin/bash
include .env
export

# Config
IMAGE_NAME ?= karlhillcom-app
MIN_FREE_MB ?= 2048

.PHONY: deploy-image preflight ssh build-image

preflight:
	@echo "Preflight: checking local Docker daemon..."
	@docker info >/dev/null 2>&1 || (echo "Error: Local Docker daemon is not running. Start Docker Desktop or the docker service and retry." >&2; exit 1)
	@echo "Preflight: checking local SSH agent..."
	@if [ -z "$$SSH_AUTH_SOCK" ]; then \
		printf 'Error: No SSH agent detected. Start an agent and add your key.\n' >&2; \
		printf '  eval "$(ssh-agent -s)"\n' >&2; \
		printf '  ssh-add ~/.ssh/id_ed25519\n' >&2; \
		exit 1; \
	fi
	@ssh-add -l >/dev/null 2>&1 || (echo "Error: SSH agent has no identities or is locked. Add your key with: ssh-add ~/.ssh/id_ed25519" >&2; exit 1)
	@echo "Preflight: checking SSH connectivity to $(PRODUCTION)..."
	@ssh -o BatchMode=yes karl@$(PRODUCTION) 'echo ok' >/dev/null 2>&1 || (echo "Error: Cannot SSH to karl@$(PRODUCTION) using the current agent. Add your key with 'ssh-add ~/.ssh/id_ed25519', unlock it, or configure an IdentityFile in ~/.ssh/config." >&2; exit 1)
	@echo "Preflight: checking remote Docker daemon..."
	@ssh karl@$(PRODUCTION) 'docker info >/dev/null 2>&1' || (echo "Error: Remote Docker daemon is not running on $(PRODUCTION). Start it and retry." >&2; exit 1)
	@echo "Preflight: checking free space on remote /var/lib/docker ..."
	@ssh karl@$(PRODUCTION) "df -Pm /var/lib/docker 2>/dev/null | awk 'NR==2{print \$4}'" | awk -v min=$(MIN_FREE_MB) 'NF==0{exit 0} { if ($$1<min){ printf("Error: Only %d MB free on remote /var/lib/docker. Need at least %d MB.\n", $$1, min); exit 2 } }'
	@if [ $$? -eq 2 ]; then exit 1; fi
	@# Optional tool hints
	@if ! command -v pv >/dev/null 2>&1; then echo "Note: pv not found locally; proceeding without progress meter."; fi
	@if ! command -v bzip2 >/dev/null 2>&1 && ! command -v gzip >/dev/null 2>&1; then echo "Note: No compressor found (bzip2/gzip); streaming uncompressed tar."; fi
	@echo "Preflight: all checks passed."

build-image:
	@echo "Building Docker image $(IMAGE_NAME):latest ..."
	@docker build -t $(IMAGE_NAME):latest -f docker/Dockerfile .

deploy-image: preflight
	@# Ensure image exists; build if missing
	@docker image inspect $(IMAGE_NAME):latest >/dev/null 2>&1 || $(MAKE) build-image
	@echo "Deploying $(IMAGE_NAME):latest to $(PRODUCTION)..."
	@COMPRESS=\"\"; COMPRESS_NAME=\"none\"; if command -v bzip2 >/dev/null 2>&1; then COMPRESS=\"| bzip2\"; COMPRESS_NAME=\"bzip2\"; elif command -v gzip >/dev/null 2>&1; then COMPRESS=\"| gzip\"; COMPRESS_NAME=\"gzip\"; fi; \
	 PVPIPE=\"\"; PVNAME=\"none\"; if command -v pv >/dev/null 2>&1; then PVPIPE=\"| pv\"; PVNAME=\"pv\"; fi; \
	 echo \"Using compression: $$COMPRESS_NAME, progress: $$PVNAME\"; \
	 eval \"docker save $(IMAGE_NAME):latest $$COMPRESS $$PVPIPE | ssh karl@$(PRODUCTION) docker load\"

ssh:
	ssh karl@$(PRODUCTION)
