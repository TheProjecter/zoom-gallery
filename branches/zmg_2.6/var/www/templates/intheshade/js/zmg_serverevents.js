if (!window.ZMG) window.ZMG = {};
if (!ZMG.Events) ZMG.Events = {};

ZMG.Events.Server = {
    onview: function(text, xml, data, resp) {
        var key, view = data.view, o, isJSON = false;
        console.log('Server#onview: ', view);
        ZMG.Dispatches.lastRequest = null;
        if (view == "admin:gallerymanager") {
            this.ongallerymanager(text);
        }
    },
    ongallerylist: function(text, xml) {
        ZMG.Events.Client.onhideloader();
        
        var o = Json.evaluate(text);

        if (o.result == ZMG.CONST.result_ok) {
            var gallery, el, oGalleries = ZMG.cacheElement('zmg_galleries_body');
            oGalleries.innerHTML = "";

            for (var i in o.data) {
                gallery = o.data[i].gallery;
                el = new Element('div', {
                    'class': 'zmg_gallery',
                    events: {
                        click    : ZMG.Events.Client.ongalleryclick.bindWithEvent(ZMG.Events.Client),
                        //mouseover: this.onMouseEnter.bindWithEvent(this),
                        //mouseout : this.onMouseLeave.bindWithEvent(this)
                        mousedown: function() {this.addClass('zmg_gallery_sel');},
                        mouseup  : function() {this.removeClass('zmg_gallery_sel');}
                    }
                }).setHTML(gallery.name).inject(oGalleries);
            }
        }
    },
    onerror: function() {
        //TODO
    }
};
