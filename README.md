<div align="center">
  
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
  
    
![GitHub top language](https://img.shields.io/github/languages/top/NullBrunk/E-Commerce?style=for-the-badge)
![GitHub commit activity](https://img.shields.io/github/commit-activity/m/NullBrunk/E-Commerce?style=for-the-badge)
![repo size](https://img.shields.io/github/repo-size/NullBrunk/E-Commerce?style=for-the-badge)

![image](https://user-images.githubusercontent.com/125673909/236008769-2e900822-be7e-4c74-a87e-bfcc22bd69ec.png)


</div> 

# E-Commerce
An E-Commerce website with the Laravel Framework (Currently in dev)

The Web App is iserved on localhost:80, and the API is served on localhost:8000.


# Installation

```sql
CREATE DATABASE ecommerce;
use ecommerce;

CREATE TABLE users(
    `id` INT AUTO_INCREMENT,
    `mail` VARCHAR(50) UNIQUE NOT NULL,
    `pass` VARCHAR(130) NOT NULL,
    `is_admin` SMALLINT DEFAULT 0,

    PRIMARY KEY(`id`)   
);

CREATE TABLE cart(
    `id` INT AUTO_INCREMENT,
    `id_user` INT NOT NULL,
    `id_product` INT NOT NULL,

    PRIMARY KEY(`id`),
    FOREIGN KEY(`id_user`) REFERENCES users(`id`),
    FOREIGN KEY(`id_product`) REFERENCES products(`id`)
);

CREATE TABLE products(
    `id` INT AUTO_INCREMENT,
    `id_user` INT NOT NULL,
    `name` VARCHAR(45) NOT NULL,
    `price` VARCHAR(20),
    `descr` TEXT,
    `class` TEXT,
    `image` VARCHAR(50) NOT NULL,

    FOREIGN KEY(`id_user`) REFERENCES users(`id`),
    PRIMARY KEY(`id`)
);

CREATE TABLE comments(
    `id` INT AUTO_INCREMENT,
    `id_product` INT NOT NULL, 
    `id_user` INT NOT NULL,
    `title` VARCHAR(45) NOT NULL,
    `content` TEXT NOT NULL,
    `writed_at` DATETIME NOT NULL,

    PRIMARY KEY(`id`),
    FOREIGN KEY(`id_user`) REFERENCES users(`id`),
    FOREIGN KEY(`id_product`) REFERENCES product(`id`)
);

CREATE TABLE contact(
    `id` INT AUTO_INCREMENT,
    `readed` BOOL,
    `id_contactor` INT NOT NULL,
    `id_contacted` INT NOT NULL,
    `content` TEXT NOT NULL,

    FOREIGN KEY(`id_contactor`) REFERENCES users(id),
    FOREIGN KEY(`id_contacted`) REFERENCES users(id),

    PRIMARY KEY(`id`)
);

```

```bash
# Start the MySQL service
php artisan mysql

# Link the storage directory to public/storage/
php artisan storage:link

# Start the WebApp in a terminal
sudo php artisan serve --port=80 --host=0.0.0.0

# Start the API on port 8000 on another terminal
sudo php artisan serve --port=8000 --host=0.0.0.0
```


