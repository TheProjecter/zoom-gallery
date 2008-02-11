if (!window.ZMG) window.ZMG = {};

ZMG.Shared = {
    register: function(name, value) {
        if (!name) return;
        
        this[name] = value;
    },
    
    get: function(name) {
        return this[name] || null;
    },
    
    nodeCache: {},
    /**
     * Add a DOM element to the DOM cache, for easy retrieval throughout
     * the application.
     * @author Mike de Boer (mike AT zoomfactory.org)
     * @param {String} id
     * @param {String} elname Optional.
     * @type DOMElement
     */
    cacheElement: function(id, elname) {
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