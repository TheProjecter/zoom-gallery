[
{foreach name=mediaiterator item=medium from=$zoom->getMedia($zoom->getParamInt('gid'),$zoom->getParamInt('offset'),$zoom->getParamInt('length'))}
    '<img src={$zoom->jsonHelper($medium->getRelPath($mediapath))} id="{$medium->mid}_lgrid_gen"\/><dl><dt>{$medium->name}<\/dt><dd><b>{$medium->descr}<\/b><\/dd><\/dl>'{if !$smarty.foreach.mediaiterator.last},{/if}
{foreachelse}
"<b>{t}No media to show{/t}<\/b>"
{/foreach}
]
