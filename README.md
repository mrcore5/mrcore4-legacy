## mRcore Bridge Module for mRcore4 Legacy v5.7

This mrcore5 applications allow all mrcore4 apps to function by providing a legacy interface.

Use this with mrcore5 only if you are in an environment that needs to support mrcore 4 applications.


## What Is mRcore

mRcore is a module/package system for Laravel allowing you to build all your applications as reusable modules.
Modules resemble the Laravel folder structure and can be plugged into a single Laravel instance.
mRcore solves module loading dependency order and in-place live asset handling.  Modules can be
full web UIs, REST APIs and/or full Console command line apps.  A well built module is not only your
UI and API, but a shared PHP library, a native API or repository which can be reused as dependencies in other modules.

We firmly believe that all code should be built as modules and not in Laravel's directory structure itself.
Laravel simply becomes the "package server".  A single Laravel instance can host any number of modules.

See https://github.com/mrcore5/framework for details and installation instructions.


## Official Documentation

This package uses your Laravel `config/database.php` config file and is looking for
both a `sqlsrv` and `mysql` connection array.

This package uses SMTP mail settings from Laravel, so you will need to define those in your .env like so

    ### SMTP Required for mrcore4legacy ###
    MAIL_HOST=smtp.example.com
    MAIL_PORT=587
    MAIL_USERNAME=system@example.com
    MAIL_PASSWORD=password
    MAIL_FROM_ADDRESS=legacy@exanoke.com
    MAIL_FROM_NAME=Legacy


## Contributing

Thank you for considering contributing to the mRcore framework!  Fork and pull!

### License

mRcore is open source software licensed under the [MIT license](http://mreschke.com/license/mit)
