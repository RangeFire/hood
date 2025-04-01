# Hood - Community Management Platform

Hood is a comprehensive community management system that helps you manage and engage with your community seamlessly.

## Features

- **User Management**: Authentication, registration, and profile management
- **GameConnect**: Discord integration and server management
- **Bugreport System**: Track and manage bug reports
- **Subscription Management**: Various subscription tiers with different features
- **File Upload**: Secure file storage and management
- **Community Center**: Centralized community engagement
- **Changelog System**: Keep your community informed about updates

## Tech Stack

- **Backend**: Laravel PHP Framework
- **Frontend**: Bootstrap, jQuery, FontAwesome
- **Storage**: S3 compatible file storage
- **Authentication**: Native authentication system
- **Payment Processing**: Integrated payment solutions

## Installation

### Requirements

- PHP 8.0 or higher
- Composer
- MySQL or compatible database
- Node.js and NPM

### Setup

1. Clone the repository
   ```
   git clone https://github.com/yourusername/hood.git
   cd hood
   ```

2. Install dependencies
   ```
   composer install
   npm install
   ```

3. Environment setup
   ```
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure your database in the .env file
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hood
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Run migrations and seeders
   ```
   php artisan migrate --seed
   ```

6. Build assets
   ```
   npm run dev
   ```

7. Start the server
   ```
   php artisan serve
   ```

## Project Structure

The project follows the standard Laravel structure with some additional directories:

- `app/` - Application code
- `config/` - Configuration files
- `database/` - Database migrations and seeds
- `public/` - Publicly accessible files and assets
- `resources/` - Views, assets, and language files
- `routes/` - Route definitions
- `storage/` - Application storage
- `tests/` - Test files

## License

© RangeFire
