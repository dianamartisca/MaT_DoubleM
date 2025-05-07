# My PHP Backend Project

This project is a simple PHP backend application designed to manage users and service requests. It provides a RESTful API for client applications to interact with user data and service requests.

## Project Structure

```
my-php-backend
├── public
│   ├── index.php          # Entry point of the application
│   └── api
│       ├── users.php      # API for user operations
│       └── requests.php   # API for service requests
├── src
│   ├── config
│   │   └── database.php   # Database connection configuration
│   ├── controllers
│   │   ├── UserController.php    # Controller for user-related operations
│   │   └── RequestController.php  # Controller for request-related operations
│   ├── models
│   │   ├── User.php       # User model
│   │   └── Request.php    # Request model
│   └── helpers
│       └── response.php   # Helper functions for JSON responses
├── db
│   └── issuesdb.sql      # SQL commands for database structure and initial data
└── README.md              # Project documentation
```

## Setup Instructions

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd my-php-backend
   ```

2. **Set up the database**:
   - Import the `issuesdb.sql` file into your MySQL database using phpMyAdmin or a similar tool.

3. **Configure the database connection**:
   - Update the `src/config/database.php` file with your database credentials.

4. **Run the application**:
   - Use a local server like XAMPP or MAMP to serve the `public` directory.

## Usage Guidelines

- **API Endpoints**:
  - `POST /api/users.php` - Create a new user.
  - `GET /api/users.php` - Retrieve user information.
  - `PUT /api/users.php` - Update user information.
  - `DELETE /api/users.php` - Delete a user.
  - `POST /api/requests.php` - Submit a new service request.
  - `GET /api/requests.php` - Retrieve service requests.

## SQL Commands

The following SQL commands are used to create the necessary database tables:

```sql
-- Create users table
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create requests table
CREATE TABLE `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `date_requested` date NOT NULL,
  `description` text NOT NULL,
  `response` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## License

This project is licensed under the MIT License.