# NewBlog

- [NewBlog](#newblog)
  - [Upcoming versions](#upcoming-versions)
    - [Version 2.1 - Developers update](#version-21---developers-update)
    - [Version 2.0 - Rewriting from scratch](#version-20---rewriting-from-scratch)
  - [Changelog](#changelog)
    - [Version 1.1 - MVC Update](#version-11---mvc-update)
    - [Version 1.0 - First release](#version-10---first-release)
  - [Documentation](#documentation)
    - [How a request is handled](#how-a-request-is-handled)
    - [To-do](#to-do)
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
- [X] New design
- [X] Add Error logging
- [X] Add password hashing
- [X] Add page redirection (protection against direct access to files)

## Changelog

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

### To-do

- [ ] Remove useless DB connection if blog is not installed (back)
- [ ] Add admin panel (front)
- [ ] Add missing methods to controller & model (back)
- [ ] Improve error handling and displaying (front/back)
- [ ] Pass error logging to model (back)
- [ ] Try to find a way to make functions out of the code in each model method (back)
- [ ] Fix links and headers style (front)
- [ ] Error message when wrong email & wrong password (front/back)

### Conception questions

- Should I ask for the user to create the database or should I create it automatically ?
- Should I use a single global DO connection or separate connections for each entity ?
- Should I separate CSS classes in a component logic or in a specific context logic ?
