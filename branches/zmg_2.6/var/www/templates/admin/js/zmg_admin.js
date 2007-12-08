if (!window.ZMG) window.ZMG = {};

ZMG.Admin = {
    nodeCache    : {},
    menuTree     : null,
    Events       : null,
    
    start: function() {
        ZMG.Admin.cacheElement('zmg_admin_cont');
        ZMG.Admin.cacheElement('zmg_admin_loader');
        ZMG.Admin.cacheElement('zmg_menu_tree');
        ZMG.Admin.cacheElement('zmg_view_content');
        ZMG.Admin.Events = new ZMG.Events();
        //set correct dimensions of the admin content
        ZMG.Admin.Events.Client.onwindowresize();
        //load the tree
        ZMG.Admin.Events.Client.onloadnavigation();
        //set the initial view
        ZMG.Admin.Events.Client.onviewselect();
    },
    
    /**
     * Add a DOM element to the DOM cache, for easy retrieval throughout
     * the application.
     * @author Mike de Boer (mike AT zoomfactory.org)
     * @param {String} id
     * @param {String} elname Optional.
     * @type DOMElement
     */
    cacheElement :
    function(id, elname) {
        if (!this.nodeCache[id] && !this.nodeCache[elname]) {
            var el = $(id);
            if (el) {
                this.nodeCache[elname || id] = el;
                return el;
            } else
                return null;
        }
        return this.nodeCache[elname || id] || null;
    }
};

window.addEvent('domready', ZMG.Admin.start); 