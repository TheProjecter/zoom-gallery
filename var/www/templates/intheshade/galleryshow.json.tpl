{literal}
{
{/literal}
    'result': '{$zmgAPI->getParam('result_ok')}',
    'data': [
        {literal}
        {
        {/literal}
        {$zmgAPI->getGallery($zmgAPI->getViewToken('last'), 'json')}
        {literal}
        },
        {/literal}
        {foreach name=mediaiterator item=medium from=$zmgAPI->getMedia($zmgAPI->getViewToken('last'))}
            {literal}{{/literal}
            {$medium->toJSON()}
            {literal}}{/literal}{if !$smarty.foreach.mediaiterator.last},{/if}
        {foreachelse}
        
        {/foreach}
        {literal}
    ]
}
{/literal}
