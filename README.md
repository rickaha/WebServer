# Reverse Proxy Setup with Docker

This repository sets up a reverse proxy to route traffic through two Nginx web servers using Docker Compose. The configuration includes persistent storage for server data and custom network settings.

## Features
- **Docker Compose**: Manages multiple services in a single YAML file.
- **Nginx Web Servers**: Configured for static content serving.
- **Reverse Proxy**: Routes HTTP requests efficiently across multiple servers.
- **Persistent Storage**: Uses Docker volumes for configuration and data persistence.

## Getting Started

### Prerequisites
- Docker installed on your system https://docs.docker.com/get-docker/
- Docker Compose installed https://docs.docker.com/compose/install

### Installation

Clone this repository:
```
git clone https://github.com/YOUR_USERNAME/docker-webserver.git
cd docker-webserver
```

### Running the Services

To start all services, navigate to the project directory and run:
```
docker-compose up -d
```

This command will create and start containers for webserver1, webserver2, and the reverse proxy
server within a custom network named proxy-net.

## Configuration Details

### Docker Compose File

The docker-compose.yml file sets up:
- Two Nginx web servers (webserver1, webserver2) with persistent storage volumes for
  configuration files.
- A reverse proxy server that routes requests to the correct web server based on hostname or path.

### Configuration Files

#### Nginx Web Server Configurations
The basic Nginx server block configurations are located in:
- webservers/webserver1/default.conf
- webservers/webserver2/default.conf

These files define listening ports, server names, root directories, and error handling pages for
serving static content over HTTP.

#### Reverse Proxy Configuration
Default Nginx configuration is added to handle basic HTTP requests. It serves
static content and includes server-specific proxy configurations:
- reverse-proxy/default.conf

### Persistent Storage

Volumes are used to persistently store configurations and data for the web servers and reverse
proxy, ensuring that changes survive container restart
