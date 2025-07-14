# Docker Multi-Server Setup

This repository contains the configuration files for setting up multiple web servers using Docker containers. It includes configurations for HTTPS reverse-proxying with SSL
certificates managed by Certbot.

## Overview

The setup includes:
- Web servers
- A reverse-proxy server to handle incoming requests and manage SSL certificates with Certbot

## Getting Started

### Prerequisites

- Docker installed on your machine.
- Docker Compose installed on your machine.

### Installation

1. Clone the repository:
```
   git clone https://github.com/rickaha/WebServer.git && cd WebServer
```

2. Build and start the containers using Docker Compose:
```
   sudo docker compose up -d
```

3. Run Certbot to obtain SSL certificates (do this for every website):
```
   sudo docker compose run --rm  certbot certonly --webroot --webroot-path /var/www/certbot/ -d example.com -d www.example.com
```
- The http configuration files in reverse-proxy/sites-enabled/ can now be removed and instead copy the https files from sites-available to sites-enabled.

- Change WP_HOME and WP_SITEURL from http to https.

4. Create cron job so Certbot try to reneiw SSL certificates once a week:
```
   sudo crontab -e
```
Add the following to the end of the file:

```
   0 1 * * sun docker compose -f /home/<your_username>/WebServer/docker-compose.yml run --rm certbot renew > /home/<your_username>/WebServer/log.txt
   30 1 * * sun docker compose -f /home/<your_username>/WebServer/docker-compose.yml restart reverse-proxy
```

## Docker Compose Files

- docker-compose.yml: configuration file defining all services.

## Reverse Proxy Configuration

- The reverse proxy is configured to handle requests for multiple domains. 

- The SSL certificates are managed by Certbot and need to be renewed every 3 months.

- To activate sites, place configuration files in reverse-proxy/sites-enabled/

## Websites

- Place your website content in the respective service directory under public-html/.

- Place .env file in services/service1/:

``` dotenv
   PUID=****
   GUID=****
   UMASK=002
   TZ=Etc/UTC

```

- Place .env file in services/service2/:

``` dotenv
   MYSQL_ROOT_PASSWORD=**** 
   MYSQL_USER=****
   MYSQL_PASSWORD=****
   MYSQL_DB=wordpress
   WP_HOME=http://website.com
   WP_SITEURL=http://website.com

```
- Place config.php file in php-fpm/: 

``` php
   <?php
   return [
       'smtp_password' => '****',
       'smtp_username' => '****',
       'smtp_server' => '****',
       'mail_recipient' => '****'
   ];
   ?>
```

## Permissions

- On the host, set the permissions for nginx like:

```
   sudo adduser --system --no-create-home --group nginx
   sudo chown -R nginx:nginx services/service1/default.conf
   sudo chown -R nginx:nginx services/service1/public-html
   id nginx
```
- Now add the correct PUID/PGID to the .env file in services/service1/.
