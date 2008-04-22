<center>
    <div class="zmg_greyblock">
        {t}Filter{/t}: {$zmgAPI->callAbstract('zmgHTML', 'galleriesSelect', 'ZMG.ClientEvents.onMmGalleryChange(this);')}
    </div>
</center>
<br/>
<div id="zmg_mm_lgrid" class="lgrid">
    <div class="lgrid-toolbar">
    <table id="zmg_lgrid_pagination" class="lgrid-pagination">
        <tr>
            <td class="lgrid-nav-btn">
                <div class="lgrid-nav-btn-first">&nbsp;</div>
            </td>
            <td class="lgrid-nav-btn">
                <div class="lgrid-nav-btn-prev">&nbsp;</div>
            </td>
            <td class="lgrid-nav-spacer">&nbsp;</div>
            <td class="lgrid-nav-pager">
                Page <input type="text" size="3" value="1" id="lgrid-nav-pager"/>
            </td>
            <td class="lgrid-nav-spacer">&nbsp;</div>
            <td class="lgrid-nav-btn">
                <div class="lgrid-nav-btn-next">&nbsp;</div>
            </td>
            <td class="lgrid-nav-btn">
                <div class="lgrid-nav-btn-last">&nbsp;</div>
            </td>
        </tr>
    </table>
    <div class="lgrid-nav"><span></span><span></span></div>
    </div>
    <div class="lgrid-scroller">
        <div class="lgrid-body"></div>
        <div class="lgrid-panel-edit">
            <center>
                <div class="zmg_greyblock">
                    <img id="zmg_edit_medium_thumbnail" src="" alt="{t}Thumbnail{/t}" title="{t}Thumbnail{/t}" border="0"/>
                </div>
            </center>
            
            <form name="zmg_form_edit_medium" id="zmg_form_edit_medium">

            <div id="zmg_edit_medium_tabs" class="tab-all-container">
                <div class="tab-strip-wrapper">
                    <ul class="tab-strip">
                        <li><a rel="properties">{t}Properties{/t}</a></li>
                        <li><a rel="permissions">{t}Permissions{/t}</a></li>
                        <li><a rel="comments">{t}Comments{/t}</a></li>
                    </ul>
                </div>
                <div class="tab-wrapper"></div>
            </div> <!-- END div 'zmg_edit_medium_tabs' -->
            
            <!-- Content for each tab (look at the 'rel' property): -->
            <div class="tab-container" rel="properties">
                <table border="0" width="400" class="adminform">
                <tr>
                    <td width="30%">{t}Filename{/t}</td>
                    <td>
                      <strong>
                      <span class="zmg_smallheader" id="zmg_edit_filename"></span>
                      </strong>
                    </td>
                </tr>
                <tr>
                    <td><label for="zmg_edit_name">{t}Name{/t}</label></td>
                    <td>
                        <input type="text" name="zmg_edit_name" id="zmg_edit_name" value="" size="50" maxlength="50" class="inputbox" />
                    </td>
                </tr>
                <tr>
                    <td><label for="zmg_edit_keywords">{t}Keywords{/t}</label></td>
                    <td valign="middle">
                      <input type="text" name="zmg_edit_keywords" id="zmg_edit_keywords" size="50" value="" class="inputbox" />
                    </td>
                </tr>
                <tr>
                    <td><label for="zmg_edit_gimg">{t}Set as Gallery Image{/t}</label></td>
                    <td align="left" valign="top">
                        <input type="checkbox" name="zmg_edit_gimg" id="zmg_edit_gimg" value="1"/>
                    </td>
                </tr>
                <tr>
                    <td><label for="zmg_edit_pimg">{t}Set as Gallery Image of PARENT gallery{/t}</label></td>
                    <td>          
                        <input type="checkbox" name="zmg_edit_pimg" id="zmg_edit_pimg" value="1"/>
                    </td>
                </tr>
                <tr>
                    <td><label for="zmg_edit_published">{t}Published{/t}</label></td>
                    <td>
                        <input type="checkbox" name="zmg_edit_published" id="zmg_edit_published" value="1"/>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><label for="zmg_edit_descr">{t}Description{/t}</label></td>
                    <td>
                       <textarea cols="50" rows="5" name="zmg_edit_descr" id="zmg_edit_descr" class="inputbox"></textarea>
                    </td>
                </tr>
                </table>
            </div>
            <div class="tab-container" rel="permissions">
                <!-- new tab should start here... 'Permissions' -->
                <table border="0" width="400" class="adminform">
                <tr>
                    <td valign="top" width="20%">{t}Please select a group that you grant permission to view this medium{/t}</td>
                    <td>
                        {$zmgAPI->callAbstract('zmgHTML', 'groupsACLSelect', $zmgAPI->constructArray(0, 'zmg_edit_acl_gid'))}
                    </td>
                </tr>
                </table>
            </div>
            <div class="tab-container" rel="comments">
                <!-- new tab should start here... 'Comments' -->
                <table border="0" width="300">
                <tr>
                    <td>
                    <!-- List of comments put here... -->
                    </td>
                </tr>
                </table>
            </div>
            <!-- 'Actions' tab will move to the toolbar -->

		    <input type="hidden" name="zmg_edit_mid" value="" />
		    </form>
        </div>
    </div>
</div>
