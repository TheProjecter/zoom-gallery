{literal}
{
    {/literal}
    {if $subview eq 'getgalleries'}
        {foreach item=gallery from=$zoom->getGalleries($zoom->getParamInt('sub'),$zoom->getParamInt('pos'))}
            '{$gallery->gid}':{literal} {{/literal}
                'text': {$zoom->jsonHelper($gallery->name)},
		        'id'  : 'admin:gallerymanager:get:{$gallery->gid}',
		        'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#2',
		        'openicon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#3',
		        'open': false,
		        'load': ZMG.CONST.req_uri + '&view=admin:gallerymanager:getgalleries&sub={$gallery->gid}&pos={$zoom->getParamInt('pos')+1}',
            {literal}
            }
            {/literal}
        {foreachelse}
        
        {/foreach}
    {/if}
    {literal}
}
{/literal}
