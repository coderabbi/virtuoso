![image](https://dl.dropboxusercontent.com/s/em253abjckwr6k4/Virtuoso.png)

#Laravel Virtuoso

####Laravel Composable View Composers Package

Increase flexibility and reduce code duplication by composing complex View Composers from simple 
component Composers.

## Background

In many of our projects, the same data is often repeated on multiple pages. This presents the challenge 
of preparing this data in our Controllers and providing it to our various Views without an undue amount 
of code repetition. Laravel provides us with the ability to limit this potential repetition through an 
abstraction known as the View Composer. A View Composer allows you to abstract this code to a single 
location and make it available to multiple Views. A View Composer is simply a piece of code which is 
bound to a View and executed whenever that View is requested.

An example View Composer from the Laravel documentation:

``` php
View::composer('profile'), function($view)
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
    	$this->app->view->composer('profile', 'My\Project\Name\Space\UserCountComposer');
  	}
 
}
```

#### Additional Resources

* View Composers in the [Laravel Documentation](http://laravel.com/docs/responses#view-composers).
* View Composers at [Laracasts](https://laracasts.com/lessons/view-composers).

## Application

Unfortunately, the out-of-the-box functionality of Laravel View Composers is relatively limited.  

If we choose to go with the ```View::composer()``` format, our bootstrap files will quickly become overblown
and unwieldy.  On the other hand, if we choose a Class based approach, we're forced to create a Service 
Provider to register each of our Composer/View associations.  In both cases, an element of indirection is
involved as there is no single, obvious place to easily view all of our Composer/View associations.  These
issues are only compounded if we wish to associate a single Composer with multiple Views (which is the whole
point, of course\!).  Finally, both approaches have a glaring limitation - it is only possible to associate a
single View Composer with any given View.  This leads to code repetition when particular data is necessary for 
multiple Views but is not the only data required for one or more of the Views which consumes it - the very
issue that View Composers are intended to solve!

Let's look at an example.  

Consider the prototypical "Admin Panel" implementation.  We may have a "Dashboard" View which includes high-level
data we wish to highlight about our users as well as various entities unique to our domain. We're also likely to 
have a "Users" View which may feature that same user data as the "Dashboard" but also requires additional, more 
granular data regarding users of our system.

How would we approach this challenge using View Composers?  If we create a View Composer which provides just the
high-level user data required by the "Dashboard" View and associate that also with the "Users" View, we're going 
to be forced to prepare the more granular user data for the "Users" View in the Controller.  What if that data 
is itself required elsewhere? Ideally, we should be able to create separate View Composers for both the high-level 
and granular user data and associate one or more of them with our various Views as needed - this would allow us to 
add our data to the Views via composition (seems like the very issue that View Composers were intended to address, 
right?).  Unfortunately, neither of the two common View Composer implementations allows us to associate multiple 
View Composers with a single View\!

This is the problem that Virtuoso is intended to solve.  Virtuoso allows you to create simple, single-focused View 
Composers for your data and leverage composition when providing data to your Views by associating one or more View
Composers with a single View via a "Composite Composer" as needed.  This results in less code duplication and more 
tightly focused View Composers. As a pleasing side benefit, Virtuoso's "Composer Array" provides a single, obvious 
location to view our Composer/View associations, limiting the impact of the indirection inherent in using View 
Composers.

## Installation

First, install Virtuoso through Composer, either by adding it to the Require Array of your 
`composer.json`:

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
	
	protected function getMyData()
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

That's it!  Virtuoso will take care of registering the Service Provider for you!

You may access data provided by the Simple View Composer from the View as you normally would:

``` php
<ul>
	@foreach ($myData as $datum)
		<li>{{ $datum }}</li>
	@endforeach
</ul>
```

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
		'MySecondSimpleComposerComposer',
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

That's it!  Virtuoso will take care of registering the Service Provider and combining the component View 
Composers for you!

You may access data provided by the Composite View Composer from the View as you normally would:

``` php
<ul>
	@foreach ($myData as $datum)
		<li>{{ $datum }}</li>
	@endforeach
</ul>
```

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
