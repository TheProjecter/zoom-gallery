{literal}
{
    'result': '{/literal}{$zmgAPI->getParam('result_ok')}{literal}',
    'data': {
        {/literal}
        {foreach item=gallery from=$zmgAPI->getGalleries($zmgAPI->getRequestParamInt('sub'),$zmgAPI->getRequestParamInt('pos'))}
            '{$gallery->gid}':{literal} {{/literal}
                {$gallery->toJSON()}
            {literal}
            },
            {/literal}
        {foreachelse}
        
        {/foreach}
        {literal}
    }
}
{/literal}
