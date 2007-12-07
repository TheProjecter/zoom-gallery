{literal}
{
    {/literal}
    {if $subview eq 'overview'}
        {literal}
        'tabs': {
            'meta': {
                'name' : '{/literal}{t}Metadata{/t}{literal}',
                'title': '{/literal}{t}View and change metadata settings{/t}{literal}',
                'url'  : '',
                'data' : ['admin:settings:meta', 'html']
            },
            'locale': {
                'name' : '{/literal}{t}Localization{/t}{literal}',
                'title': '{/literal}{t}View and change locale settings{/t}{literal}',
                'url'  : '',
                'data' : ['admin:settings:locale', 'html']
            },
            'filesystem': {
                'name' : '{/literal}{t}Storage{/t}{literal}',
                'title': '{/literal}{t}View and change storage settings{/t}{literal}',
                'url'  : '',
                'data' : ['admin:settings:filesystem', 'html']
            },
            'layout': {
                'name' : '{/literal}{t}Layout{/t}{literal}',
                'title': '{/literal}{t}View and change layout settings{/t}{literal}',
                'url'  : '',
                'data' : ['admin:settings:layout', 'html']
            },
            'app': {
                'name' : '{/literal}{t}Application{/t}{literal}',
                'title': '{/literal}{t}View and change application settings{/t}{literal}',
                'url'  : '',
                'data' : ['admin:settings:app', 'html']
            },
            'info': {
                'name' : '{/literal}{t}Info{/t}{literal}',
                'title': '{/literal}{t}View info about Zoom Media Gallery{/t}{literal}',
                'url'  : '',
                'data' : ['admin:settings:info', 'html']
            }
        }
        {/literal}
    {/if}
    {literal}
}
{/literal}