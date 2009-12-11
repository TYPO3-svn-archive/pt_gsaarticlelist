{* Default pager template for pt_gsaarticlelist; Author: Fabrizio Branca (branca@punkt.de), since 2008-02 *}
{* $Id: pager.tpl,v 1.1 2008/03/05 15:34:26 ry44 Exp $ *}


{if not($prev.alreadyhere)}
	<span class="pager{$prev.class}"><a href="{$prev.url}">{$prev.label}</a></span>
{/if}

{foreach from=$pages item=page}
	{if $page=='fill'}
		{$fill}
	{else}
		<span class="pager{$page.class}"><a href="{$page.url}">{$page.label}</a></span>
	{/if}	 
{/foreach}

{if not($next.alreadyhere)}
	<span class="pager{$next.class}"><a href="{$next.url}">{$next.label}</a></span>
{/if}