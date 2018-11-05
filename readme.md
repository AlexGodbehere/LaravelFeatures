# LaravelFeatures


**Laravel Features** is a package for Laravel 5.7+ that allows you to easily manange and toggle application features. Its primary purpose is to provide a facade to check if a feature is accessible by free or premium users, however it also supports the enabling and disabling of features.


## Installation

Install via Composer:

```
composer require alexgodbehere/laravelfeatures
```

Then, add an `isPremium` bool to your user model. This will determine if the user can use a premium feature.
## Feature Management

### Adding a Feature

To add a feature to your application, use the following. **Features are free by default.** A feature can be created as a premium feature by using the `-P` or `--premium` flag.

```
php artisan feature:add <name> --premium
```

By default, features are live and can be used straight away. If you would like to prevent the feature from being used until explicitly enabled, use the `-D` or `--dev` flag.

```
php artisan feature:add <name> --dev
```

If you are intending to programatically seed your database, you can supply the description as the second argument to prevent the description prompt from appearing.

```
php artisan feature:add <name> <description>
```

### Removing a Feature

To remove a feature from your application, use the following.

```
php artisan feature:remove <name>
```

### Enabling a Feature

If the feature was created with the `--dev` flag, it can be enabled with:
```
php artisan feature:enable <name>
```

Similarly, a feature can be disabled with:
```
php artisan feature:disable <name>
```

### Setting a Feature as Premium

Features can be toggled between free and premium with the `free` and `premium` commands:

```
php artisan feature:free <name>
```

```
php artisan feature:premium <name>
```

### Listing all Features

To view all features in your application, use the `list` command.
```
php artisan feature:list
```
```
php artisan feature:list --premium
```

```
php artisan feature:list --free
```

### Viewing Feature Usage Counters

To view how many times a feature has been accessed, use the `count` command.

```
php artisan feature:count <name>
```

### Resetting Feature Usage Counters

To reset a features usage counter, use the `reset` command.

```
php artisan feature:reset <name>
```

You can also reset counters for all features with the `resetall` command.

```
php artisan feature:resetall
```

## Usage

To check if the currently authenticated user is able to use a feature, simply use the `Feature::can()` facade.

```
if (Feature::can('FeatureName') {
    // Code here
}
```

*Note: the `can()` function takes an optional second argument `isPremium` to force the check to pass. This is used for testing only and is disabled for production environments.*

Alternatively, check if the user isn't able to use a feature by using the `Feature::cannot()` facade.
```
if (Feature::cannot('FeatureName') {
    // Code here
}
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

```
vendor/bin/phpunit
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email alex.godbehere@gmail.com instead of using the issue tracker.

## Credits

- [Alex Godbehere][link-author]
- [All Contributors][link-contributors]

## License

GPL-3.0. Please see the [license file](license.md) for more information.
