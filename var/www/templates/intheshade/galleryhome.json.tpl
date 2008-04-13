{literal}
{
    'result': '{/literal}{$zmgAPI->getParam('result_ok')}{literal}',
    'data': [
        {/literal}
        {foreach name=galleryiterator item=gallery from=$zmgAPI->getGalleries($zmgAPI->getRequestParamInt('sub'),$zmgAPI->getRequestParamInt('pos'))}
            {literal}{{/literal}
            {$gallery->toJSON()}
            {literal}}{/literal}{if !$smarty.foreach.galleryiterator.last},{/if}
        {foreachelse}
        
        {/foreach}
        {literal}
    ]
}
{/literal}
