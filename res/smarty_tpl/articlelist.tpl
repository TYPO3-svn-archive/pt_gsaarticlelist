{* Default template for pt_gsaarticlelist; Author: Fabrizio Branca (branca@punkt.de), since 2008-02 *}
{* $Id: articlelist.tpl,v 1.2 2008/03/12 17:20:54 ry44 Exp $ *}

<table class="tx-ptgsashop-pi2-boxtable{$art.class_tableWidth}">

	{foreach from=$articles item=art}

    <tr>
    <td valign="bottom" class="tx-ptgsashop-pi2-boxheader">
  	  {if $art.cond_articleDisplayUrl AND $art.singleViewUrl != ''}
		  {if $art.cond_articleDisplayDescription}
		  	<a href="{$art.singleViewUrl}"><span class="tx-ptgsashop-pi2-txt1">{$art.articleDescription}</span></a>&nbsp;
	      {/if}
	  {else}
		  {if $art.cond_articleDisplayDescription}
	        	<span class="tx-ptgsashop-pi2-txt1">{$art.articleDescription}</span>&nbsp;
	      {/if}
	  {/if}
      {if $art.cond_articleDisplayArticleno}
        <span class="tx-ptgsashop-pi2-txt2">({$art.ll_artNo}: {$art.articleNo})</span>
      {/if}
    </td>
    <td valign="bottom" align="right" class="tx-ptgsashop-pi2-boxheader">
        <form action="{$art.faction_addToCart}" method="post" class="tx-ptgsashop-pi2-formdefault">
          {if $art.cond_articleDisplayPrice}
            <span class="tx-ptgsashop-pi2-txt2">{$art.ll_price}</span>
            <span class="tx-ptgsashop-pi2-txt1">{$art.articlePrice}</span>
          {/if}
            <input type="hidden" name="no_cache" value="1" />
            <input type="hidden" name="{$art.fname_articleId}" value="{$art.fval_articleId}" />
            {$art.fhidden_reloadHandlerToken}
          {if $art.cond_articleDisplayOrderbutton}
            <input type="image" src="{$art.imgsrc_cartButton}" name="{$art.fname_cartButton}" title="{$art.ll_titleCartButton}" />
          {/if}
          {if $art.cond_articleDisplayCartqty}
            <span class="tx-ptgsashop-pi2-txt2" title="{$art.ll_titleCartQty}">({$art.cartArticleQty})</span>
          {/if}
          {if $art.cond_articleDisplayRemovebutton}
            {if $art.cond_article_in_cart}
            <input type="image" src="{$art.imgsrc_removeButton}" name="{$art.fname_removeButton}" title="{$art.ll_titleRemoveButton}" />
            {else}
            <img src="typo3conf/ext/pt_gsashop/res/img/empty.gif" {$art.emptygif_attributes} />
            {/if}
          {/if}
        </form>
    </td>
    </tr>
    {if $art.cond_fixedCost}
    <tr>
        <td colspan="2" class="tx-ptgsashop-pi2-boxcell">
            <span class="tx-ptgsashop-pi2-txt1">{$art.artFixedCostTotal}</span>
            <span class="tx-ptgsashop-pi2-txt2">{$art.ll_artfixedcost_info}</span>
        </td>
    </tr>
    {/if}
    {if $art.cond_articleDisplayMatch1}
    <tr>
        <td colspan="2" class="tx-ptgsashop-pi2-boxcell">
            <span class="tx-ptgsashop-pi2-txt2">{$art.articleMatch1}</span>
        </td>
    </tr>
{/if}
{if $art.cond_articleDisplayMatch2}
    <tr>
        <td colspan="2" class="tx-ptgsashop-pi2-boxcell">
            <span class="tx-ptgsashop-pi2-txt2">{$art.articleMatch2}</span>
        </td>
    </tr>
    {/if}
    {if $art.cond_articleDisplayDeftext}
    <tr>
        <td colspan="2" class="tx-ptgsashop-pi2-boxcell">
            <span class="tx-ptgsashop-pi2-txt2">{$art.articleDefText}</span>
        </td>
    </tr>
    {/if}
    {if $art.cond_articleDisplayAlttext}
    <tr>
        <td colspan="2" class="tx-ptgsashop-pi2-boxcell">
            <span class="tx-ptgsashop-pi2-txt2">{$art.articleAltText}</span>
        </td>
    </tr>
    {/if}
    {if $art.cond_articleDisplayImg}
	<!-- old images (from GSA) 
    <tr>
        <td colspan="2" class="tx-ptgsashop-pi2-boxcell">
            <img src="{$art.imgsrc_articleImg}" width="{$art.width_articleImg}" height="{$art.height_articleImg}" />
        </td>
    </tr>
	-->
	<!-- new images (from TYPO3) -->
	<tr>
		<td colspan="2" class="tx-ptgsashop-pi2-boxcell">
			{foreach from=$art.images item=image}
				{$image.img}
			{/foreach}
		</td>
	</tr> 
    {/if}
    {if $art.cond_articleDisplayPricescales}
    <tr>
        <td colspan="2" class="tx-ptgsashop-pi2-boxcell">
            <table class="tx-ptgsashop-pi2-boxtable">
                <tr>
                    <th valign="bottom" class="tx-ptgsashop-pi2-boxheader">{$art.ll_pricescales_qty}</th>
                    <th valign="bottom" class="tx-ptgsashop-pi2-boxheader">{$art.ll_pricescales_price}</th>
                </tr>
              {foreach from=$art.articlePriceScales item=price key=quantity}
                <tr>
                    <td class="tx-ptgsashop-pi2-boxcell">{$quantity}</td>
                    <td class="tx-ptgsashop-pi2-boxcell">{$price}</td>
                </tr>
              {/foreach}
            </table>    
        </td>
    </tr>    
    {/if}
	<tr>
		<td style="height:20px" colspan="2"></td>
	</tr>
	
	{/foreach}
	
</table>
{if $art.cond_articleDisplayCartlink}
<a href="{$art.href_cartPage}" class="tx-ptgsashop-pi2-link">{$art.ll_cartLink}</a><br /><br />
{/if}