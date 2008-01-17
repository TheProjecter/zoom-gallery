{literal}
{
    'gallerymanager': {
        'text': {/literal}{t escape='json'}Gallery Manager{/t}{literal},
        'id'  : 'admin:gallerymanager',
        'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#1',
        'openicon': '',
        'open': false,
        'load': ZMG.CONST.req_uri + '&view=admin:gallerymanager:getgalleries&sub=0&pos=0',
        'extra': {
            'forcetype': 'html'
        }
    },
    'mediamanager': {
        'text': {/literal}{t escape='json'}Media Manager{/t}{literal},
        'id'  : 'admin:mediamanager',
        'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#4',
        'openicon': '',
        'open': false,
        'load': '',
        'children': {
            'upload' : {
                'text': {/literal}{t escape='json'}Upload new media{/t}{literal},
                'id'  : 'admin:mediamanager:upload',
                'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#5',
                'openicon': '',
                'load': '',
                 'extra': {
                    'forcetype': 'html'
                } 
            }
        },
        'extra': {
            'forcetype': 'html'
        }
    },
    'thumbcoder': {
        'text': {/literal}{t escape='json'}Zoom Thumb coder{/t}{literal},
        'id'  : 'admin:thumbcoder',
        'icon': '',
        'openicon': '',
        'open': false,
        'load': ''
    },
    'settings': {
        'text': {/literal}{t escape='json'}Settings{/t}{literal},
        'id'  : 'admin:settings:overview',
        'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#6',
        'openicon': '',
        'open': false,
        'children': {
            'meta' : {
                'text': {/literal}{t escape='json'}Metadata{/t}{literal},
		        'id'  : 'admin:settings:meta',
		        'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#7',
		        'openicon': '',
		        'open': false,
		        'load': '',
                'extra': {
                    'forcetype': 'html'
                }
            },
            'locale' : {
                'text': {/literal}{t escape='json'}Localization{/t}{literal},
                'id'  : 'admin:settings:locale',
                'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#8',
                'openicon': '',
                'open': false,
                'load': '',
                'extra': {
                    'forcetype': 'html'
                }
            },
            'filesystem' : {
                'text': {/literal}{t escape='json'}Storage{/t}{literal},
                'id'  : 'admin:settings:filesystem',
                'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#9',
                'openicon': '',
                'open': false,
                'load': '',
                'extra': {
                    'forcetype': 'html'
                }
            },
            'layout' : {
                'text': {/literal}{t escape='json'}Layout{/t}{literal},
                'id'  : 'admin:settings:layout',
                'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#10',
                'openicon': '',
                'open': false,
                'load': '',
                'extra': {
                    'forcetype': 'html'
                }
            },
            'app' : {
                'text': {/literal}{t escape='json'}Application{/t}{literal},
                'id'  : 'admin:settings:app',
                'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#11',
                'openicon': '',
                'open': false,
                'load': '',
                'extra': {
                    'forcetype': 'html'
                }
            },
            'plugins' : {
                'text': {/literal}{t escape='json'}Plugins{/t}{literal},
                'id'  : 'admin:settings:plugins',
                'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#12',
                'openicon': '',
                'open': false,
                'load': '',
                'extra': {
                    'forcetype': 'html'
                }
            },
            'info' : {
                'text': {/literal}{t escape='json'}Info{/t}{literal},
                'id'  : 'admin:settings:info',
                'icon': ZMG.CONST.res_path + '/images/admin_treeitems.gif#13',
                'openicon': '',
                'open': false,
                'load': '',
                'extra': {
                    'forcetype': 'html'
                }
            }
        }
    }
}
{/literal}
