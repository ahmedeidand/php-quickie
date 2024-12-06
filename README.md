## Quickie

a PHP API for testing a specific piece of code without needs to call the function which containing the code to be tested.

Example:

If we have a class Test: 

```php

<?php
class Test {

	public function process(int $a, int $b, int $c, int $d)
	{

		print('Processing a' . $a . PHP_EOL) ;
		print('Processing b' . $b . PHP_EOL) ;
		print('Processing c' . $c . PHP_EOL) ;
		print('Processing d' . $d . PHP_EOL) ;

		return 'Success' ;

	}
}
?>
```


If we need to check that if statement  `print('Processing b' . $b . PHP_EOL) ;` is working properly or not

In the standard scenario, We should prepare the arguments and pass it to the process function on an instance of Test class.

But in our case all we need is just to put the separator (Which is configurable in quickieConfig.php - set by default to -  /\*__\*/) before and after the code we want to test.

The code after changes should be : 


```php

<?php
class Test {

	public function __invoke(int $a, int $b, int $c, int $d)
	{

		print('Processing a' . $a . PHP_EOL) ;
		/*__*/
		print('Processing b' . $b . PHP_EOL) ;
		/*__*/
		print('Processing c' . $c . PHP_EOL) ;
		print('Processing d' . $d . PHP_EOL) ;

		return 'Suscess' ;

	}
}
?>
```

Then to test it, We need to run: 

```php

<?php

$quickieInstance = new Quickie(Test::class) ;

/*
 * b is the specific dependency we need to test.
 */

$quickieInstance ->__invoke([
	'b' => NULL
]) ;
<?
```

