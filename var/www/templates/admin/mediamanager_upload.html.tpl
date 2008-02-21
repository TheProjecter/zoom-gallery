<div id="zmg_upload_tabs" class="tab-all-container">
    <div class="tab-strip-wrapper">
        <ul class="tab-strip">
            <li><a rel="standard">{t}Standard{/t}</a></li>
            <li><a rel="dragndrop">{t}Drag 'n Drop{/t}</a></li>
        </ul>
    </div>
    <div class="tab-wrapper"></div>
</div> <!-- END div 'zmg_upload_tabs' -->

<!-- Content for each tab (look at the 'rel' property): -->
<div class="tab-container" rel="standard">
	<label for="zmg_fancyupload_filedata" class="zmg_greyblock">
	    {t}Upload Media{/t}:
	    <span>{t}After selecting the media, start the upload.{/t}</span>
	</label>
	<form action="" method="post" id="zmg_fancyupload_gid">
        <div class="zmg_halfsize">
            <fieldset>
                <legend>{t}Select a gallery{/t}</legend>
                {$zmgAPI->callAbstract('zmgHTML', 'galleriesSelect', 'ZMG.ClientEvents.onMmGalleryChange(this);')}
            </fieldset>
        </div>
    </form>
	<form action="" method="post" id="zmg_fancyupload" enctype="multipart/form-data">
	    <div class="zmg_halfsize">
	        <fieldset>
	            <legend>{t}Select Files{/t}</legend>
	            <input type="file" name="Filedata" id="zmg_fancyupload_filedata" />
	            <ul class="zmg_fancyupload_queue" id="zmg_fancyupload_queue">
	                <li style="display: none" />
	            </ul>
	        </fieldset>
	    </div>
	</form>
	<form action="" method="post" id="zmg_fancyupload_data">
	   <div class="zmg_halfsize">
            <fieldset>
                <legend>{t}Enter Name & Description{/t}</legend>
                <input type="text" name="zmg_upload_name" value="{$zmgAPI->getConfig('filesystem/upload/tempname')}" size="50" />
                <br /><br />
                <textarea name="zmg_upload_descr" rows="5" cols="42">{$zmgAPI->getConfig('filesystem/upload/tempdescr')}</textarea>
            </fieldset>
        </div>
    
        <div class="zmg_clear"></div>
    </form>
</div>

<div class="tab-container" rel="dragndrop">
    <applet 
      title="JUpload"
      name="JUpload"
      code="com.smartwerkz.jupload.classic.JUpload"
      codebase="."
      archive="{$zmgAPI->getParam('site_url')}/components/com_zoom/var/www/templates/admin/other/jupload/JUpload.jar,
               {$zmgAPI->getParam('site_url')}/components/com_zoom/var/www/templates/admin/other/jupload/commons-codec-1.3.jar,
               {$zmgAPI->getParam('site_url')}/components/com_zoom/var/www/templates/admin/other/jupload/commons-httpclient-3.0-rc4.jar,
               {$zmgAPI->getParam('site_url')}/components/com_zoom/var/www/templates/admin/other/jupload/commons-logging.jar,
               {$zmgAPI->getParam('site_url')}/components/com_zoom/var/www/templates/admin/other/jupload/skinlf/skinlf-6.2.jar"
      width="640"
      height="480"
      mayscript="mayscript"
      alt="JUpload by www.jupload.biz">
      <param name="Config" value="{$zmgAPI->getParam('rpc_url')}&amp;view=admin:mediamanager:juploadconfig&amp;forcetype=plain">
    </applet>
</div>