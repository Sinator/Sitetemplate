<?php

namespace Page;

/**
 * Главная страница
 * @author Смагин Артем <asmagin@tdigitals.ru>
 */
class sitemap extends \Page {
	/**
	 * Зависимости страницы
	 * @var string
	 */
	protected $dependencies = 'db';

	/**
	 * Заголовок страницы
	 * @var string
	 */
	protected $header = 'Карта сайта';

	/**
	 * Функция создания страницы
	 */
	public function Generate() {
		global $routing_base;
		echo $_SERVER['SCRIPT_URL'];
		$sitemap = new \SiteMap($routing_base);
		
		$tree = $sitemap->GetTree('');
		$this->tpl->assign('tree', $tree);

	}
}

?>