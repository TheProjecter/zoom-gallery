<table width="85%" border="0" class="adminform">
<tr>
  <th colspan="3">
    {t}Here you are able to change storage settings for your gallery like filesystem
    path to your galleries and media, directory and file permissions and upload settings.{/t} 
  </th>
</tr>
<tr>
  <td width="250">
    <label for="zmg_filesystem_mediapath">{t}Path to galleries and media{/t}</label>
  </td>
  <td colspan="2">
    <input type="text" name="zmg_filesystem_mediapath" id="zmg_filesystem_mediapath" value="{$zmgAPI->getConfig('filesystem/mediapath')}" size="70"/>
  </td>
</tr>
<tr>
  <td>
    {t}Permissions{/t}
  </td>
  <td width="30%">
    <label for="zmg_filesystem_dirperms">{t}Directories{/t}</label>
     <input type="text" name="zmg_filesystem_dirperms" id="zmg_filesystem_dirperms" value="{$zmgAPI->getConfig('filesystem/dirperms')}" size="10"/>
  </td>
  <td>
    <label for="zmg_filesystem_fileperms">{t}Files{/t}</label>
     <input type="text" name="zmg_filesystem_fileperms" id="zmg_filesystem_fileperms" value="{$zmgAPI->getConfig('filesystem/fileperms')}" size="10"/>
  </td>
</tr>
<tr>
  <td class="subheader" colspan="3">{t}Uploads{/t}</td>
</tr>
<tr>
  <td>
    <label for="zmg_filesystem_upload_maxfilesize">{t}Maximum file size (Kb){/t}</label>
  </td>
  <td colspan="2">
    <input type="text" name="zmg_filesystem_upload_maxfilesize" id="zmg_filesystem_upload_maxfilesize" value="{$zmgAPI->getConfig('filesystem/upload/maxfilesize')}" size="70"/>
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_filesystem_upload_tempname">{t}Temporary name{/t}</label>
  </td>
  <td colspan="2">
    <input type="text" name="zmg_filesystem_upload_tempname" id="zmg_filesystem_upload_tempname" value="{$zmgAPI->getConfig('filesystem/upload/tempname')}" size="70"/>
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_filesystem_upload_tempdescr">{t}Temporary description{/t}</label>
  </td>
  <td colspan="2">
    <input type="text" name="zmg_filesystem_upload_tempdescr" id="zmg_filesystem_upload_tempdescr" value="{$zmgAPI->getConfig('filesystem/upload/tempdescr')}" size="70"/>
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_filesystem_upload_autonumber">{t}Media autonumbering{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_filesystem_upload_autonumber" id="zmg_filesystem_upload_autonumber" value="1" size="70"{if $zmgAPI->getConfig('filesystem/upload/autonumber') eq 1} checked="checked"{/if} />
  </td>
</tr>
</table>
