[
{foreach name=mediaiterator item=medium from=$zoom->getMedia($zoom->getParamInt('gid'),$zoom->getParamInt('offset'),$zoom->getParamInt('length'))}
    "<img src=\"{$site_url|regex_replace:"/[\/]/":"\\/"}\/{$zoom->getConfig('filesystem/mediapath')|regex_replace:"/[\/]+/":"\\/"}{$medium->gallery_dir}\/thumbs\/{$medium->filename}\"\/><dl><dt>{$medium->name}<\/dt><dd><b>{$medium->descr}<\/b><\/dd><\/dl>"{if !$smarty.foreach.mediaiterator.last},{/if}
{foreachelse}
"<b>{t}No media to show{/t}<\/b>"
{/foreach}
]