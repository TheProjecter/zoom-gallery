if (!window.ZMG) window.ZMG = {};

ZMG.ClientEvents = (function() {
    function onStart() {
        ZMG.Dispatches.getI18n();
        
        onLoadHome();
    };
    
    function onLoadHome() {
        var params = document.location.search.toQueryParams();
        ZMG.Dispatches.selectView(params['view'] || 'gallery:show:home');
    };
    
    function onShowLoader() {
        ZMG.cacheElement('zmg_loader').setStyle('display', '');
    };
    
    function onHideLoader() {
        ZMG.cacheElement('zmg_loader').setStyle('display', 'none');
    };
    
    function onShowMessage(sTitle, sDescr) {
        //TODO
    };
    
    var oTooltip, activeGallery, initPos;
    var tShowTimer, iThreshold = 1200, iOffset = 8; //milliseconds to wait before tooltip will show

    function buildTooltipContent(oGallery) {
        return ['<span class="zmg_gallery_descr">', oGallery.descr, '</span>\
          <span class="zmg_gallery_mediumcount">', oGallery.medium_count, ' ', _('media'), '</span>'].join('');
    };
    
    function onGalleryTooltip(gId, e) {
        $clear(tShowTimer);
        if (!oTooltip) oTooltip = new ZMG.Tooltip('zmg_gallery_info');

        var oGallery = ZMG.Shared.get('gallery:' + gId);
        if (!oGallery) return;
        
        if (gId === activeGallery) {
            var pos = (e && e.page) ? e.page : initPos;
            oTooltip.setContent(oGallery.name, buildTooltipContent(oGallery))
             .locate(pos.x + iOffset, pos.y + iOffset).show();
        } else {
            //register this event, to for the mouse movement threshold
            initPos = e.page;
            activeGallery = gId;
            tShowTimer = setTimeout(function() { onGalleryTooltip(gId); }, iThreshold);
        }
    };
    
    function onCancelGalleryTooltip() {
        $clear(tShowTimer);
        oTooltip.hide();
    };
    
    //publish methods to the world:
    return {
        onStart: onStart,
        onHideLoader: onHideLoader,
        onShowLoader: onShowLoader,
        onGalleryTooltip: onGalleryTooltip,
        onCancelGalleryTooltip: onCancelGalleryTooltip
    };
})();

/**
 * Add a DOM element to the DOM cache, for easy retrieval throughout
 * the application.
 * @author Mike de Boer (mike AT zoomfactory.org)
 * @param {String} id
 * @param {String} elname Optional.
 * @type DOMElement
 */
ZMG.nodeCache    = [];
ZMG.cacheElement = function(id, elname) {
    if (!this.nodeCache[id] && !this.nodeCache[elname]) {
        var el = $(id);
        if (el) {
            this.nodeCache[elname || id] = el;
            return el;
        } else
            return null;
    }
    return this.nodeCache[elname || id] || null;
};

window.addEvent('domready', function() { ZMG.ClientEvents.onStart(); });