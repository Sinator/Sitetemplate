<?php

/**
 * Класс роутингов
 * @author Смагин АС <asmagin@tdigitals.ru>
 */
class Router {

	/**
	 * Переменная содержащая в себе id, class, type контроллера
	 */
	protected $controller;
	
	/**
	 * Переменная параметров, содержит как _GET, так и параметры из URL-Regex
	 */
	protected $params;
	
	
	/**
	 * Метод получения информации о страце по пути
	 * @param string $path Запрошеный путь. Если NULL то использует $_SERVER['SCRIPT_URL']
	 * 
	 * @return boolen TRUE если нашли страницу. FALSE в противном случае.
	 */
	 public function GetByPath($path = null) {
	 	global $settings, $db;
		
		// Устанавливаем переменную пути
	 	if(empty($path))
			$this->path = $_SERVER['SCRIPT_URL'];
		else
			$this->path = $path;
		
		// Если задан префикс, то удаляем его из пути
		if(!empty($settings['Route']['prefix']))
			$this->path = preg_replace('|^'.$settings['Route']['prefix'].'|i', '', $this->path);
			
		// Если задан суффикс, то удаляем его из пути
		if(!empty($settings['Route']['suffix']))
			$this->path = preg_replace('|'.$settings['Route']['suffix'].'$|i', '', $this->path);
		
		// Если путь пуст, то скорей всего это был корен, так вернем же его )
		if(empty($this->path))
			$this->path = '/';
		
		// Подготавлеваем переменную пути для запроса
		$path = addslashes($this->path);
		
		// Поиск по роутингам
		$query = "SELECT 
					`r`.`regex`,
					`r`.`params`,
					`c`.`id`,
					`c`.`class`,
					`c`.`type`
				FROM 
					`Routing` AS  `r`
					JOIN `Controllers` AS `c` ON
						`c`.`id` = `r`.`controller_id`
				WHERE 
					'$path' RLIKE `r`.`regex`";
					
		// Выполняем запрос
		if(!$q = $db->query($query))
			if(is_object($q))
				throw new SiteException('Ошибка запроса к БД. SQL Info: '. implode(', ', $q->errorInfo()));
			else
				throw new SiteException('Ошибка запроса к БД. SQL Info: '. implode(', ', $db->errorInfo()));
		
		// Формируем массив контроллера
		$res = $q->fetchAll(PDO::FETCH_ASSOC);
		foreach ($res AS $row) {
			$this->controller = $row;
			// Устанавлеваем параметры
			$this->ParseParams('|'.$row['regex'].'|i', $row['params']);
			return true;
		}

		// Поиск по алиасам
		$query = "SELECT 
					`a`.`regex`,
					`a`.`params`,
					`c`.`id`,
					`c`.`class`,
					`c`.`type`
				FROM 
					`Aliases` AS  `a`
					JOIN `Routing` AS `r` ON
						`r`.`id` = `a`.`route_id`
					JOIN `Controllers` AS `c` ON
						`c`.`id` = `r`.`controller_id`
				WHERE 
					'$path' RLIKE `a`.`regex`";
					
		// Выполняем запрос
		if(!$q = $db->query($query))
			if(is_object($q))
				throw new SiteException('Ошибка запроса к БД. SQL Info: '. implode(', ', $q->errorInfo()));
			else
				throw new SiteException('Ошибка запроса к БД. SQL Info: '. implode(', ', $db->errorInfo()));
		
		// Формируем массив контроллера
		$res = $q->fetchAll(PDO::FETCH_ASSOC);
		foreach ($res AS $row) {
			$this->controller = $row;
			// Устанавлеваем параметры
			$this->ParseParams('|'.$row['regex'].'|i', $row['params']);
			return true;
		}
		
		return false;
	 }
	
	
	/**
	 * Метод получения переменных из Regex URL и _GET
	 * @param string $pattern Регульрное выражене
	 * @param string $params Имена параметров через запятую в том же порядке, что и в регулярке
	 * 
	 */
	 protected function ParseParams($pattern, $params) {
	 	$params_array = array();
	 	if(!empty($params)) {
	 		$params = explode(',', $params);
	 		preg_match($pattern, $this->path, $matches);
	 		foreach($params AS $k => $param) 
	 			$params_array[$param] = $matches[$k+1];
	 	}
		
		$this->params = array_merge($_GET, $params_array);
	 	
	 	return true;
	 }
	
	
	/**
	 * Метод получения параметров
	 * 
	 * @return mixed Массив параметров либо FALSE
	 */
	 public function GetParams() {
	 	if(is_array($this->params))
	 		return $this->params;
		return false;
	 }
	
	
	/**
	 * Метод получения параметров
	 * 
	 * @return mixed Массив параметров либо FALSE
	 */
	 public function GetController() {
	 	if(!empty($this->controller))
	 		return $this->controler;
		return false;
	 }
	 
	 /**
	 * Метод генерации ошибки 404
	 */
	 public function Error404() {
	 	define('SAPI_NAME', php_sapi_name());
		if (SAPI_NAME == 'cgi' OR SAPI_NAME == 'cgi-fcgi') 
			header('Status: 404 Not Found');
		else 
			header('HTTP/1.1 404 Not Found');
			echo "<h1>Хээррр-ра себе!!<br/> Не ррр-рэбят, я не в куррр-рсе!</h1> (#404) <a href='http://".$_SERVER['SERVER_NAME']."'>Go HOME</a>";
		exit();
	 }
}

?>