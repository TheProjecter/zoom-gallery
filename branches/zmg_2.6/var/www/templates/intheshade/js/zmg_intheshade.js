if (!window.ZMG) window.ZMG = {};

ZMG.ClientEvents = (function() {
    function onStart() {
        ZMG.Dispatches.getI18n();
    };
    
    function onI18nAvailable() {
        onCheckLocation();
        Shadowbox.init({
            skipSetup: true,
            assetURL: ZMG.CONST.res_path + "/../../shared/",
            audioPlayerFile: ZMG.CONST.res_path + "/../../shared/redirect.php?q=" /*,
            text: {
                cancel:  _('cancel'),
                loading: _('loading'),
                close:   _('close'),
                next:    _('next'),
                prev:    _('previous')
            }*/
        });
    };
    
    var tPollTimer = null, iPollTimeout = 1000;
    function onCheckLocation() {
        $clear(tPollTimer);

        if (!document.location.hash)
            window.location.hash = "gallery:show:home";

        var hash = document.location.hash.replace(/#/, '');
        if (hash != ZMG.CONST.active_view)
            ZMG.Dispatches.selectView(hash);

        window.setTimeout(onCheckLocation, iPollTimeout);
    };
    
    function onShowLoader() {
        ZMG.Shared.cacheElement('zmg_loader').setStyle('display', '');
    };
    
    function onHideLoader() {
        ZMG.Shared.cacheElement('zmg_loader').setStyle('display', 'none');
    };
    
    function onShowMessage(sTitle, sDescr) {
        //TODO
        console.log('onShowMessage: ', sTitle, sDescr);
    };
    
    var oTooltip, activeMedium, initPos;
    var tShowTimer, iThreshold = 1200, iOffset = 8; //milliseconds to wait before tooltip will show
    
    function onMediumTooltip(iId, bIsMedium, e) {
        $clear(tShowTimer);
        bIsMedium = (bIsMedium) ? true : false;
        
        if (!oTooltip) oTooltip = new ZMG.Tooltip('zmg_medium_info');

        var obj = bIsMedium ? ZMG.Shared.get('medium:' + iId) : ZMG.Shared.get('gallery:' + iId);
        if (!obj) return;
        
        if (iId === activeMedium) {
            var pos = (e && e.page) ? e.page : initPos;
            oTooltip.locate(pos.x + iOffset, pos.y + iOffset).show();
        } else {
            //register this event, to for the mouse movement threshold
            initPos = e.page;
            activeMedium = iId;
            oTooltip.setContent(obj.name, ZMG.GUI.buildTooltipContent(obj, bIsMedium));
            tShowTimer = setTimeout(function() { onMediumTooltip(iId); }, iThreshold);
        }
    };
    
    function onCancelMediumTooltip() {
        $clear(tShowTimer);
        oTooltip.hide();
    };
    
    var sActiveView = null;
    
    function onActivateView(el) {
        if (!ZMG.Shared.cacheElement(el)) return;
        var oParent = ZMG.Shared.cacheElement('zmg_view_content');
        for (var i = 0; i < oParent.childNodes.length; i++)
            if (oParent.childNodes[i].nodeType == 1)
                $(oParent.childNodes[i]).setStyle('display', 'none');

        sActiveView = el;
        var oActiveView = ZMG.Shared.cacheElement(el);
        oActiveView.setStyle('display', '');
        ZMG.Shared.register('activeView', oActiveView);
        
        return oActiveView;
    };
    
    //publish methods to the world:
    return {
        onStart: onStart,
        onI18nAvailable: onI18nAvailable,
        onCheckLocation: onCheckLocation,
        onHideLoader: onHideLoader,
        onShowLoader: onShowLoader,
        onMediumTooltip: onMediumTooltip,
        onCancelMediumTooltip: onCancelMediumTooltip,
        onActivateView: onActivateView
    };
})();

window.addEvent('domready', function() { ZMG.ClientEvents.onStart(); });