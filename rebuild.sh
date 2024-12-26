# This script automates the deployment and rebuild process

# Exit on any error
set -e

# Color codes for better readability
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to log messages
log() {
    echo -e "${GREEN}[REBUILD]${NC} $1"
}

# Function to log warnings
warn() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Function to install Node.js and npm globally
install_node_npm() {
    log "Installing Node.js and npm globally"

    # Check if nvm is installed
    if [ -d "$HOME/.nvm" ]; then
        # Source nvm
        export NVM_DIR="$HOME/.nvm"
        [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

        # Install latest LTS version of Node.js
        nvm install --lts
        nvm use --lts
    else
        # If nvm is not installed, use alternative method
        # This might vary depending on your operating system

        # For Ubuntu/Debian
        if [ -x "$(command -v apt-get)" ]; then
            curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
            sudo apt-get install -y nodejs

        # For macOS with Homebrew
        elif [ -x "$(command -v brew)" ]; then
            brew install node

        # For other systems, you might need to adjust
        else
            warn "Automatic Node.js installation not supported on this system"
            exit 1
        fi
    fi

    # Verify installation
    node --version
    npm --version
}

# Main rebuild process
main() {
    log "Starting rebuild process."

    # Install Node.js and npm globally
    install_node_npm

    # Pull latest changes
    log "Pulling latest repository changes"
    git pull origin main

    # Install/update dependencies
    log "Installing/updating PHP dependencies"
    composer install --no-interaction --prefer-dist --optimize-autoloader

    # Install/update npm dependencies
    log "Installing/updating npm dependencies"
    npm install
    npm run build

    log "Rebuild completed successfully for ${DOMAIN}."
}

# Execute main function with domain argument
main "$@"
