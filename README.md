![image](https://dl.dropboxusercontent.com/s/em253abjckwr6k4/Virtuoso.png)

#Laravel Virtuoso

####Laravel Composable View Composers Package

Increase flexibility and reduce code duplication by easily composing complex View Composers from simple 
component Composers without unnecessary indirection or boilerplate code.

## Background

In many of our projects, the same data is often repeated on multiple pages. This presents the challenge 
of preparing this data in our Controllers and providing it to our various Views without an undue amount 
of code repetition. Laravel provides us with the ability to limit this potential repetition through an 
abstraction known as the View Composer. A View Composer allows you to abstract this code to a single 
location and make it available to multiple Views. A View Composer is simply a piece of code which is 
bound to a View and executed whenever that View is requested.

An example View Composer from the Laravel documentation:

``` php
View::composer('profile', function($view)
{
	$view->with('count', User::count());
}
```

A View Composer may also be created as a Class:

``` php
<?php namespace My\Project\Name\Space;

class UserCountComposer
{

	public function compose($view)
	{
		$view->with('count', User::count());
	}

}
```

Of course, when a View Composer is created as a Class, the association between the View Composer and the 
View must be registered, either using the following syntax:

``` php
View::composer('profile', 'UserCountComposer');
```
or via a Service Provider:

``` php
<?php namespace My\Project\Name\Space;
 
use Illuminate\Support\ServiceProvider;
 
class ComposerServiceProvider 
	extends ServiceProvider 
{
 
	public function register()
  	{
    	$this->app['view']->composer('profile', 'My\Project\Name\Space\UserCountComposer');
  	}
 
}
```

Data provided to the View by a View Composer may be accessed as if it had been provided by the Controller:

``` php
<ul>
	@foreach ($data as $datum)
		<li>{{ $datum }}</li>
	@endforeach
</ul>
```

#### Additional Resources

* View Composers in the [Laravel Documentation](http://laravel.com/docs/responses#view-composers).
* View Composers at [Laracasts](https://laracasts.com/lessons/view-composers).

## Application

Unfortunately, the out-of-the-box functionality of Laravel View Composers can be somewhat cumbersome.  

If we choose to go with the ```View::composer()``` format, our bootstrap files will quickly become overblown
and unwieldy.  On the other hand, if we choose a Class based approach, in addition to creating the View Composer Classes,
we need to register our Composer/View associations.  We might choose to create a Service Provider to register 
each of our Composer/View associations, resulting in repetitive boilerplate code as our Service Providers proliferate.  
Alternately, we might choose to create a single Service Provider to register all of our Composer/View associations, 
but this merely simplifies our bootstrap files at the expense of an unwieldy Service Provider.  Perhaps the best choice 
is to create a Service Provider for each View and within it register its View Composer associations, but this dramatically 
increases the indirection which already exists with View Composers.  Quite simply, keeping View Composers separate from their
registrations seems a wrongheaded approach.

This is the problem that Virtuoso is intended to solve.  Virtuoso allows you to easily create simple, single-focused 
View Composers for your data and leverage composition when providing data to your Views by associating one or more View
Composers with a single View via a "Composite Composer" as needed without any unnecessary indirection or repetitive 
boilerplate code - all of your Composer/View associations can be found in a single location and all without writing any
new Service Providers!

## Requirements

Virtuoso supports the following versions of PHP:

* PHP 5.4.\*
* PHP 5.5.\*
* PHP 5.6.\*
* HHVM

and the following versions of Laravel:
 
* Laravel 4.1.\*
* Laravel 4.2.\*

## Installation

First, install Virtuoso through Composer (Virtuoso on [Packagist](https://packagist.org/packages/coderabbi/virtuoso)), 
either by adding it to the Require Array of your `composer.json`:

```js
"require": {
    "coderabbi/virtuoso": "0.*"
}
```

or from the Command Line: 

``` bash
php composer.phar require coderabbi/virtuoso:0.*
```

Next, update `app/config/app.php` to include a reference to this package's Service Provider in the 
Providers Array:

``` php
'providers' => array(
    'Coderabbi\Virtuoso\ComposerServiceProvider'
)
```

Finally, update `app\config\view.php` to include the Composers Array:

``` php
'composers' => array (
)
```

## Usage

#### Simple View Composers

First, create your View Composer as you normally would (make sure to implement the Composer Interface):

``` php
<?php namespace My\Project\Name\Space;

use Coderabbi\Virtuoso\Composer;

class MyFirstSimpleComposer
	implements Composer
{

	public function compose($view)
	{
		$view->with('myData', $this->getMyData());
	}
	
	private function getMyData()
	{
		// do your thing here
	}
	
}
```

> Ideally, you should limit the data provided by each Composer to it's simplest, most cohesive unit. This
> will allow you to compose Composite View Composers for your Views more easily.

Next, associate the View (full path within the View Directory specified in `app/config/view.php` 
using dot notation) with your Simple View Composer (fully qualified Class Name including Namespace) 
by adding it to the Composers Array in `app\config\view.php`:

``` php
'composers' => array (
	'partials.header' => 'My\Project\Name\Space\MyFirstSimpleComposer',
)
```

That's it!  Virtuoso will take care of registering the View/Composer associations for you - no Service Provider
required!

You may access data provided by the Simple View Composer from the View as you normally would.

#### Composite View Composers

First, create your component View Composers as above (but do not associate them with your View in the 
Composer Array in `app\config\view.php`).

Next, create your Composite View Composer, adding the component View Composers to the Composers Array of
the Composite View Composer:

``` php
<?php namespace My\Project\Name\Space;

use Coderabbi\Virtuoso\CompositeComposer;

class MyFirstCompositeComposer
	extends CompositeComposer
{

	protected $composers = array(
		'MyFirstSimpleComposer',
		'MySecondSimpleComposer',
		'MyThirdSimpleComposer'
	);
	
}
```

Finally, associate your Composite View Composer (fully qualified Class Name including Namespace) with the 
View (full path within the View Directory specified in `app/config/view.php` using dot notation) by adding 
it to the Composers Array in `app\config\view.php`:

``` php
'composers' => array (
	'partials.header' => 'My\Project\Name\Space\MyFirstCompositeComposer',
)
```

That's it!  Virtuoso will take care of registering the individual View/Composer associations for you - no Service
Provider Required!

You may access data provided by the Composite View Composer from the View as you normally would.

## Roadmap

The addition of tests will bring the package to v1.0. It's a very simple package designed to address a single 
limitation in the standard implementations of Laravel View Composers so at this time I have no further plans
for the package beyond that version.  You are welcome to submit Issues or Pull Requests if you are so inclined; 
I will give my full attention to each.

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
Further details may be found in the [LICENSE](https://github.com/coderabbi/virtuoso/blob/master/LICENSE) file.

## Author

Follow [@coderabbi](http://twitter.com/coderabbi) on Twitter.