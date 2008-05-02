if (!window.ZMG) window.ZMG = {};

(function() {
    ZMG.EventHandlers = {
        onGalleryClick: function() {
            var aId = this.id.split('_');
            var iId = parseInt(aId[aId.length - 1]);
            console.log('gallery selected: ', iId);
        },
        onGalleryEnter: function() {
            $(this).addClass('zmg_hover');
        },
        onGalleryLeave: function() {
            $(this).removeClass('zmg_hover');
        }
    };
})();
