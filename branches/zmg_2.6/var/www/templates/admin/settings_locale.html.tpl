<table width="85%" border="0" class="adminform">
<tr>
  <th colspan="2">
    {t}Here you are able to change localization settings for your gallery like the default
    language and the encoding of messages that come from the server.{/t} 
  </th>
</tr>
<tr>
  <td width="250">
    <label for="zmg_locale_default">{t}Default language{/t}</label>
  </td>
  <td>
    <input type="text" name="zmg_locale_default" id="zmg_locale_default" value="{$zmgAPI->getConfig('locale/default')}" size="70"/>
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_locale_encoding">{t}Message encoding{/t}</label>
  </td>
  <td>
    <input type="text" name="zmg_locale_encoding" id="zmg_locale_encoding" value="{$zmgAPI->getConfig('locale/encoding')}" size="70"/>
  </td>
</tr>
</table>
