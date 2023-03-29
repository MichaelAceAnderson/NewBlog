# NewBlog

Information for developers
NewBlog is a CMS (Content Managing system) written in PHP allowing you to post text/image content on your own blog.
It was written to learn PHP and web development in general.
The real interest of this project was to synthetize all the knowledge I had acquired in the past two years
into a single project. It is not yet finished but it aimes to use:

- Basic web languages (HTML, PHP, CSS, JS)
- SEO (indexation, meta tags, etc...)
- Responsive design
- Object Oriented Programming (encapsulation, inheritance, etc...)
- MVC web architecture
- Database management (procedures, views, triggers)
- Cybersecurity (prepared requests, password encryption, etc...) ⚠️ Needs penetration testing
- Project management (Git versioning, UML, documentation, etc...)
- Testing (unit, functional, integration, performance etc...)
- Deployment & DevOps (Web server, DB server, CI/CD, Docker, etc...)

## Table of contents

- [NewBlog](#newblog)
  - [Table of contents](#table-of-contents)
  - [Getting started](#getting-started)
  - [To do (upcoming versions)](#to-do-upcoming-versions)
    - [Version 2.1 - Developers update](#version-21---developers-update)
    - [Version 2.0 - Rewriting from scratch](#version-20---rewriting-from-scratch)
  - [Changelog (global changes)](#changelog-global-changes)
    - [Version 1.1 - MVC Update](#version-11---mvc-update)
    - [Version 1.0 - First release](#version-10---first-release)
  - [Documentation](#documentation)
    - [How a request is handled](#how-a-request-is-handled)
    - [How a page is structured](#how-a-page-is-structured)
    - [How the database is structured](#how-the-database-is-structured)
    - [How the app is structured](#how-the-app-is-structured)
      - [Model](#model)
    - [Controller](#controller)
      - [View](#view)

## Getting started

Required:

- XAMPP (Apache/PHP)
- PostGreSQL database
- VSCode (or any other IDE)
- A web browser (NewBlog was only tested on Edge)

Step 1: Clone the repository (with the required rights)

```bash
git clone https://github.com/MichaelAceAnderson/NewBlog.git 
```

Step 2: Start the Apache server and the PostGreSQL database

Step 3: Make sure you have a "newblog" database with a "postgres" user and password "PG770rwx"

Step 4: Open the project in your IDE

Step 5: Open [the project](http://localhost/) in your browser

Step 6: Install the blog

Step 7: Use the account page (click on the username in the top right corner) to change credentials/post content

Step 8: Use the admin page to manage the blog settings

## To do (upcoming versions)

### Version 2.1 - Developers update

 ![2.1](https://img.shields.io/badge/2.1-yellow)

General changes

- [ ] Switch to Nginx & PHP-FPM (WinNMP ?) (back)
- [ ] HTTPS certificate (back)
- [ ] Make server compatible with Linux (back)
- [ ] Automatically separate environment between development and production (back)
- [ ] Dockerize application (back)
- [ ] Add Model Unit Tests (back)
- [ ] Add Controller Unit Tests (back)
- [ ] Cookie storage for session variables (back)
- [ ] Apply DICP principles to the code (back)
- [ ] Make theme colors & fonts user-customizable (front/back)

Specific changes

- [ ] Use Namespaces for PHP classes (back)
- [ ] Turn all static methods into custom object methods (back)
- [ ] Add inheritance for controllers and models (back)
- [ ] Separate SESSION variables into array (back)
- [ ] Fix links and headers style (front)
- [ ] Create the database automatically (back)
- [ ] Add a "remember me" option for login (front/back)
- [ ] Use a single PDO Connection for all operations ?

### Version 2.0 - Rewriting from scratch

 ![2.0](https://img.shields.io/badge/2.0-blue)

- [X] Refactorization to Model-View-Controller
- [X] Separate Model into entities
- [X] New design
- [X] Add Error logging
- [X] Add password hashing
- [X] Add page redirection (protection against direct access to files)
- [X] Add conception/UML diagrams

To-do:

- [ ] Destroy SESSION variables when blog is reinstalled (back)
- [ ] Don't use SESSION variables when blog is not installed (back)
- [ ] Remove useless DB connection if blog is not installed (back)
- [ ] Make separate logs for view/controller/model (back)
- [ ] Try to find a way to make functions out of the code in each model method (back)
- [ ] Improve error handling to handle all cases and display them properly (front/back)
- [ ] Update design for posts (front)
- [ ] Restore logo changing feature (front/back)
- [ ] Make CSS rules reusable (front)

## Changelog (global changes)

### Version 1.1 - MVC Update

 ![1.1](https://img.shields.io/badge/1.1-default)

- [x] Converted structure to Model-View-Controller
- [x] Fixed errors due to obsolete URLs
- [x] Various bug fixes

### Version 1.0 - First release

 ![1.0](https://img.shields.io/badge/1.0-default)
Features:

- [x] Text posts
- [x] Image posts
- [x] Admin panel
- [x] Choose logo
- [x] Choose background
- [x] Reset posts
- [x] Change blog title
- [x] Change blog description
- [x] Change login & password

## Documentation

### How a request is handled

Form (View) -> POST request -> GET request (Controller) -> Call Model method -> Return array/error (Model) -> Return array/false -> Display result/error (View)

### How a page is structured

![Page structure & includes](PageStructure.png)

### How the database is structured

![Database structure](DBStructure.png)

### How the app is structured

#### Model

model.php is only used to store generic functions related to PDO connection and error handling.

```php
class Model{
    /* CONSTRUCTOR */ // (returns Exception on database connection failure)
    /* PROPERTIES */ // (PDO connection, statement to execute, etc...)
    /* METHODS */ // (accessors and error logging functions)

    // Include other models
}
```

Every other model is yet to become a child of the Model class and is used to store functions related to a specific table.

```php
class SpecificModel{
    /* METHODS */ // (return arrays or Exceptions)
}
```

### Controller

controller.php is only used to store generic functions related to redirection and error displaying.

```php
class Controller{
    // Include model

    /* CONSTRUCTOR */ // (returns Exception on database connection failure)
    /* METHODS */ // (data conversion [HTTP/JSON], used for future API)

    // Include other controllers
}
```

Every other controller is yet to become a child of the Controller class and is used to store functions handling requests between the view and a specific model.

```php
class SpecificController{
    /* METHODS */ // (return booleans adapted to the context or Exceptions on Model-related errors)
}
```

#### View

index.php is the main page and handles every user interaction with the blog.
It includes the common structure and the content specific to the page requested (via GET)

```php
// Include head
// Include header
// Redirect user to the install page if the blog is not installed (or corrupted)
// Include content
// Include footer
```
