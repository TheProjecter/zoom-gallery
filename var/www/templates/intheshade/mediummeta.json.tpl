{literal}
{
{/literal}
    'result': '{$zmgAPI->getParam('result_ok')}',
    'data': [
        {foreach name=metaiterator item=metaobj from=$zmgAPI->getMediaMetadata($zmgAPI->getViewToken('last'))}
            {literal}{{/literal}
            {$metaobj->toJSON()}
            {literal}}{/literal}{if !$smarty.foreach.metaiterator.last},{/if}
        {foreachelse}
        
        {/foreach}
        {literal}
    ]
}
{/literal}