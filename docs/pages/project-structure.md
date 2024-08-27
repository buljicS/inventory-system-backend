[< Go Back](../README.md)

# Structuring SlimPHP project

```
root/
├── app/
│   ├── config/
│   └── middlewares/
├── bin/
├── docs/
├── public/
│   ├── .htaccess
│   ├── favicon.ico
│   ├── swagger/
│   └── index.php
├── src/
│   ├── Controllers/
│   ├── Interfaces/
│   ├── Models/
│   ├── Providers/
│   ├── Services/
│   └── Repositories/
│   └── Utilities/
├── templates/
├── tests/
├── vendor/
├── .htaccess
├── .env
├── .env.example
├── README.md
├── .gitignore
└── composer.json
```

# Breakdown
- `app/` serves as dependency storage, all routes, settings, middlewares and such are stored in php files in this folder
- `bin/` stores cli scripts (bash / php)
- `docs/` stores documentation for project
- `public/` all files in this folder are publicly visible on server
- `src/` all code (logic, functions, methods, classes and rest) lives inside of this folder
- `templates/` also called `views` dir is responsible for holding all html pages
- `tests/` PHPUnit tests are stored here
- `vendor/` all packages are stored in this folder (composer generated)
- `.htaccess` global apache config file
- `.env` environment variables are stored here
- `.env.example` template for creating env file 
- `README.md` git generated markdown file
- `.gitignore` VCS file for excluding folders and files from git
- `copomoser.json` composer source file (created or composer generated)

> Note: This is usually the way of structuring SlimPHP app, but it's also similar to much larger frameworks like Symphony

# `src/` Overview
- Inside `src` dir lives all of your php code (classes, functions, methods, etc)
- `src/` dir should not be publicly available on your server
<hr>

[< Go Back](../README.md)