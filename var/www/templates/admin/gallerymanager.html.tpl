<form name="zmg_form_edit_gallery" id="zmg_form_edit_gallery">
	<center>
	    <div class="zmg_greyblock">
	        <img id="zmg_edit_gallery_thumbnail" src="" alt="{t}Thumbnail{/t}" title="{t}Thumbnail{/t}" border="0"/>
	    </div>
	</center>
	
	<div id="zmg_edit_gallery_tabs" class="tab-all-container">
	    <div class="tab-strip-wrapper">
	        <ul class="tab-strip">
	            <li><a rel="properties">{t}Properties{/t}</a></li>
	            <li><a rel="permissions">{t}Permissions{/t}</a></li>
	        </ul>
	    </div>
	    <div class="tab-wrapper"></div>
	</div> <!-- END div 'zmg_edit_gallery_tabs' -->
	
	<!-- Content for each tab (look at the 'rel' property): -->
	<div class="tab-container" rel="properties">
	    <table border="0" width="400" class="adminform">
	    <tr>
	        <td><label for="zmg_edit_gallery_name">{t}Name{/t}</label></td>
	        <td>
	            <input type="text" name="zmg_edit_gallery_name" id="zmg_edit_gallery_name" value="" size="50" maxlength="50" class="inputbox" />
	        </td>
	    </tr>
	    <tr>
	        <td><label for="zmg_edit_gallery_dir">{t}Directory{/t}</label></td>
	        <td>
	            <input type="text" name="zmg_edit_gallery_dir" id="zmg_edit_gallery_dir" value="" size="50" maxlength="50" class="inputbox" />
	        </td>
	    </tr>
	    <tr>
	        <td><label for="zmg_edit_gallery_password">{t}Password{/t}</label></td>
	        <td>
	            <input type="text" name="zmg_edit_gallery_password" id="zmg_edit_gallery_password" value="" size="50" maxlength="50" class="inputbox" />
	        </td>
	    </tr>
	    <tr>
	        <td><label for="zmg_edit_gallery_keywords">{t}Keywords{/t}</label></td>
	        <td valign="middle">
	          <input type="text" name="zmg_edit_gallery_keywords" id="zmg_edit_gallery_keywords" size="50" value="" class="inputbox" />
	        </td>
	    </tr>
	    <tr>
	        <td><label for="zmg_edit_gallery_hidenm">{t}Hide 'No Media' text{/t}</label></td>
	        <td>
	            <input type="checkbox" name="zmg_edit_gallery_hidenm" id="zmg_edit_gallery_hidenm" value="1"/>
	        </td>
	    </tr>
	    <tr>
	        <td><label for="zmg_edit_gallery_published">{t}Published{/t}</label></td>
	        <td>
	            <input type="checkbox" name="zmg_edit_gallery_published" id="zmg_edit_gallery_published" value="1"/>
	        </td>
	    </tr>
	    <tr>
	        <td><label for="zmg_edit_gallery_shared">{t}Shared{/t}</label></td>
	        <td>
	            <input type="checkbox" name="zmg_edit_gallery_shared" id="zmg_edit_gallery_shared" value="1"/>
	        </td>
	    </tr>
	    <tr>
	        <td valign="top"><label for="zmg_edit_gallery_descr">{t}Description{/t}</label></td>
	        <td>
	           <textarea cols="50" rows="5" name="zmg_edit_gallery_descr" id="zmg_edit_gallery_descr" class="inputbox"></textarea>
	        </td>
	    </tr>
	    </table>
	</div>
	<div class="tab-container" rel="permissions">
	    <table border="0" width="400" class="adminform">
        <tr>
            <td valign="top" width="20%">{t}Please select a group that you grant permission to view the contents of this gallery{/t}</td>
            <td>
                {$zmgAPI->callAbstract('zmgHTML', 'groupsACLSelect', $zmgAPI->constructArray(0, 'zmg_edit_gallery_acl_gid'))}
            </td>
        </tr>
        </table>
	</div>
	<!-- 'Actions' tab will move to the toolbar -->
	
	<input type="hidden" name="zmg_edit_gallery_gid" value="" />
</form>

<div id="zmg_gallerymanager_newclick" style="display:none;">
    <a href="javascript:void(0);" onclick="ZMG.EventHandlers.onGalleryNewClick();">
        {t}Click here to create a new gallery{/t}
    </a>
</div>