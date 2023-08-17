<div align="center">
  
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
  
    
![GitHub top language](https://img.shields.io/github/languages/top/NullBrunk/E-Commerce?style=for-the-badge)
![GitHub commit activity](https://img.shields.io/github/commit-activity/m/NullBrunk/E-Commerce?style=for-the-badge)
![repo size](https://img.shields.io/github/repo-size/NullBrunk/E-Commerce?style=for-the-badge)

![commerce](https://github.com/NullBrunk/E-Commerce/assets/125673909/eee9fecb-8e8a-4f66-a510-9eca6278f299)

</div>

# E-Commerce
An E-Commerce website with the Laravel Framework (Currently in dev)

The Web App is iserved on localhost:80, and the API is served on localhost:8000.


# Installation

```bash
git clone https://github.com/NullBrunk/E-Commerce
cd E-Commerce 
```

First of all you'll need to install MySQL or MariaDB, composer and MailDev/MailHog, then :

```bash
# Install composer productions dependencies
composer install --no-dev

# Install node_modules
npm install
```


Then, you can change the .env and put your SQL username, password, db_name host and port.
Start the mariadb/mysql service and run

Then start the MySQL service and run

```bash
php artisan migrate
```

Then you'll need to link the storage directory to public/storage/

```bash
php artisan storage:link
```

Finally, you can launch the artisan development server on web port, and launch the API

In one terminal type
```
sudo php artisan serve --port=80
```

In another type
```
php artisan server --port=8000
```


# Thanks

- Thanks to <a href="https://codepen.io/md-khokon">Md-khokon</a> for <a href="https://codepen.io/md-khokon/pen/bPLqzV">this amazing e-mail template</a>