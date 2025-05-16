#!/bin/bash

# Colors for terminal output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}=== AgriTech Béchar Deployment Script ===${NC}"

# Check if Heroku CLI is installed
if ! command -v heroku &> /dev/null; then
    echo -e "${RED}Heroku CLI is not installed. Please install it first:${NC}"
    echo "https://devcenter.heroku.com/articles/heroku-cli"
    exit 1
fi

# Login to Heroku
echo -e "${YELLOW}Logging in to Heroku...${NC}"
heroku login

# Create Heroku app
echo -e "${YELLOW}Creating Heroku app...${NC}"
heroku create agritech-bechar || echo -e "${YELLOW}App may already exist, continuing...${NC}"

# Add MySQL database
echo -e "${YELLOW}Adding MySQL database...${NC}"
heroku addons:create jawsdb:kitefin || echo -e "${YELLOW}Database may already exist, continuing...${NC}"

# Set environment variables
echo -e "${YELLOW}Setting environment variables...${NC}"
heroku config:set APP_KEY=base64:ZMOj5f5ZpC+y51TgTc2W6tIxofnVXw46jSPe24Emorc=
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set APP_NAME="AgriTech Béchar"

# Push to Heroku
echo -e "${YELLOW}Pushing code to Heroku...${NC}"
git push heroku master

# Run migrations and seeders
echo -e "${YELLOW}Running migrations and seeders...${NC}"
heroku run php artisan migrate --force
heroku run php artisan db:seed

# Open the application
echo -e "${GREEN}Deployment complete! Opening application...${NC}"
heroku open

echo -e "${GREEN}Your AgriTech Béchar application is now online!${NC}"
