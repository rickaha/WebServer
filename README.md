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
    git clone https://github.com/rickaha/WebServer.git
    cd WebServer
```
- The https configuration files in reverse-proxy/conf.d/ need to be marked .disabled

2. Build and start the containers using Docker Compose:
```
    sudo docker compose up -d
```

3. Run Certbot to obtain SSL certificates (do this for every website):
```
    sudo docker compose run --rm  certbot certonly --webroot --webroot-path /var/www/certbot/ -d example1.se -d www.example1.se
```
- The http configuration files in reverse-proxy/conf.d/ can now be .disabled and instead use the https files.

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

- The SSL certificates are managed by Certbot and need to be reneiwed every 3 months. 

- The configuration files in reverse-proxy/conf.d/ need to be marked .disabled to not be included:

```
    mv mysite.com.conf mysite.com.conf.disabled
```

## Websites

- Place your website content in the respective directories under sites/.
