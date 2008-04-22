if (!window.ZMG) window.ZMG = {};

(function() {
    ZMG.EventHandlers = {
        onPinMouseEnter: function(e) {
            this.addClass('zmg_tool_pinned_hover');
        },
        
        onPinMouseLeave: function(e) {
            this.removeClass('zmg_tool_pinned_hover');
        },
        
        onPinMouseClick: function(e) {
            //TODO
            alert('unpin!');
        },
        
        onMmUploadClick: function(e) {
            ZMG.ClientEvents.onViewSelect('admin:mediamanager:upload', 'html');
        },
        
        onSettingsSaveClick: function(e) {
            ZMG.Dispatches.saveSettings(FormSerializer.serialize($('zmg_settings_form')));
        },
        
        onMediumBackClick: function(e) {
            var oToolbar = ZMG.Shared.get('toolbar');
            if (oToolbar) oToolbar.clear();
            
            ZMG.ClientEvents.onLiveGridEditSlide();
        },
        
        onMediumSaveClick: function(e) {
            ZMG.Dispatches.saveMedium(FormSerializer.serialize($('zmg_form_edit_medium')));
        },
        
        onMediumCorrectPanel: function() {
            var livegrid = ZMG.Shared.get('liveGrid');
            var oScrollerSize = livegrid.scroller.getSize().size;
            var oEditSize = livegrid.options.editpanel.getSize().size;
            if (oScrollerSize.y < (oEditSize.y + 16))
                livegrid.scroller.style.height = (oEditSize.y + 16) + "px";
        },
        
        onMmUploadStartClick: function() {
            if (!ZMG.ClientEvents.getActiveFilter()) {
                return ZMG.ClientEvents.onShowMessage('Medium Upload',
                  'You must select a gallery before you can upload media!'); //no gallery selected!
            }
            
            var oUploader = ZMG.Shared.get('uploader');
            if (oUploader) oUploader.upload();
        },
        
        onMmUploadClearClick: function() {
            var oUploader = ZMG.Shared.get('uploader');
            if (oUploader) oUploader.clearList(false);
        },
        
        onMmUploadCompleted: function() {
            ZMG.Dispatches.saveMedia('fancyupload', FormSerializer.serialize($('zmg_fancyupload_data')));
        },
        
        onGalleryNewClick: function(e) {
            ZMG.ClientEvents.onViewSelect('admin:gallerymanager:get:new');
        },
        
        onGallerySaveClick: function(e) {
            ZMG.Dispatches.saveGallery(FormSerializer.serialize($('zmg_form_edit_gallery')));
        },
        
        onGalleryDeleteClick: function(e) {
            ZMG.Dispatches.deleteGallery($('zmg_edit_gallery_gid').value);
        }
    };
})();
