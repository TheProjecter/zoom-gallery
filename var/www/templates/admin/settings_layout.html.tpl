<table width="85%" border="0" class="adminform">
<tr>
  <th colspan="3">
    {t}Here you are able to change layout settings for your gallery that will determine
    the look and feel of it.{/t} 
  </th>
</tr>
<tr>
  <td width="250">
    <label for="zmg_layout_galleryprefix">{t}Gallery prefix{/t}</label>
  </td>
  <td>
    <input type="text" name="zmg_layout_galleryprefix" id="zmg_layout_galleryprefix" value="{$zoom->getConfig('layout/galleryprefix')}" size="70"/>
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_columnsno">{t}Number of columns for galleries{/t}</label>
  </td>
  <td>
    <input type="text" name="zmg_layout_columnsno" id="zmg_layout_columnsno" value="{$zoom->getConfig('layout/columnsno')}" size="70"/>
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_media_columnsno">{t}Number of columns for media{/t}</label>
  </td>
  <td>
    <input type="text" name="zmg_layout_media_columnsno" id="zmg_layout_media_columnsno" value="{$zoom->getConfig('layout/media/columnsno')}" size="70"/>
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_pagesize">{t}Number of items per page{/t}</label>
  </td>
  <td>
    <input type="text" name="zmg_layout_pagesize" id="zmg_layout_pagesize" value="{$zoom->getConfig('layout/pagesize')}" size="70"/>
  </td>
</tr>
<tr>
  <td class="subheader" colspan="3">{t}General settings{/t}</td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_showoccspace">{t}Show occupied space in Gallery Manager{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_showoccspace" id="zmg_layout_showoccspace" value="1" size="70"{if $zoom->getConfig('layout/showoccspace') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_showtopten">{t}Show 'Top Ten' link{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_showtopten" id="zmg_layout_showtopten" value="1" size="70"{if $zoom->getConfig('layout/showtopten') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_showlastsubm">{t}Show 'Last Submitted' link{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_showlastsubm" id="zmg_layout_showlastsubm" value="1" size="70"{if $zoom->getConfig('layout/showlastsubm') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_showcloselink">{t}Show 'Close' link in Popup window{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_showcloselink" id="zmg_layout_showcloselink" value="1" size="70"{if $zoom->getConfig('layout/showcloselink') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_showmainscreen">{t}Show 'Mainscreen' link in breadcrumb navigation{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_showmainscreen" id="zmg_layout_showmainscreen" value="1" size="70"{if $zoom->getConfig('layout/showmainscreen') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_shownavbuttons">{t}Show navigation links{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_shownavbuttons" id="zmg_layout_shownavbuttons" value="1" size="70"{if $zoom->getConfig('layout/shownavbuttons') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_showmediafound">{t}Show 'x Media found' notification{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_showmediafound" id="zmg_layout_showmediafound" value="1" size="70"{if $zoom->getConfig('layout/showmediafound') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_usepopup">{t}Show each medium in a seperate window (popup){/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_usepopup" id="zmg_layout_usepopup" value="1" size="70"{if $zoom->getConfig('layout/usepopup') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_showgalleryimg">{t}Show a thumbnail in front of each gallery{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_showgalleryimg" id="zmg_layout_showgalleryimg" value="1" size="70"{if $zoom->getConfig('layout/showgalleryimg') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_showgallerydsc">{t}Show the description of each gallery{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_showgallerydsc" id="zmg_layout_showgallerydsc" value="1" size="70"{if $zoom->getConfig('layout/showgallerydsc') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_showsearchbox">{t}Show the search inputbox{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_showsearchbox" id="zmg_layout_showsearchbox" value="1" size="70"{if $zoom->getConfig('layout/showsearchbox') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_showzmglogo">{t}Show Zoom Media Gallery logo{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_showzmglogo" id="zmg_layout_showzmglogo" value="1" size="70"{if $zoom->getConfig('layout/showzmglogo') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td class="subheader" colspan="3">{t}Ordering settings{/t}</td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_ordering_galleries">{t}(sub-)Gallery ordering method{/t}</label>
  </td>
  <td>
    <select name="zmg_layout_ordering_galleries" id="zmg_layout_ordering_galleries">
      <option value="1"{if $zoom->getConfig('layout/ordering/galleries') eq 1} selected="selected"{/if}>
        {t}by Date, ascending{/t}
      </option>
      <option value="2"{if $zoom->getConfig('layout/ordering/galleries') eq 2} selected="selected"{/if}>
        {t}by Date, descending{/t}
      </option>
      <option value="3"{if $zoom->getConfig('layout/ordering/galleries') eq 3} selected="selected"{/if}>
        {t}by Name, ascending{/t}
      </option>
      <option value="4"{if $zoom->getConfig('layout/ordering/galleries') eq 4} selected="selected"{/if}>
        {t}by Name, descending{/t}
      </option>
    </select>
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_ordering_media">{t}Media ordering method{/t}</label>
  </td>
  <td>
    <select name="zmg_layout_ordering_media" id="zmg_layout_ordering_media">
      <option value="1"{if $zoom->getConfig('layout/ordering/media') eq 1} selected="selected"{/if}>
        {t}by Date, ascending{/t}
      </option>
      <option value="2"{if $zoom->getConfig('layout/ordering/media') eq 2} selected="selected"{/if}>
        {t}by Date, descending{/t}
      </option>
      <option value="3"{if $zoom->getConfig('layout/ordering/media') eq 3} selected="selected"{/if}>
        {t}by Filename, ascending{/t}
      </option>
      <option value="4"{if $zoom->getConfig('layout/ordering/media') eq 4} selected="selected"{/if}>
        {t}by Filename, descending{/t}
      </option>
      <option value="3"{if $zoom->getConfig('layout/ordering/media') eq 5} selected="selected"{/if}>
        {t}by Name, ascending{/t}
      </option>
      <option value="4"{if $zoom->getConfig('layout/ordering/media') eq 6} selected="selected"{/if}>
        {t}by Name, descending{/t}
      </option>
    </select>
  </td>
</tr>
<tr>
  <td class="subheader" colspan="3">{t}Viewing media settings{/t}</td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_media_showhits">{t}Show number of hits{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_media_showhits" id="zmg_layout_media_showhits" value="1" size="70"{if $zoom->getConfig('layout/medium/showhits') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_media_showname">{t}Show name{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_media_showname" id="zmg_layout_media_showname" value="1" size="70"{if $zoom->getConfig('layout/medium/showname') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_media_showdescr">{t}Show description{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_media_showdescr" id="zmg_layout_media_showdescr" value="1" size="70"{if $zoom->getConfig('layout/medium/showdescr') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_media_showkeywords">{t}Show keywords{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_media_showkeywords" id="zmg_layout_media_showkeywords" value="1" size="70"{if $zoom->getConfig('layout/medium/showkeywords') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_media_showdate">{t}Show date{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_media_showdate" id="zmg_layout_media_showdate" value="1" size="70"{if $zoom->getConfig('layout/medium/showdate') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_media_showusername">{t}Show username{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_media_showusername" id="zmg_layout_media_showusername" value="1" size="70"{if $zoom->getConfig('layout/medium/showusername') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_media_showfilename">{t}Show filename{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_media_showfilename" id="zmg_layout_media_showfilename" value="1" size="70"{if $zoom->getConfig('layout/medium/showfilename') eq 1} checked="checked"{/if} />
  </td>
</tr>
<tr>
  <td>
    <label for="zmg_layout_media_showmetadata">{t}Show metadata{/t}</label>
  </td>
  <td colspan="2">
    <input type="checkbox" name="zmg_layout_media_showmetadata" id="zmg_layout_media_showmetadata" value="1" size="70"{if $zoom->getConfig('layout/medium/showmetadata') eq 1} checked="checked"{/if} />
  </td>
</tr>
</table>