# AgriTech Béchar

AgriTech Béchar is a Laravel-based application for agricultural technology management. It provides features for managing pivots, monitoring pests, planning tasks, and more.

## Features

- User authentication and role-based access control
- Dashboard for monitoring agricultural data
- Pivot management
- Pest monitoring
- Task planning
- Profile management
- Reporting

## Requirements

- PHP >= 7.4
- Composer
- MySQL or SQLite
- Node.js & NPM (for frontend assets)

## Local Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/AISSA232300/agritech.git
   cd agritech
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Create a copy of the .env file:
   ```bash
   cp .env.example .env
   ```

4. Generate an application key:
   ```bash
   php artisan key:generate
   ```

5. Configure your database in the .env file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=agritech_bechar
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

7. Compile assets:
   ```bash
   npm run dev
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

9. Visit http://localhost:8000 in your browser.

## Deployment

### Deploying to Heroku

1. Create a GitHub repository:
   - Go to GitHub: https://github.com/new
   - Name the repository: `agritech`
   - Choose "Public" visibility
   - Click "Create repository"

2. Push your code to GitHub:
   ```bash
   git remote add origin https://github.com/AISSA232300/agritech.git
   git branch -M main
   git push -u origin main
   ```

3. Use the deployment script:
   ```bash
   ./deploy.sh
   ```

   This script will:
   - Log in to Heroku
   - Create a new Heroku app
   - Add a MySQL database
   - Set environment variables
   - Push the code to Heroku
   - Run migrations and seeders
   - Open the application

### Manual Deployment

1. Create a Heroku account: https://signup.heroku.com/
2. Install the Heroku CLI: https://devcenter.heroku.com/articles/heroku-cli
3. Login to Heroku: `heroku login`
4. Create a new Heroku app: `heroku create agritech-bechar`
5. Add a MySQL database: `heroku addons:create jawsdb:kitefin`
6. Push to Heroku: `git push heroku main`
7. Run migrations: `heroku run php artisan migrate --force`
8. Run seeders: `heroku run php artisan db:seed`
9. Set environment variables:
   ```bash
   heroku config:set APP_KEY=base64:ZMOj5f5ZpC+y51TgTc2W6tIxofnVXw46jSPe24Emorc=
   heroku config:set APP_ENV=production
   heroku config:set APP_DEBUG=false
   heroku config:set APP_NAME="AgriTech Béchar"
   ```
10. Open your application: `heroku open`

## License

This project is licensed under the MIT License.
