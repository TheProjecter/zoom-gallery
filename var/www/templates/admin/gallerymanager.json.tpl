{literal}
{
    {/literal}
    {if $subview eq 'getgalleries'}
        {foreach item=gallery from=$zoom->getGalleries($zoom->getParamInt('sub'),$zoom->getParamInt('pos'))}
            '{$gallery->gid}':{literal} {{/literal}
                'text': {$zoom->jsonHelper($gallery->name)},
		        'id'  : 'gid:{$gallery->gid}',
		        'icon': ZMG.CONST.res_path + '/images/sample_icons.gif#3',
		        'openicon': '',
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
