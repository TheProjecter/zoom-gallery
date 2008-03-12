{literal}
{
    'result': '{/literal}{$zmgAPI->getParam('result_ok')}{literal}',
    'data': [
        {/literal}
        {foreach name=mediaiterator item=medium from=$zmgAPI->getMedia($zmgAPI->getParamInt('gid'))}
            {literal}{{/literal}
            {$medium->toJSON()}
            {literal}}{/literal}{if !$smarty.foreach.mediaiterator.last},{/if}
        {foreachelse}
        
        {/foreach}
        {literal}
    ]
}
{/literal}
