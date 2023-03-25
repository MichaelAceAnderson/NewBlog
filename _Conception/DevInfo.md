# NewBlog

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
- Cybersecurity (prepared requests, password encryption, etc...) ⚠ Needs penetration testing
- Project management (Git versioning, UML, documentation, etc...)
- Testing (unit, functional, integration, performance etc...)
- Deployment & DevOps (Web server, DB server, CI/CD, Docker, etc...)

## Table of contents

- [NewBlog](#newblog)
  - [Table of contents](#table-of-contents)
  - [Upcoming versions](#upcoming-versions)
    - [Version 2.1 - Developers update](#version-21---developers-update)
    - [Version 2.0 - Rewriting from scratch](#version-20---rewriting-from-scratch)
  - [Changelog (global changes)](#changelog-global-changes)
    - [Version 1.1 - MVC Update](#version-11---mvc-update)
    - [Version 1.0 - First release](#version-10---first-release)
  - [Documentation](#documentation)
    - [How a request is handled](#how-a-request-is-handled)
    - [To-do (specific changes) -\> 2.0](#to-do-specific-changes---20)
    - [Conception questions](#conception-questions)

Information for developers

## Upcoming versions

### Version 2.1 - Developers update

- [ ] Switch to Nginx & PHP-FPM (WinNMP ?)
- [ ] HTTPS certificate
- [ ] Make server compatible with Linux
- [ ] Automatically separate environment between development and production
- [ ] Dockerize application
- [ ] Use Namespaces for PHP classes
- [ ] DB roles separation OR single (persistent) PDO connection
- [ ] Add conception/UML diagrams
- [ ] Add Model Unit Tests
- [ ] Add Controller Unit Tests
- [ ] Cookie storage for session variables
- [ ] Apply DICP principle

### Version 2.0 - Rewriting from scratch

- [X] Refactorization to Model-View-Controller
- [X] Separate Model into entities
- [X] Separate Model into entities
- [X] New design
- [X] Add Error logging
- [X] Add password hashing
- [X] Add page redirection (protection against direct access to files)

## Changelog (global changes)

### Version 1.1 - MVC Update

- [x] Converted structure to Model-View-Controller
- [x] Fixed errors due to obsolete URLs
- [x] Various bug fixes

### Version 1.0 - First release

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

### To-do (specific changes) -> 2.0

- [ ] Destroy SESSION variables when blog is reinstalled (back)
- [ ] Don't use SESSION variables when blog is not installed (back)
- [ ] Turn all static methods into custom object methods (back)
- [ ] Add inheritance for controllers and models (back)
- [ ] Separate SESSION variables into array (back)
- [ ] Remove useless DB connection if blog is not installed (back)
- [ ] Add missing methods to controller & model (back)
- [ ] Make separate logs for view/controller/model (back)
- [ ] Try to find a way to make functions out of the code in each model method (back)
- [ ] Fix links and headers style (front)
- [ ] Improve error handling to handle all cases and display them properly (front/back)
- [ ] Update design for posts (front)
- [ ] Add media posting \[video/image\] (front/back)

### Conception questions

- Should I ask for the user to create the database or should I create it automatically ?
- Should I use a single global PDO connection or separate connections for each entity ?
- Should I separate CSS classes in a reusable component logic or in a specific context logic ?
