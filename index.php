<?php 

require './Quickie.php'  ;

class Test {

	public function __invoke(int $a, int $b, int $c, int $d)
	{

		print('Processing a=' . $a . PHP_EOL) ;
		/*__*/
		print('Processing b=' . $b . PHP_EOL) ;
		/*__*/
		print('Processing c=' . $c . PHP_EOL) ;
		print('Processing d=' . $d . PHP_EOL) ;

		return 'Suscess' ;

	}
}

(new  Quickie(Test::class)) ->__invoke([
	'b' => NULL
]) ;

