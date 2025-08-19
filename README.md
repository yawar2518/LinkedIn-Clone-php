# NotLinkedIn - LinkedIn Clone (PHP & MySQL)

A simple LinkedIn-like platform built with PHP and MySQL, featuring user authentication, startup and job management, messaging, notifications, and connections.

## Features

- User registration and login
- Create and manage startups
- Post and view jobs
- Send and receive messages
- Notifications for new messages and connections
- Manage connections (send, accept, decline)
- User dashboard with profile, startups, jobs, messages, and notifications

## Project Structure

```
LinkedIn-Clone-php/
  NOTLINKEDIN/
    back_end/      # PHP backend scripts (authentication, jobs, startups, messaging, etc.)
    front_end/     # HTML/CSS frontend pages
    Database/      # SQL schema and sample data
    logs/          # Error logs
```

## Setup Instructions

1. **Clone the repository**
   ```sh
   git clone https://github.com/yourusername/LinkedIn-Clone-php.git
   ```

2. **Database Setup**
   - Import the SQL file into your MySQL server:
     - Open phpMyAdmin or use the MySQL CLI.
     - Create a database named `notlinkedin`.
     - Import [`NOTLINKEDIN/Database/notlinkedin.sql`](NOTLINKEDIN/Database/notlinkedin.sql).

3. **Configure Database Connection**
   - Edit [`NOTLINKEDIN/back_end/db.php`](NOTLINKEDIN/back_end/db.php) if your MySQL credentials differ from the defaults.

4. **Run the Project**
   - Place the `LinkedIn-Clone-php/NOTLINKEDIN` folder in your web server's root directory (e.g., `htdocs` for XAMPP).
   - Access the app in your browser at `http://localhost/NOTLINKEDIN/front_end/index.html`.

## Usage

- **Sign Up:** Register a new account via the Sign Up page.
- **Login:** Log in to access your dashboard.
- **Create Startup:** Add your startup and manage its details.
- **Post Job:** Post jobs under your startup.
- **Messaging:** Send and receive messages with other users.
- **Connections:** Send, accept, or decline connection requests.
- **Notifications:** View notifications for new messages and connections.

## File Overview

- Backend logic: [`NOTLINKEDIN/back_end/`](NOTLINKEDIN/back_end/)
- Frontend pages: [`NOTLINKEDIN/front_end/`](NOTLINKEDIN/front_end/)
- Database schema: [`NOTLINKEDIN/Database/notlinkedin.sql`](NOTLINKEDIN/Database/notlinkedin.sql)

## Requirements

- PHP 7.x or higher
- MySQL/MariaDB
- Web server (Apache recommended)

## License

This project is for educational purposes.

---

**Note:** For demo/testing, two users are pre-created in the database:
- test / test@gmail.com
- test2 / test2@gmail.com

Passwords are hashed; set your own via the Sign Up
