# Developer Changelog

- [Developer Changelog](#developer-changelog)
	- [To do (upcoming versions)](#to-do-upcoming-versions)
		- [Version 3.3 - Security update](#version-33---security-update)
		- [Version 3.2 - Interaction update](#version-32---interaction-update)
		- [Version 3.1 - International update](#version-31---international-update)
		- [Version 3.0 - API update](#version-30---api-update)
		- [Version 2.1 - Developers update](#version-21---developers-update)
	- [Developer Changelog](#developer-changelog-1)
		- [Version 2.0 - Rewriting from scratch](#version-20---rewriting-from-scratch)
		- [Version 1.1 - MVC Update](#version-11---mvc-update)
		- [Version 1.0 - First release](#version-10---first-release)

## To do (upcoming versions)

### Version 3.3 - Security update

 ![3.3](https://img.shields.io/badge/3.3-yellow?style=flat-square)

- [ ] Penetration testing
- [ ] Add CSRF protection
- [ ] Add XSS protection (stored or reflected -> Escape variables)
- [ ] Check SQL injections & add automatic tests
- [ ] Add password strength checker
- [ ] Add password reset system
- [ ] Add 2 factors authentication
- [ ] Add captcha
- [ ] Add HTTPS certificate
- [ ] Add cookie consent banner
- [ ] Add GDPR/RGPD compliance:
  - [ ] Add privacy policy
  - [ ] Add terms of use
  - [ ] Add cookie policy
  - [ ] Add cookie consent banner

### Version 3.2 - Interaction update

 ![3.2](https://img.shields.io/badge/3.2-yellow?style=flat-square)

- [ ] Add advanced post editor with markdown support (front/back)
- [ ] Add possibility to create multiple accounts on a same blog (front/back)
- [ ] Add comments system (front/back)

### Version 3.1 - International update

 ![3.1](https://img.shields.io/badge/3.1-yellow?style=flat-square)

- [ ] Add language detection/selection system with constants & language files
- [ ] Convert code comments to English

### Version 3.0 - API update

 ![3.0](https://img.shields.io/badge/3.0-yellow?style=flat-square)

- [ ] Add API for external applications
- [ ] Add JS live updates
- [ ] Add JS/PHP input validation (uploaded files types, e-mails RegEx, URLs etc...)

### Version 2.1 - Developers update

 ![2.1](https://img.shields.io/badge/2.1-yellow?style=flat-square)

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
- [ ] Use Namespaces for PHP classes (back)
- [ ] Turn all static methods into custom object methods (back)
- [ ] Add inheritance for controllers and models (back)
- [ ] Separate SESSION variables into array (back)
- [ ] Create the database automatically and configure newblog db user in json/ini file (back)
- [ ] Add a "remember me" option for login (front/back)
- [ ] Try to make a model method for uploading files (back)
- [ ] Upload progress bar (front/back)
- [ ] Try to cache blog info to prevent useless DB requests (back)
- [ ] Add constants & global variables for re-used values \[ex: paths, links, ...\] (back)
- [ ] Make app portable by replacing absolute paths (DOCUMENT_ROOT) with relative paths (\_\_DIR\_\_) (back)
- [ ] Display missing file authorizations errors (back/front)
- [ ] Prevent access to development files with .htaccess (back)
- [ ] Replace .gitkeep by mkdir in PrintLog method (back)
- [ ] Add password confirmation in installation page (front/back)
- [ ] Add Update system to avoid reinstalling the blog (front/back)
- [ ] Add possibility to delete post if the user is the author (front/back)
- [ ] Add a table for tags and a table to link tags with posts (back)
- [ ] Add possibility to filter posts by tag
- [ ] Make tags appear in head meta for indexation (back)
- [ ] Add missing variable precisions in log (back)
- [ ] Improve isDbInstalled method (back)
- [ ] Add mediaUrl to posts & documentation (back)
- [ ] Add custom Exceptions with specific codes [using constants ?] (back)
- [ ] Refactor code with naming conventions & message standards (back)
- [ ] Reformat indentation in debug page (back)
- [ ] Add ValueError | Error catch after password hashing (back)
- [ ] Add section comments in every Model & Controller (back)
- [ ] Check code validity (back)
- [ ] Improve error message logging [see Convergence project] (back)
- [ ] Add user action logs in Controller (back)
- [ ] Remove default values in SQL table creation -> Pass them to the Model (back)
- [ ] Try PHP_CodeSniffer

## Developer Changelog

### Version 2.0 - Rewriting from scratch

 ![2.0](https://img.shields.io/badge/2.0-green?style=flat-square)

- [X] Refactorized to Model-View-Controller
- [X] Separated Model into entities
- [X] New design
- [X] Added Error logging
- [X] Added password hashing
- [X] Added page redirection (protection against direct access to files)
- [X] Added conception/UML diagrams

### Version 1.1 - MVC Update

 ![1.1](https://img.shields.io/badge/1.1-brightgreen?style=flat-square)

- [x] Converted structure to Model-View-Controller
- [x] Fixed errors due to obsolete URLs
- [x] Various bug fixes

### Version 1.0 - First release

 ![1.0](https://img.shields.io/badge/1.0-brightgreen?style=flat-square)
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
