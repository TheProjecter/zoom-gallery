[
{foreach name=mediaiterator item=medium from=$zmgAPI->getMedia($zmgAPI->getParamInt('gid'),$zmgAPI->getParamInt('offset'),$zmgAPI->getParamInt('length'), $zmgAPI->getParam('subview'))}
    '<img src={$zmgAPI->jsonHelper($medium->getRelPath())} id="{$medium->mid}_lgrid_gen"\/><dl><dt>{$medium->name}<\/dt><dd><b>{$medium->descr}<\/b><\/dd><\/dl>'{if !$smarty.foreach.mediaiterator.last},{/if}
{foreachelse}
"<b>{t}No media to show{/t}<\/b>"
{/foreach}
]
