## Mrcore4 to Mrcore5 Bridge

This mrcore5 applications allow all mrcore4 apps to function by providing a legacy interface.

Use this with mrcore5 only if you are in an environment that needs to support mrcore 4 applications.

## What Is Mrcore

Mrcore is a set of Laravel and Lumen components used to build various systems.
It is a framework, a development platform and a CMS.  It is a modularized version of Laravel
providing better package development support.  Think of Laravel 4.x workbenches on steroids.

See https://github.com/mrcore5/framework for details and installation instructions.

## Official Documentation

This package uses your Laravel `config/database.php` file, so there is no special configuration for database.

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

Mrcore is open-sourced software licensed under the [MIT license](http://mreschke.com/license/mit)
