{include file='common/header.tpl'}

<div id="content" class="container">
	<div class="article span9 ui-sortable" id="menu">
		<h2>Карта сайта</h2>
		
		<ul>
		    {include file='common/sitemap_node.tpl' object=$tree}
		</ul>
		<a href="javascript:void(0)" class="show_modal" modal="Sample/Modal">Пример модалки</a>
	</div>
</div>

<div class="modal hide fade in" id="modal">
	<div class="modal-body">
		<div id="loader"></div>
	</div>
</div>

{include file='common/footer.tpl'}