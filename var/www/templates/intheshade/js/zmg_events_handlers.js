if (!window.ZMG) window.ZMG = {};

(function() {
    function getGalleryId(sId) {
        var aId = sId.split('_');
        return parseInt(aId[aId.length - 1]);
    };
    
    ZMG.EventHandlers = {
        onGalleryClick: function() {
            var iId = getGalleryId(this.id);
            console.log('gallery selected: ', iId);
        },
        onGalleryEnter: function(e) {
            $(this).addClass('zmg_hover');

            var iId = getGalleryId(this.id);
            ZMG.ClientEvents.onGalleryTooltip(iId, new Event(e || window.event));
        },
        onGalleryLeave: function() {
            $(this).removeClass('zmg_hover');

            ZMG.ClientEvents.onCancelGalleryTooltip();
        }
    };
})();
