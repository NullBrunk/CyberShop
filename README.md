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




https://github.com/NullBrunk/E-Commerce/assets/125673909/bb256fa4-6ef4-47b1-a745-e0b5a1dc62ae


https://github.com/NullBrunk/E-Commerce/assets/125673909/a15e4a32-3035-49fa-99bc-f834218a315c



https://github.com/NullBrunk/E-Commerce/assets/125673909/7ed51d3a-2cf4-4c0e-b333-465cd6b7f975



https://github.com/NullBrunk/E-Commerce/assets/125673909/0465e9bc-2540-4ce4-a304-d05e39500112




https://github.com/NullBrunk/E-Commerce/assets/125673909/4c6b2c51-15af-4138-8fd4-639f08370a90






https://github.com/NullBrunk/E-Commerce/assets/125673909/75af32a3-3840-4cac-a018-9f6a3c27a972





# Installation

```bash
git clone https://github.com/NullBrunk/E-Commerce
cd E-Commerce 
```

First of all you'll need to install MySQL or MariaDB, composer, npm and MailDev or MailHog, then :

```bash
# Install composer productions dependencies
composer install --no-dev

php artisan migrate

php artisan storage:link
```

In one terminal type
```
sudo php artisan serve --port=80
```
In another type
```
php artisan server --port=8000
```

### Quick note : 
We have some services that are real-time on this Website. But since i dont want to bloat my tech-stack, i choosed to use https://pusher.com/ instead of a Redis/NodeJS websocket approach. The .env is actually filled with my PUSHER config, but since the free plan is limited, you can go signup and use the premium plan if you want, or use my prebuilt config.  


# Thanks

- Thanks to <a href="https://codepen.io/md-khokon">Md-khokon</a> for <a href="https://codepen.io/md-khokon/pen/bPLqzV">this amazing e-mail template</a>
