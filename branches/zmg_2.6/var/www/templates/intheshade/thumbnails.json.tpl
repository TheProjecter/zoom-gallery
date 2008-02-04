{literal}
{
    'result': '{/literal}{$zmgAPI->getParam('result_ok')}{literal}',
    'data': {
        {/literal}
        {foreach item=medium from=$zmgAPI->getMedia($zmgAPI->getParamInt('gid'))}
            '{$medium->medium}':{literal} {{/literal}
                {$medium->toJSON()}
            {literal}
            },
            {/literal}
        {foreachelse}
        
        {/foreach}
        {literal}
    }
}
{/literal}
