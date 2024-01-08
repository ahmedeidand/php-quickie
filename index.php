<?php

require './Test.php' ;

class Quickie {

	public function __construct(string $className)
	{

		$this ->_className = $className ;
		$this ->_replaceMap = [] ;
		$this ->_thisContext = NULL ;
	}

	public function __invoke(array $codeDependencies)
	{

		$className = $this ->_className ;
		$replaceMap = $this ->_replaceMap ;

		$fn  = function () use ($codeDependencies, $className, $replaceMap) { 
			foreach ($codeDependencies as $name => $fn) {
				${$name} = $fn instanceof \Closure ?  $fn ->__invoke() : $fn ;
			}

			$quickieConfig = require './quickieConfig.php' ;

			$codeString = explode($quickieConfig ['SEPARATOR'], file_get_contents((new \ReflectionClass($className)) ->getFileName())) [1] ?? NULL  ;

			if (empty($codeString)) throw new \DomainException('NO CODE TO BE EVALUATED. MAKE SURE YOU ARE SETTING SEPARATORS PROPERLY') ;

			$processedString = array_reduce(array_keys($replaceMap), function (string $codeString, string $r) use ($replaceMap) {
				return str_replace($r, $replaceMap [$r], $codeString); 
			}, $codeString) ;

			eval($processedString)  ;
		};

		$fn ->bindTo($this ->_thisContext ?: NULL, $this ->_className) ->__invoke();
	}

	public function setThis(mixed $fn)
	{
		$this ->_thisContext = $fn instanceof \Closure ?  $fn	->__invoke() : $fn ;return $this ;
	}

	public function setReplace(array $replaceMap)
	{
		$this ->_replaceMap = $replaceMap ;return $this ; 
	}
}


(new  Quickie(Test::class)) ->__invoke([
	'name' => 'الله'
]) ;

