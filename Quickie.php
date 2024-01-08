<?php

class Quickie {

	/**
	 * Constructs a new instance.
	 * @param      string  $className  The class that contains the code shoule be tested
	 * */
	public function __construct(string $className)
	{

		$this ->_className = $className ;
		$this ->_replaceMap = [] ;
		$this ->_thisContext = NULL ;
	}

	/**
	 * 
	 * @param      array             $codeDependencies  The variables that the code to be tested depends on.
	 * @throws     \DomainException  Thrown when the separator is not configured or set properly */
	public function __invoke(array $codeDependencies)
	{

		$className = $this ->_className ;
		$replaceMap = $this ->_replaceMap ;

		$fn  = function () use ($codeDependencies, $className, $replaceMap) { 
			foreach ($codeDependencies as $dependencyName => $dependencyValue) {
				${$dependencyName} = $dependencyValue ;
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

	/**
	 * Set the object used in instance context .
	 * @param      mixed  $fn     PHP object
	 * @return     self   ( Quicke instance ) 
	 * */
	public function setThis($object)
	{
		$this ->_thisContext = $object; 
		return $this ;
	}

	/**
	 * Replace string. - (To replace the class name with the namespaced class name)
	 * @param      array  $replaceMap  The replace map
	 * @return     self   ( Quickie instance ) 
	 * */
	public function setReplace(array $replaceMap)
	{
		$this ->_replaceMap = $replaceMap ;
		return $this ; 
	}
}

