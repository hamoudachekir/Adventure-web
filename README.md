# Adventure Platform Web
![adventure](https://github.com/mannai-omar/adventure-desktop/assets/73422595/5bbfa3dc-00c8-4bab-b983-012988486ebc)
Welcome to the Adventure Platform Web repository! Adventure Platform Web is a multi-platform application aimed at outdoor enthusiasts, providing a user-friendly interface to discover and book outdoor activities.

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Getting Started](#getting-started)
- [Demos](#demos)

## Introduction

Adventure Platform Web is part of the Adventure Platform project, which aims to simplify the process of finding and booking outdoor activities. This desktop application allows users to browse available activities, manage reservations, and interact with the Adventure Platform ecosystem.

## Features

- Browse and search for outdoor activities.
- View activity details, including descriptions, images, and user ratings.
- Make reservations for activities.
- Manage reservations and view booking history.
- Interact with user comments and ratings for activities.
- Receive email confirmation for reservations.
- Upon reservation confirmation, receive a second email with a QR code containing reservation details for easy access.

## Technologies Used

Adventure Platform Desktop is built using the following technologies:

- Symfony 5.4
- MySQL
- Twig
- Git

## Getting Started

To get started with Adventure Platform Desktop, follow these steps:

1. Clone this repository to your local machine.
2. Install [PHP](https://www.php.net/downloads) and [Composer](https://getcomposer.org/download/) if not already installed.
3. Install [Symfony CLI](https://symfony.com/download) for managing Symfony applications.
4. Set up [XAMPP](https://www.apachefriends.org/index.html). for MySQL server and import the provided schema.
5. Import [adventure.sql](https://github.com/mannai-omar/adventure-desktop/blob/main/src/adventure.sql) to import the database.
6. Update database configuration in the `.env` file with your MySQL credentials.
7. Run `composer install` to install dependencies.
8. Run `symfony serve` to start the Symfony web server.


## Demos

### Front-end
<img src="https://github.com/mannai-omar/adventure-web/assets/73422595/a0bfcf93-44d6-43c1-944e-52cd52f69e55" alt="Front-end Demo" width="700">

### Dashboard
<img src="https://github.com/mannai-omar/adventure-web/assets/73422595/108f4272-175f-4c22-85ee-e55733e06299" alt="Dashboard Demo" width="700">


