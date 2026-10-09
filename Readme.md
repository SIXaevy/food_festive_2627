# Food Festive Project Setup

This guide provides a step-by-step process for creating the Docker configuration and PHP project structure for your web application.

## 1A. Install Docker Desktop

Download and install Docker Desktop from the official Docker website:

https://www.docker.com/products/docker-desktop/

After installation, make sure Docker Desktop is running before continuing.

> If Docker Desktop is not running, the container cannot build or start correctly.

---

## 1. Open the Project Folder

The Docker Compose file is inside the `food_festive_2627` folder. From the parent `food_festive_3d` folder, the project is laid out like this:

```text
food_festive_3d/
├── db_food_festive.sql
└── food_festive_2627/
    ├── Dockerfile
    ├── docker-compose.yml
    └── src/
```

Run the commands below from `food_festive_3d`. If your terminal is already inside `food_festive_2627`, omit the `-f food_festive_2627\docker-compose.yml` argument.

## 2. Create the Dockerfile

Create a file named `Dockerfile` inside the root folder and paste the following code:

```dockerfile
FROM php:8.2-apache

WORKDIR /var/www/html

COPY src/ /var/www/html/

RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]
```

This file sets up the PHP Apache environment and serves the project from the `src` folder.

---

## 3. Create the docker-compose.yml File

Create a file named `docker-compose.yml` in the project root and copy the values from the existing project file:

```yaml
version: '3.9'

services: 
  php-env: 
    build: .
    container_name: 'app_server'
    volumes: 
      - ./src:/var/www/html
    ports:
      - 9000:80
  mysql_db:
    image: mysql:latest
    container_name: 'db_server'
    environment:
      MYSQL_ROOT_PASSWORD: root
    volumes:
      - mysql_data:/var/lib/mysql
  phpmyadmin:
    image: phpmyadmin/phpmyadmin:latest
    container_name: 'dbms_software'
    environment:
      PMA_HOST: mysql_db
      PMA_USER: root
      PMA_PASSWORD: root
    ports:
      - 9001:80

volumes:
  mysql_data:
```

This is the Docker Compose configuration used by the project. It includes the PHP app container, MySQL database, and phpMyAdmin services. The named `mysql_data` volume persists MySQL data when the container is recreated.

---

## 4. Create the src Folder and index.php File

Inside the `src` folder, create an `index.php` file with the following content:

```php
<?php
phpinfo();
```

This file is the homepage of your PHP application. When you visit the page, PHP will automatically generate the PHP information page.

> Before visiting the index page in the browser, make sure the `.htaccess` file is already created inside the same `src` folder.

---

## 5. Create the .htaccess File

Create a file named `.htaccess` inside the `src` folder.

This file is used by Apache to enable clean URLs and rewrite requests properly.

Copy and paste the following code into the `.htaccess` file:

```apache
<IfModule mod_rewrite.c>
  RewriteEngine On

  # Ensure the rules are only applied to non-existing files and directories
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d

  # Rewrite any URL without a file extension to .php
  RewriteRule ^([^\.]+)$ $1.php [NC,L]
</IfModule>
```

This code allows clean URLs and rewrites requests to `.php` files.

---

## 6. Start the Docker Container

Before opening the index page, make sure the `.htaccess` file is already inside the `src` folder.

Run the following command from the `food_festive_3d` parent folder:

```bash
docker compose -f food_festive_2627\docker-compose.yml up --build
```

This command will:
- Build the Docker image
- Create and start the container
- Serve the application from the `src` directory

---

## 7. Open the Website in the Browser

Once the container is running, open the browser and visit the app using the port mapped in Docker Desktop.

You should see the PHP information page generated automatically by `phpinfo()`. This page displays the PHP configuration, server information, and environment details.

---

## 8. Stop the Container

When you are done, stop the container with this command from `food_festive_3d`:

```bash
docker compose -f food_festive_2627\docker-compose.yml down
```

This keeps the `mysql_data` volume. Do not add `-v` unless you intend to delete the database and all its data.

---

## Final Project Structure

```text
food_festive_3d/
├── db_food_festive.sql
└── food_festive_2627/
    ├── Dockerfile
    ├── docker-compose.yml
    └── src/
        ├── .htaccess
        └── index.php
```

This setup is the basic foundation for creating a PHP web application using Docker.

---

## Completion Form

After finishing the task, answer the form here:

https://forms.gle/eCjHgKjFP8iG1cUj7
