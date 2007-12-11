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
            This one should go offscreen...
            <a href="javascript:void(0);" onclick="ZMG.Admin.Events.Client.onlivegrideditslide();">
              Link
            </a>
            <form name="zmg_form_edit_medium" id="zmg_form_edit_medium">

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
                <td>{t}Name{/t}</td>
                <td>
                    <input type="text" name="zmg_edit_name" id="zmg_edit_name" value="" size="50" maxlength="50" class="inputbox" />
                </td>
            </tr>
            <tr>
                <td>{t}Keywords{/t}</td>
                <td valign="middle">
                  <input type="text" name="zmg_edit_keywords" id="zmg_edit_keywords" size="50" value="" class="inputbox" />
                </td>
            </tr>
            <tr>
                <td>{t}Set as Gallery Image{/t}</td>
                <td align="left" valign="top">
                    <input type="checkbox" name="zmg_edit_gimg" id="zmg_edit_gimg" value="1"/>
                </td>
            </tr>
            <tr>
                <td>{t}Set as Gallery Image of PARENT gallery{/t}</td>
                <td>          
                    <input type="checkbox" name="zmg_edit_pimg" id="zmg_edit_pimg" value="1"/>
                </td>
            </tr>
            <tr>
                <td>{t}Published{/t}</td>
                <td>
                    <input type="checkbox" name="zmg_edit_published" id="zmg_edit_published" value="1"/>
                </td>
            </tr>
            <tr>
                <td>{t}Description{/t}</td>
                <td>
                   <textarea cols="50" rows="5" name="zmg_edit_descr" id="zmg_edit_descr" class="inputbox"></textarea>
                </td>
            </tr>
            </table>


            <!-- new tab should start here... 'Members' -->
            <table border="0" width="300">
            <tr>
                <td>
                <!-- List of members put here... -->
                </td>
            </tr>
            </table>
            
            <!-- 'Actions' tab will move to the toolbar -->

		    <input type="hidden" name="mid" value="" />
		    </form>
        </div>
    </div>
</div>
