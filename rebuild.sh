#!/bin/bash

# Color codes for better readability
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
GRAY='\033[0;37m'
NC='\033[0m' # No Color

# Function to log messages with emoji
log() {
    case "$1" in
        start)
            echo -e "🚀 \033[1m**Starting rebuild process.**\033[0m"
            ;;
        deps)
            echo -e "\n📦 Preparing dependencies..."
            echo "   - Checking Node.js and npm..."
            echo -e "   ${GRAY}- Node.js version: $(node --version)${NC}"
            echo -e "   ${GRAY}- npm version: $(npm --version)${NC}"
            echo "   - Pulling latest repo changes..."
            ;;
        npm)
            echo -e "\n📦 Installing npm packages..."
            ;;
        build)
            echo -e "\n🏗️ Building frontend assets..."
            ;;
        complete)
            echo -e "\n✨ ${GREEN}\033[1mRebuild completed successfully!${NC}"
            ;;
        warning)
            echo -e "\n⚠️  ${YELLOW}$2${NC}"
            ;;
        security)
            echo -e "\n🛡️  ${RED}$2${NC}"
            ;;
    esac
}

# Function to handle security vulnerabilities
handle_vulnerabilities() {
    log security "Security vulnerabilities detected in npm packages!"
    echo -e "${YELLOW}Attempting to automatically fix vulnerabilities...${NC}"

    # Run npm audit fix
    npm audit fix

    # Check the result of npm audit fix
    if [ $? -eq 0 ]; then
        log security "Successfully applied security fixes using npm audit fix."

        # Run another audit to confirm
        npm audit
        if [ $? -ne 0 ]; then
            log warning "Some vulnerabilities remain. Manual review recommended."
        fi
    else
        log warning "Automatic vulnerability fix failed. Manual intervention required."
        echo -e "${RED}Please run 'npm audit' and address vulnerabilities manually.${NC}"
    fi
}

# Main rebuild process
main() {
    # Start process
    log start

    # Prepare dependencies
    log deps

    # Use nvm for Node.js management
    export NVM_DIR="$HOME/.nvm"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

    # Install or use latest Node.js
    nvm install node
    nvm use node

    # Pull latest changes
    git pull origin main

    # Install npm dependencies
    log npm
    npm install

    # Check for vulnerabilities
    npm audit > /dev/null
    if [ $? -ne 0 ]; then
        handle_vulnerabilities
    fi

    # Build frontend
    log build
    npm run build

    # Complete process
    log complete
}

# Execute main function
main "$@"

