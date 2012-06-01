<?php

/**
 * Класс управления деревом сайта
 * @author Смагина АС
 */
class SiteMap {
	
	/**
	 * Распаршенное дерево сайта на многоуровневый массив 
	 */
	private $routing;
	
	/**
	 * Конструктор класса
	 */
	function __construct($routing = null)	{
		if(isset($routing['paths']) && isset($routing['pages']))
		
	 	$start = explode(' ', microtime());	
			$this->routing = $this->RoutingParse($routing);
		$end = explode(' ', microtime());
		$total = round(($end[1]+$end[0])-($start[1]+$start[0]), 8);
		
				//return false;
		//else 
			//return false;
		
		echo "<pre>";
		echo $total."<br>";
		print_r($this->routing);
		echo "</pre>";
		

	}
	
	/**
	 * Метод получения дерева сайта начиная с $node
	 * @param string $node Путь от которого начинаем строить дерево
	 * 
	 * @return mix Массив элементов дерева либо False
	 */
	function GetTree($node = null) {
		global $routing_base;
		
		
		//echo "<pre>";
		//print_r($routing_base);
		//echo "</pre>";
	}
	
	/**
	 * Мотод парсенга массива routing в многоуровневый иерархичный массив
	 * 
	 * @param array $routing Массив роутингов
	 * 
	 * @return array многоуровневый иерархичный массив путей
	 */
	 function RoutingParse($routing) {
		$pattern = "|^(/[\w\d]*)(/.*)?$|i";
		$result = false;
		foreach($routing['paths'] AS $path => $data) {
			$node = array();
			if(preg_match($pattern, $path, $paths)) {
				if(!empty($paths[2]) && $paths[2] != '/')
						$node[$paths[1]]['children'] = $this->RoutingParse(array('paths' => array($paths[2] => $data)));
				else
					$node[$paths[1]]['data'] = $data;
			}
			$result = !empty($result)? array_merge_recursive($result, $node) : $node;
		}
		return $result;
	 }

}

?>