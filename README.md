# Laravel Nova Auditing

This package provides an implementation of [laravel auditing](http://laravel-auditing.com) for Nova

## Features
*   Display all audit resources for a specified resource.
*   Display an audit details, and specify where the changes happened.
*   Revert a model into a specified audit.

## Installation

```
composer require yassir3wad/nova-auditing
```

First of all, follow the [installation guide](http://laravel-auditing.com/docs/9.0/installation) of the parent package, then publish the config file

```
php artisan vendor:publish --provider="Yassir3wad\NovaAuditing\ToolServiceProvider" --tag=config
```

## Usage

1.  Add your auditable resources and user resources into the published config file `novaaudit.php`
```php
<?php

return [
    'auditable_resources' => [
        App\Nova\Post::class,
    ],
    'user_resources' => [
        App\Nova\User::class
    ]
];
```

2.  Add `Audits` `MorphMany` relation into your resource to list resource audits
```php
<?php
use Laravel\Nova\Fields\MorphMany;
use Yassir3wad\NovaAuditing\Resources\Audit;

class Post extends Resource{
    
     public function fields(Request $request)
     {
         return [
             ID::make()->sortable(),
 
            MorphMany::make('Audits', 'audits', Audit::class)
         ];
     }   
}
```