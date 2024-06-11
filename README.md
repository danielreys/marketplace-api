# Installation

1. **Download the Repository**: Clone or download the repository to your local machine.

2. **Environment Setup**: 
   - Copy `.env_example` and create your own `.env` file in the root directory of the project.
   - Customize the environment variables in the `.env` file according to your setup.

## Database Setup

1. **Migration**: 
   - Run database migrations to create the necessary tables in your SQLite database.
   - Navigate to your project directory and execute the following command:
     ```bash
     php artisan migrate
     ```

2. **Configure SQLite in Your IDE**: 
   - After running migrations, a `database.sqlite` file will be generated in the `database/` directory.
   - It's recommended to add this file as a data source in your IDE. In PhpStorm, simply click on the `database.sqlite` file, and it will be added automatically.

## Testing

1. **Run Tests**: 
   - To execute the automated tests, run the following command in your terminal:
     ```bash
     php artisan test
     ```
   - This will run PHPUnit tests and ensure that your application functions correctly.

2. **API Testing**: 
   - All API endpoints have been thoroughly tested using Postman.
     
NOTE: Files will be saved locally in `storage\app\Files\media`

## Technologies & Tools Used

- **Laravel 11**: Framework
- **PHPUnit 11**: Testing
- **Sanctum**: Auth
- **SQLite**: Database
- **Postman**: Testing API endpoints
