{foreach item=node from=$object key=path}
  {if $path != '_data'}
      <li>{$path}{if !empty($node._data.page)} (controller: {$node._data.page}){/if}</li>
          {if count($node) > 1 || (count($node) == 1 && !isset($node._data)) }
              <ul>
                  {include file='common/sitemap_node.tpl' object=$node}
              </ul>
          {/if}
  {/if}
{/foreach}