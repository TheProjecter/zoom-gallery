ZMG.GUI = (function() {
    
    return {
    
    buildTooltipContent: function(obj, bIsMedium) {
        if (!bIsMedium)
            return ['<span class="zmg_gallery_descr">', obj.descr, '</span>\
              <span class="zmg_gallery_mediumcount">', obj.medium_count, ' ', _('media'), '</span>'].join('');

        return ['<span class="zmg_medium_descr">', obj.descr, '</span>'].join('');
    },
    
    buildGalleryDiv: function(gallery) {
        if (!gallery.cover_img)
            gallery.cover_img = ZMG.CONST.res_path + "/images/mimetypes/small/unknown.png";
        return ['<a href="#gallery:show:', gallery.gid, '" id="zmg_gallery_', gallery.gid, '" class="zmg_gallery">\
          <img src="', gallery.cover_img, '" alt="" title=""/>\
          <span class="zmg_gallery_name">',
            gallery.name,
          '</span>\
          <span class="zmg_gallery_descr">',
            gallery.descr,
          '</span>\
        </a>'].join('');
    },
    
    buildMediumDiv: function(medium) {
        return ['<div class="zmg_medium_thumb_cont">\
          <a href="', medium.url_view, '" id="zmg_medium_', medium.mid, '" class="zmg_medium_thumb" title="', medium.name,'">\
              <img src="', medium.url_thumb, '" alt="" title=""/>\
          </a>\
          <span class="zmg_medium_name">',
            medium.name,
          '</span>\
          <span class="zmg_medium_hits">',
            medium.hits, ' ', ZMG.CONST.i18n.hits,
          '</span>\
        </div>'].join('');
    }
    
    };
    
})();