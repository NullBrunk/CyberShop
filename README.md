<div align="center">

<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
  
![GitHub top language](https://img.shields.io/github/languages/top/NullBrunk/E-Commerce?style=for-the-badge)
![GitHub commit activity](https://img.shields.io/github/commit-activity/m/NullBrunk/E-Commerce?style=for-the-badge)
![repo size](https://img.shields.io/github/repo-size/NullBrunk/E-Commerce?style=for-the-badge)

![image](https://user-images.githubusercontent.com/125673909/236008769-2e900822-be7e-4c74-a87e-bfcc22bd69ec.png)
![image](https://user-images.githubusercontent.com/125673909/236008883-52c67def-b3dd-4713-9a73-906aa35e6ee3.png)


</div>

# E-Commerce

An E-Commerce website with the Laravel Framework

# Changelog

All notable changes to this project will be documented in this file.

```bash
# [1.0] - 24 avril 2023
- Creation of the Laravel project
- Deleted Eloquent ORM cause i hate ORM
- Added the Makefile

# [1.1] - 28 avril 2023
- Started the front end (Using https://bootstrapmade.com/)
- Updated DATABASE & TABLES 
- Implemented Login & Signup pages 
- Implemented the Laravel Request Validator (for signup & login) 
```

# Installation

```sql
CREATE DATABASE ecommerce;
use ecommerce;

CREATE TABLE users(
    `id` INT AUTO_INCREMENT,
    `mail` VARCHAR(50) UNIQUE NOT NULL,
    `pass` TEXT NOT NULL,
    `is_admin` SMALLINT DEFAULT 0,

    PRIMARY KEY(`id`)   
);
```
Then
```bash
sudo make db
sudo make serv
```


