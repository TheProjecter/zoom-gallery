if (!window.ZMG) window.ZMG = {};

(function() {
    function getObjectId(sId) {
        var aId = sId.split('_');
        return parseInt(aId[aId.length - 1]);
    };
    
    ZMG.EventHandlers = {
        onGalleryClick: function() {
            var iId = getObjectId(this.id);
            console.log('gallery selected: ', iId);
            ZMG.ClientEvents.onCheckLocation();
        },
        onGalleryEnter: function(e) {
            $(this).addClass('zmg_hover');

            var iId = getObjectId(this.id);
            ZMG.ClientEvents.onMediumTooltip(iId, false, new Event(e || window.event));
        },
        onGalleryLeave: function() {
            $(this).removeClass('zmg_hover');

            ZMG.ClientEvents.onCancelMediumTooltip();
        },
        onMediumClick: function() {
            var iId = getObjectId(this.getElementsByTagName('a')[0].id);
            console.log('medium selected: ', iId);
            ZMG.ClientEvents.onCheckLocation();
        },
        onMediumEnter: function(e) {
            $(this).addClass('zmg_hover');

            var iId = getObjectId(this.getElementsByTagName('a')[0].id);
            ZMG.ClientEvents.onMediumTooltip(iId, true, new Event(e || window.event));
        },
        onMediumLeave: function() {
            $(this).removeClass('zmg_hover');

            ZMG.ClientEvents.onCancelMediumTooltip();
        }
    };
})();
