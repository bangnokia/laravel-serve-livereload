![](https://banners.beyondco.de/Laravel%20serve%20livereload.png?theme=light&packageManager=composer+require&packageName=bangnokia%2Flaravel-serve-livereload+--dev&pattern=architect&style=style_1&description=This+is+why+it%27s+awesome&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

# Laravel Serve LiveReload

This package integrates into default `artisan serve` command an WebSockets server for live reloading the application when any file changed.

![Laravel serve livereload](demo.gif)

## Installation

For laravel 8, please use version ^1.x, and below use version 0.x

`composer require bangnokia/laravel-serve-livereload --dev`



## Usage

Open terminal and run `php artisan serve`

This package works even when you use custom vhost such as `valet` or `laragon`

## Configuration

By default, this package looking for files changes in these directories:

```
/app
/public 
/config 
/routes 
/resources
```

If you want to customize the watched forlders, you can publish the configuration file by this commmand:

```bash
php artisan vendor:publish --provider="BangNokia\ServeLiveReload\CommandServiceProvider"
```

and then you can config what you want in the `config/serve_livereload.php`.

