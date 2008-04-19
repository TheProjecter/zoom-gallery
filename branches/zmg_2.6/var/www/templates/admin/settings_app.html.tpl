<table width="85%" border="0" class="adminform">
<tr>
  <th colspan="2">
    {t}Here you are able to change application settings for your gallery. You may
    turn certain features on or off.{/t} 
  </th>
</tr>
<tr>
  <td width="250">
    <label for="zmg_app_features_hotlinkprotection">{t}Hot-linking protection{/t}</label>
  </td>
  <td>
    <input type="checkbox" name="zmg_app_features_hotlinkprotection" id="zmg_app_features_hotlinkprotection" value="1" size="70"{if $zmgAPI->getConfig('app/features/hotlinkprotection') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_app_features_comments">{t}Visitors can leave comments{/t}</label>
  </td>
  <td>
    <input type="checkbox" name="zmg_app_features_comments" id="zmg_app_features_comments" value="1" size="70"{if $zmgAPI->getConfig('app/features/comments') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_app_features_rating">{t}Visitors can rate media{/t}</label>
  </td>
  <td>
    <input type="checkbox" name="zmg_app_features_rating" id="zmg_app_features_rating" value="1" size="70"{if $zmgAPI->getConfig('app/features/rating') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_app_features_imagezoom">{t}Visitors can zoom into images{/t}</label>
  </td>
  <td>
    <input type="checkbox" name="zmg_app_features_imagezoom" id="zmg_app_features_imagezoom" value="1" size="70"{if $zmgAPI->getConfig('app/features/imagezoom') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_app_features_slideshow">{t}Visitors can can play a slideshow{/t}</label>
  </td>
  <td>
    <input type="checkbox" name="zmg_app_features_slideshow" id="zmg_app_features_slideshow" value="1" size="70"{if $zmgAPI->getConfig('app/features/slideshow') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_app_features_lightbox">{t}Visitors can add media to their personal Lightbox{/t}</label>
  </td>
  <td>
    <input type="checkbox" name="zmg_app_features_lightbox" id="zmg_app_features_lightbox" value="1" size="70"{if $zmgAPI->getConfig('app/features/lightbox') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_app_features_dragndrop">{t}Drag 'n Drop uploading{/t}</label>
  </td>
  <td>
    <input type="checkbox" name="zmg_app_features_dragndrop" id="zmg_app_features_dragndrop" value="1" size="70"{if $zmgAPI->getConfig('app/features/dragndrop') eq 1} checked="checked"{/if} />
  </td>
</tr>
</table>
