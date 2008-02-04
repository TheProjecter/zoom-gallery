{literal}
{
    'toolbar': [
        {/literal}
        {if $zmgAPI->getParam('subview') eq 'mediumedit'}
            {literal}
            'mediumedit',
            {
                'id': 'mediumback',
                'title': {/literal}{t escape='json'}Back{/t}{literal},
                'disabled': false
            },
            {
                'id': 'mediumsave',
                'title': {/literal}{t escape='json'}Save{/t}{literal},
                'disabled': false
            }
            {/literal}
        {elseif $zmgAPI->getParam('subview') eq 'zmg_view_mm'}
            {literal}
            'zmg_view_mm',
            {
                'id': 'mm_upload',
                'title': {/literal}{t escape='json'}Upload{/t}{literal},
                'disabled': false
            }
            {/literal}
        {elseif $zmgAPI->getParam('subview') eq 'zmg_view_gm'}
            {literal}
            'zmg_view_gm',
            {
                'id': 'gallerynew',
                'title': {/literal}{t escape='json'}New{/t}{literal},
                'disabled': false
            },
            {
                'id': 'gallerysave',
                'title': {/literal}{t escape='json'}Save{/t}{literal},
                'disabled': true
            },
            {
                'id': 'gallerydelete',
                'title': {/literal}{t escape='json'}Delete{/t}{literal},
                'disabled': true
            }
            {/literal}
        {elseif $zmgAPI->getParam('subview') eq 'zmg_view_settings'}
            {literal}
            'zmg_view_settings',
            {
                'id': 'settingssave',
                'title': {/literal}{t escape='json'}Save{/t}{literal},
                'disabled': false
            }
            {/literal}
        {else}
            'clear'
        {/if}
        {literal}
    ]
}
{/literal}
