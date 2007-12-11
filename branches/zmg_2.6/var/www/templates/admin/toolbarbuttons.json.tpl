{literal}
{
    'toolbar': [
        {/literal}
        {if $subview eq 'mediumedit'}
            {literal}
            'mediumedit',
            {
                'id': 'mediumback',
                'title': '{/literal}{t}Back{/t}{literal}'
            },
            {
                'id': 'mediumsave',
                'title': '{/literal}{t}Save{/t}{literal}'
            }
            {/literal}
        {elseif $subview eq 'zmg_view_mm'}
            {literal}
            'zmg_view_mm',
            {
                'id': 'mm_upload',
                'title': '{/literal}{t}Upload{/t}{literal}'
            }
            {/literal}
        {elseif $subview eq 'zmg_view_settings'}
            {literal}
            'zmg_view_settings',
            {
                'id': 'settingssave',
                'title': '{/literal}{t}Save{/t}{literal}'
            }
            {/literal}
        {else}
            'clear'
        {/if}
        {literal}
    ]
}
{/literal}
