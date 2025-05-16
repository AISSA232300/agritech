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

### GitHub Pages Deployment

The application is deployed on GitHub Pages and can be accessed at:

https://aissa232300.github.io/agritech/

The deployment is automated using GitHub Actions. When you push changes to the `main` branch, the application is automatically deployed to GitHub Pages.

### Manual Deployment

To deploy the application manually:

1. Push your code to GitHub:
   ```bash
   git remote add origin https://github.com/AISSA232300/agritech.git
   git branch -M main
   git push -u origin main
   ```

2. Go to the repository settings on GitHub:
   - Navigate to https://github.com/AISSA232300/agritech/settings/pages
   - Under "Source", select the `gh-pages` branch
   - Click "Save"

3. Wait for the GitHub Actions workflow to complete:
   - The workflow will build the application and deploy it to GitHub Pages
   - You can check the status of the workflow at https://github.com/AISSA232300/agritech/actions

4. Access your application at https://aissa232300.github.io/agritech/

## License

This project is licensed under the MIT License.
