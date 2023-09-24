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

# General overview
![all](https://github.com/NullBrunk/E-Commerce/assets/125673909/42d3eaba-36a1-4689-83e3-90540e955ce3)

# Signup / Login 
We use Livewire for dynamic validation
![signuplogin](https://github.com/NullBrunk/E-Commerce/assets/125673909/cfe5bbd4-ac16-4f57-8dd8-dccc6a2a9957)

# Reset password
![res_password](https://github.com/NullBrunk/E-Commerce/assets/125673909/2022cb86-1bd0-49ae-8178-45fb423096b9)

# Products
![prodyct](https://github.com/NullBrunk/E-Commerce/assets/125673909/b3de9926-31aa-41db-8b5f-8152486018a8)


# Comments

We can post, delete, update or like a comment.
![comments](https://github.com/NullBrunk/E-Commerce/assets/125673909/14fd8f03-5567-4308-b5b3-6170c18d1a31)

# Profile
![profile](https://github.com/NullBrunk/E-Commerce/assets/125673909/cd3fd157-a4c7-4d61-9639-8f1dfa1362ba)

# Chatbox 

With HTMX and JS we can easely edit/delete a chat message without having to reload the page
![chatbox](https://github.com/NullBrunk/E-Commerce/assets/125673909/b94fce1a-231f-4de3-9a57-8b9888dee732)

# Notifications 
Notifications are dynamicely updated. We use a the Pusher websocket & Livewire events.
![dynamic notifs](https://github.com/NullBrunk/E-Commerce/assets/125673909/622be458-7692-4fac-b801-e4f9b7b47325)

# Payment
Payment handled by stripe.
![payment](https://github.com/NullBrunk/E-Commerce/assets/125673909/441b4a75-84b0-4439-aa35-c752f85bfb17)


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
