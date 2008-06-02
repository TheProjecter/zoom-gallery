<playlist version="1" xmlns="http://xspf.org/ns/0/">
    <trackList>
        {foreach name=playeriterator item=medium from=$zmgAPI->getMediaFromRequest()}
            {$medium->toXML('playlist')}
        {/foreach}
    </trackList>
</playlist>
