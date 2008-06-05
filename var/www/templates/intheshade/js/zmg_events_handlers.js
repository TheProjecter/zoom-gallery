if (!window.ZMG) window.ZMG = {};

(function() {
    ZMG.getObjectId = function(sId) {
        var aId = sId.split('_');
        return parseInt(aId[aId.length - 1]);
    };
    
    ZMG.EventHandlers = {
        onGalleryClick: function() {
            ZMG.ClientEvents.onCheckLocation();
        },
        onGalleryEnter: function(e) {
            $(this).addClass('zmg_hover');

            var iId = ZMG.getObjectId(this.id);
            ZMG.ClientEvents.onMediumTooltip(iId, false, new Event(e || window.event));
        },
        onGalleryLeave: function() {
            $(this).removeClass('zmg_hover');

            ZMG.ClientEvents.onCancelMediumTooltip();
        },
        onMediumClick: function() {
            var iId = ZMG.getObjectId(this.getElementsByTagName('a')[0].id);
            console.log('medium selected: ', iId);
            if (o.result !== ZMG.CONST.result_ok) return;
            
            var medium = o.data.medium;
            var gallery = ZMG.Shared.get('gallery:' + medium.gid);
            console.log('lala', medium.url_view);
            Shadowbox.open({
                title:      medium.name,
                zmgobj:     medium,
                //type:       'img',
                content:    medium.url_view,
                gallery:    gallery ? gallery.name : null
            });
        },
        onMediumEnter: function(e) {
            $(this).addClass('zmg_hover');

            var iId = ZMG.getObjectId(this.getElementsByTagName('a')[0].id);
            ZMG.ClientEvents.onMediumTooltip(iId, true, new Event(e || window.event));
        },
        onMediumLeave: function() {
            $(this).removeClass('zmg_hover');

            ZMG.ClientEvents.onCancelMediumTooltip();
        }
    };
})();
