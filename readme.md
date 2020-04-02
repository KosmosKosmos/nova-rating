# Laravel Nova Rateable Field Extension

## About
This package introduces a __Rateable Trait__ to your application's models on __Laravel Nova__.

This package introduces a __Rateable Trait__ to your application's models.

Also, it introduces an interactive __Field__ to your Nova backend that makes it possible to instantly rate any property you wish.

With this package it is possible to perform multiple category rating.
 
For example, you might want to rate the 

- Performance
- Price
- Quality

of a product.

However, you can also generate a field that returns the average rating over all three categories combined with the virtual attribute `averageRating`

## Config Settings

Run `php artisan vendor:publish --tag=config` to set default settings.
You will find them in `config/rating.php`.

You can also set a specific size and padding per field entry via calling the `starSize` and `padding` methods.

## Credits 

Thank you for doing the base work: 

https://github.com/willvincent/laravel-rateable
https://github.com/craigh411/vue-star-rating

#### Special credits

Thank you for inspiring us:

https://novapackages.com/packages/nikaia/nova-rating-field

_If you need some more documentation about possible options, this might be a good documentation resource._

## Installation

1. `composer require kosmoskosmos/nova-rateable-field`
2. `php artisan vendor:publish`
3. `php artisan migrate`

## Usage

```php
public function fields(Request $request)
{
    return [
        // ...
        // Define categories for to be rated.
        Rating::make('Pizza Baking Skills', 'pizza_rating')->hideFromIndex(),
        Rating::make('Sushi Rolling Skills',  'sushi_rating')->hideFromIndex(),
        Rating::make('Bread Baking Skills', 'bread_rating')->hideFromIndex(),
        // Show average rating from all three categories above.  
        Rating::make('Overall Skills', 'average_rating')->onlyOnIndex(), 
        // Show average rating from only one category above
        Rating::make('Overall Bread Baking Skills', 'bread_average_rating')->onlyOnIndex(),  
    ];
}
```

Extend your model like this: 
```php
class MyModel extends Model {
    // use the Rateable trait 
    use \KosmosKosmos\Rating\Rateable;
    
    // use the getAttribute() function to inject the Trait's attribute-resolver
    public function getAttribute($key) {
        if ($result = $this->resolveRatingAttribute($key)) {
            return $result;
        }
        return parent::getAttribute($key);
    }   

}

``` 

### Attribute Naming Conventions

To make best use of this plugin we advise to use the suffix `_rating` or `Rating` for your rateable fields.
