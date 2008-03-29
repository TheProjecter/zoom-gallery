if (!window.ZMG) window.ZMG = {};

ZMG.ClientEvents = (function() {
    function onStart() {
        onLoadMedia();
    }
    
    function onLoadMedia() {
        //ZMG.Dispatches.getMedia();
        var params = document.location.search.toQueryParams();
        ZMG.Dispatches.selectView(params['view'] || 'gallery:show:home');
    }
    
    function onShowLoader() {
        ZMG.cacheElement('zmg_loader').setStyle('display', '');
    }
    
    function onHideLoader() {
        ZMG.cacheElement('zmg_loader').setStyle('display', 'none');
    }
    
    function onShowMessage(sTitle, sDescr) {
        //TODO
    }
    
    //publish methods to the world:
    return {
        onStart: onStart,
        onHideLoader: onHideLoader,
        onShowLoader: onShowLoader
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