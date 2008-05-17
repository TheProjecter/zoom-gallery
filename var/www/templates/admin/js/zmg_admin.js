if (!window.ZMG) window.ZMG = {};

ZMG.Admin = {
    menuTree     : null,
    Events       : null,
    
    start: function() {
        ZMG.GUI.init();

        ZMG.ClientEvents.onStart();
    }
};

window.addEvent('domready', ZMG.Admin.start);
