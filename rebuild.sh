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
            echo -e "🚀 \033[1mStarting build process.\033[0m"
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
            echo -e "\n✨ ${GREEN}\033[1mBuild completed successfully!${NC}"
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

# Function to check if it's a Laravel application
is_laravel_app() {
    if [ -f "artisan" ] && [ -d "app" ] && [ -d "bootstrap" ] && [ -d "config" ]; then
        return 0  # It's a Laravel app
    else
        return 1  # Not a Laravel app
    fi
}

# Function to run Laravel-specific commands
run_laravel_commands() {
    echo -e "\n🔧 ${YELLOW}Detected Laravel application. Running Laravel-specific commands...${NC}"

    # Composer install
    log "Composer install"
    composer install

    # Generate application key
    echo -e "\n🔑 Generating application key..."
    php artisan key:generate

    # Clear application cache
    echo -e "\n🧹 Clearing application cache..."
    php artisan cache:clear

    # Optimize application
    echo -e "\n🚀 Optimizing application..."
    php artisan optimize

    echo -e "\n✨ ${GREEN}Laravel artisan commands completed!${NC}"
}

# Main rebuild process
main() {
    # Start process
    log start

    # Prepare dependencies
    log deps

    # Pull latest changes
    git pull origin main

    # Install npm dependencies
    log npm
    npm i npm@latest -g
    npm rebuild esbuild

    # Check for vulnerabilities
    npm audit > /dev/null
    if [ $? -ne 0 ]; then
        handle_vulnerabilities
    fi

    # Build frontend
    log build
    npm rebuild esbuild

    # Check if it's a Laravel application
    if is_laravel_app; then
        run_laravel_commands
    else
        echo -e "\n❌ ${YELLOW}Not a Laravel application. Skipping Laravel-specific commands.${NC}"
    fi

    # Complete process
    log complete
}

# Execute main function
main "$@"
