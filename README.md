# Art Gallery Management System

A web application for managing an art gallery's artworks and warehouses.

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Composer (for dependency management)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yosraessid/art_gallery.git
cd art_gallery
```

2. Create a MySQL database and import the schema:
```bash
mysql -u your_username -p your_database_name < database/schema.sql
```

3. Copy the configuration example file and update it with your database credentials:
```bash
cp config.example.php config.php
```

4. Update the database connection settings in `config.php`

5. Start your web server and navigate to the application URL

## Features

- User authentication (login/logout)
- Artwork management (add, edit, delete)
- Warehouse management (add, edit, delete)
- Dashboard with statistics
- Responsive design

## Project Structure

```
art_gallery/
├── assets/
│   ├── css/
│   └── images/
├── includes/
│   └── navbar.php
├── database/
│   └── schema.sql
├── artworks.php
├── warehouses.php
├── index.php
├── login.php
├── logout.php
├── config.php
└── README.md
```

## License

This project is licensed under the MIT License.
