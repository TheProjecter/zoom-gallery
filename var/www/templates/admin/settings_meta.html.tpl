<table width="85%" border="0" class="adminform">
<tr>
  <th colspan="2">
    {t}Here you are able to change metadata settings for your gallery like it's name (used
    for the browser's titlebar as well), description and keywords (used for search engines).{/t} 
  </th>
</tr>
<tr>
  <td width="250">
    <label for="zmg_meta_title">{t}Title{/t}</label>
  </td>
  <td>
    <input type="text" name="zmg_meta_title" id="zmg_meta_title" value="{$zmgAPI->getConfig('meta/title')}" size="70"/>
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_meta_description">{t}Description{/t}</label>
  </td>
  <td>
    <input type="text" name="zmg_meta_description" id="zmg_meta_description" value="{$zmgAPI->getConfig('meta/description')}" size="70"/>
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_meta_keywords">{t}Keywords{/t}</label>
  </td>
  <td>
    <input type="text" name="zmg_meta_keywords" id="zmg_meta_keywords" value="{$zmgAPI->getConfig('meta/keywords')}" size="70"/>
  </td>
</tr>
</table>
