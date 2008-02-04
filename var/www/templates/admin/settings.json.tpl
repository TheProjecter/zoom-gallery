{literal}
{
    {/literal}
    {if $zmgAPI->getParam('subview') eq 'overview'}
        {literal}
        'tabs': {
            'meta': {
                'name' : {/literal}{t escape='json'}Metadata{/t}{literal},
                'title': {/literal}{t escape='json'}View and change metadata settings{/t}{literal},
                'url'  : '',
                'data' : ['admin:settings:meta', 'html']
            },
            'locale': {
                'name' : {/literal}{t escape='json'}Localization{/t}{literal},
                'title': {/literal}{t escape='json'}View and change locale settings{/t}{literal},
                'url'  : '',
                'data' : ['admin:settings:locale', 'html']
            },
            'filesystem': {
                'name' : {/literal}{t escape='json'}Storage{/t}{literal},
                'title': {/literal}{t escape='json'}View and change storage settings{/t}{literal},
                'url'  : '',
                'data' : ['admin:settings:filesystem', 'html']
            },
            'layout': {
                'name' : {/literal}{t escape='json'}Layout{/t}{literal},
                'title': {/literal}{t escape='json'}View and change layout settings{/t}{literal},
                'url'  : '',
                'data' : ['admin:settings:layout', 'html']
            },
            'app': {
                'name' : {/literal}{t escape='json'}Application{/t}{literal},
                'title': {/literal}{t escape='json'}View and change application settings{/t}{literal},
                'url'  : '',
                'data' : ['admin:settings:app', 'html']
            },
            'plugins': {
                'name' : {/literal}{t escape='json'}Plugins{/t}{literal},
                'title': {/literal}{t escape='json'}View and change plugin settings{/t}{literal},
                'url'  : '',
                'data' : ['admin:settings:plugins', 'html']
            },
            'info': {
                'name' : {/literal}{t escape='json'}Info{/t}{literal},
                'title': {/literal}{t escape='json'}View info about Zoom Media Gallery{/t}{literal},
                'url'  : '',
                'data' : ['admin:settings:info', 'html']
            }
        }
        {/literal}
    {/if}
    {literal}
}
{/literal}
