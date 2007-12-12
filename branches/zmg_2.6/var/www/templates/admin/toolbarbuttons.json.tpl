{literal}
{
    'toolbar': [
        {/literal}
        {if $subview eq 'mediumedit'}
            {literal}
            'mediumedit',
            {
                'id': 'mediumback',
                'title': {/literal}{t escape='json'}Back{/t}{literal}
            },
            {
                'id': 'mediumsave',
                'title': {/literal}{t escape='json'}Save{/t}{literal}
            }
            {/literal}
        {elseif $subview eq 'zmg_view_mm'}
            {literal}
            'zmg_view_mm',
            {
                'id': 'mm_upload',
                'title': {/literal}{t escape='json'}Upload{/t}{literal}
            }
            {/literal}
        {elseif $subview eq 'zmg_view_settings'}
            {literal}
            'zmg_view_settings',
            {
                'id': 'settingssave',
                'title': {/literal}{t escape='json'}Save{/t}{literal}
            }
            {/literal}
        {else}
            'clear'
        {/if}
        {literal}
    ]
}
{/literal}
