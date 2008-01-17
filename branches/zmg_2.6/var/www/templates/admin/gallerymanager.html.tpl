<center>
    <div class="zmg_greyblock">
        <img id="zmg_edit_gallery_thumbnail" src="" alt="{t}Thumbnail{/t}" title="{t}Thumbnail{/t}" border="0"/>
    </div>
</center>

<form name="zmg_form_edit_gallery" id="zmg_form_edit_gallery">

<div id="zmg_edit_gallery_tabs" class="tab-all-container">
    <div class="tab-strip-wrapper">
        <ul class="tab-strip">
            <li><a>{t}Properties{/t}</a></li>
            <li><a>{t}Permissions{/t}</a></li>
        </ul>
    </div>
    <div class="tab-wrapper"></div>
</div> <!-- END div 'zmg_edit_gallery_tabs' -->

<!-- Content for each tab (look at the 'rel' property): -->
<div class="tab-container" rel="properties">
    <table border="0" width="400" class="adminform">
    <tr>
        <td>{t}Name{/t}</td>
        <td>
            <input type="text" name="zmg_edit_gallery_name" id="zmg_edit_gallery_name" value="" size="50" maxlength="50" class="inputbox" />
        </td>
    </tr>
    <tr>
        <td>{t}Directory{/t}</td>
        <td>
            <input type="text" name="zmg_edit_gallery_dir" id="zmg_edit_gallery_dir" value="" size="50" maxlength="50" class="inputbox" />
        </td>
    </tr>
    <tr>
        <td>{t}Password{/t}</td>
        <td>
            <input type="text" name="zmg_edit_gallery_password" id="zmg_edit_gallery_password" value="" size="50" maxlength="50" class="inputbox" />
        </td>
    </tr>
    <tr>
        <td>{t}Keywords{/t}</td>
        <td valign="middle">
          <input type="text" name="zmg_edit_gallery_keywords" id="zmg_edit_gallery_keywords" size="50" value="" class="inputbox" />
        </td>
    </tr>
    <tr>
        <td>{t}Hide 'No Media' text{/t}</td>
        <td>
            <input type="checkbox" name="zmg_edit_gallery_hidenm" id="zmg_edit_gallery_hidenm" value="1"/>
        </td>
    </tr>
    <tr>
        <td>{t}Published{/t}</td>
        <td>
            <input type="checkbox" name="zmg_edit_gallery_published" id="zmg_edit_gallery_published" value="1"/>
        </td>
    </tr>
    <tr>
        <td>{t}Shared{/t}</td>
        <td>
            <input type="checkbox" name="zmg_edit_gallery_shared" id="zmg_edit_gallery_shared" value="1"/>
        </td>
    </tr>
    <tr>
        <td valign="top">{t}Description{/t}</td>
        <td>
           <textarea cols="50" rows="5" name="zmg_edit_gallery_descr" id="zmg_edit_gallery_descr" class="inputbox"></textarea>
        </td>
    </tr>
    </table>
</div>
<div class="tab-container" rel="permissions">
    <!-- new tab should start here... 'Members' -->
    <table border="0" width="300">
    <tr>
        <td>
        <!-- List of members put here... -->
        </td>
    </tr>
    </table>
</div>
<!-- 'Actions' tab will move to the toolbar -->

<input type="hidden" name="gid" value="" />
</form>