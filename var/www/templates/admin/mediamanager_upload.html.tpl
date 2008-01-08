<label for="zmg_fancyupload_filedata" class="zmg_greyblock">
    {t}Upload Media{/t}:
    <span>{t}After selecting the media, start the upload.{/t}</span>
</label>
<form action="" method="post" id="zmg_fancyupload" enctype="multipart/form-data">
    <div class="zmg_halfsize">
        <fieldset>
            <legend>{t}Select a gallery{/t}</legend>
	        {$zoom->callAbstract('zmgHTML', 'galleriesSelect', 'ZMG.Admin.Events.Client.onmm_gallerychange(this);')}
        </fieldset>
    </div>
    <div class="zmg_halfsize">
        <fieldset>
            <legend>{t}Select Files{/t}</legend>
            <input type="file" class="zmg_button" name="Filedata" id="zmg_fancyupload_filedata" />
            <ul class="zmg_fancyupload_queue" id="zmg_fancyupload_queue">
                <li style="display: none" />
            </ul>
        </fieldset>
    </div>
    <div class="zmg_halfsize">
        <fieldset>
            <legend>{t}Enter Name & Description{/t}</legend>
            <input type="text" name="zmg_upload_name" value="{$zoom->getConfig('filesystem/upload/tempname')}" size="50" />
            <br /><br />
            <textarea name="zmg_upload_descr" rows="5" cols="42">{$zoom->getConfig('filesystem/upload/tempdescr')}</textarea>
        </fieldset>
    </div>

    <div class="zmg_clear"></div>

    <div class="zmg_fancyupload_btn_cont">
        <input type="button" class="zmg_button" id="zmg_fancyupload_clear" value="{t}Clear Completed{/t}"/>
        <input type="submit" class="zmg_button" id="zmg_fancyupload_submit" value="{t}Start Upload{/t}"/>
    </div>
</form>