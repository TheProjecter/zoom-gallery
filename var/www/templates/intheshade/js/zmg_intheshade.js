if (!window.ZMG) window.ZMG = {};
if (!ZMG.Events) ZMG.Events = {};

ZMG.Events.Client = {
    onstart: function() {
        this.onloadgallerylist();
    },
    onloadgallerylist: function(pos, parentGid) {
        if (parseInt(pos) > 0) {
            //we need to process the parentGid
        } else
            pos = parentGid = 0;
        ZMG.Dispatches.getGalleries(pos, parentGid);
    },
    ongalleryclick: function(e) {
        
    },
    onshowloader: function() {
        ZMG.cacheElement('zmg_loader').setStyle('display', '');
    },
    onhideloader: function() {
        ZMG.cacheElement('zmg_loader').setStyle('display', 'none');
    }
};

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

window.addEvent('domready', ZMG.Events.Client.onstart.bind(ZMG.Events.Client));