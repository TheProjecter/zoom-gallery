{literal}
{
    {/literal}
    {if $zmgAPI->getParam('subview') eq 'getgalleries'}
        {foreach name=galleryiterator item=gallery from=$zmgAPI->getGalleries($zmgAPI->getParamInt('sub'),$zmgAPI->getParamInt('pos'))}
            '{$gallery->gid}':{literal} {{/literal}
                'text': {$zmgAPI->jsonHelper($gallery->name)},
		        'id'  : 'admin:gallerymanager:get:{$gallery->gid}',
		        'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#2',
		        'openicon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#3',
		        'open': false,
		        'load': ZMG.CONST.req_uri + '&view=admin:gallerymanager:getgalleries&sub={$gallery->gid}&pos={$zmgAPI->getParamInt('pos')+1}',
            {literal}}{/literal}
            {if !$smarty.foreach.galleryiterator.last},{/if}
        {foreachelse}
        
        {/foreach}
    {/if}
    {literal}
}
{/literal}
