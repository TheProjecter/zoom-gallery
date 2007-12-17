<form action="/playground/server/upload.php" method="post" id="zmg_fancyupload" enctype="multipart/form-data">
    <div class="zmg_halfsize">
        <fieldset>
            <legend>{t}Select Files{/t}</legend>

            <div class="label emph">
                <label for="zmg_fancyupload_filedata">
                    {t}Upload Media{/t}:
                    <span>{t}After selecting the media, start the upload.{/t}</span>

                </label>
                <input type="file" class="zmg_button" name="Filedata" id="zmg_fancyupload_filedata" />
            </div>

        </fieldset>
    </div>
    <div class="zmg_halfsize">
        <fieldset>
            <legend>{t}Upload Queue{/t}</legend>

            <ul class="zmg_fancyupload_queue" id="zmg_fancyupload_queue">
                <li style="display: none" />
            </ul>
        </fieldset>
    </div>

    <div class="zmg_clear"></div>

    <div class="zmg_fancyupload_btn_cont">
        <input type="button" class="zmg_button" id="zmg_fancyupload_clear" value="{t}Clear Completed{/t}"/>
        <input type="submit" class="zmg_button" id="zmg_fancyupload_submit" value="{t}Start Upload{/t}"/>
    </div>
</form>