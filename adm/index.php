<?php

try {
	include('bootstrap.php');
	Dependencies::Init('db', $settings);
	$router = new Router();
	if($router->GetByPath()){
		var_dump($router->GetPageId());
	}
	else
		echo 'Страница не найдена';
	
	$site = new Site($settings,$routing);
	$site->Build();
} catch (Exception $e) {
	echo($e->getMessage());
}

?>