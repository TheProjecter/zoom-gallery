{literal}
{
    'gallerymanager': {
        'text': '{/literal}{t}Gallery Manager{/t}{literal}',
        'id'  : 'admin:gallerymanager',
        'icon': ZMG.CONST.res_path + '/images/sample_icons.gif#2',
        'openicon': '',
        'open': false,
        'load': ZMG.CONST.req_uri + '&view=admin:gallerymanager:getgalleries&sub=0&pos=0'
    },
    'mediamanager': {
        'text': '{/literal}{t}Media Manager{/t}{literal}',
        'id'  : 'admin:mediamanager',
        'icon': '',
        'openicon': '',
        'open': false,
        'load': '',
        'children': {
            'upload' : {
                'text': '{/literal}{t}Upload new media{/t}{literal}',
                'id'  : 'admin:mediamanager:upload',
                'icon': '',
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
        'text': '{/literal}{t}Zoom Thumb coder{/t}{literal}',
        'id'  : 'admin:thumbcoder',
        'icon': '',
        'openicon': '',
        'open': false,
        'load': ''
    },
    'settings': {
        'text': '{/literal}{t}Settings{/t}{literal}',
        'id'  : 'admin:settings:overview',
        'icon': '',
        'openicon': '',
        'open': false,
        'children': {
            'meta' : {
                'text': '{/literal}{t}Metadata{/t}{literal}',
		        'id'  : 'admin:settings:meta',
		        'icon': '',
		        'openicon': '',
		        'open': false,
		        'load': '',
                'extra': {
                    'forcetype': 'html'
                }
            },
            'locale' : {
                'text': '{/literal}{t}Localization{/t}{literal}',
                'id'  : 'admin:settings:locale',
                'icon': '',
                'openicon': '',
                'open': false,
                'load': '',
                'extra': {
                    'forcetype': 'html'
                }
            },
            'filesystem' : {
                'text': '{/literal}{t}Storage{/t}{literal}',
                'id'  : 'admin:settings:filesystem',
                'icon': '',
                'openicon': '',
                'open': false,
                'load': '',
                'extra': {
                    'forcetype': 'html'
                }
            },
            'layout' : {
                'text': '{/literal}{t}Layout{/t}{literal}',
                'id'  : 'admin:settings:layout',
                'icon': '',
                'openicon': '',
                'open': false,
                'load': '',
                'extra': {
                    'forcetype': 'html'
                }
            },
            'app' : {
                'text': '{/literal}{t}Application{/t}{literal}',
                'id'  : 'admin:settings:app',
                'icon': '',
                'openicon': '',
                'open': false,
                'load': '',
                'extra': {
                    'forcetype': 'html'
                }
            },
            'info' : {
                'text': '{/literal}{t}Info{/t}{literal}',
                'id'  : 'admin:settings:info',
                'icon': '',
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