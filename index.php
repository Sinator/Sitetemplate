<?php

try {
	include('bootstrap.php');

	Dependencies::Init('db', $settings);
	$router = new Router();
	if($router->GetByPath()){
		echo '<pre>';
		print_r($router->GetParams());
		echo '</pre>';
	}
	else
		$router->Error404();


	$site = new Site($settings,$routing);
	$site->Build();
} catch (Exception $e) {
	echo('');
}

?>