{literal}
{
    'toolbar': [
        {/literal}
        {if $zmgAPI->getParam('subview') eq 'mediumedit'}
            {literal}
            'mediumedit',
            {
                'id': 'mediumBack',
                'title': {/literal}{t escape='json'}Back{/t}{literal},
                'disabled': false
            },
            {
                'id': 'mediumSave',
                'title': {/literal}{t escape='json'}Save{/t}{literal},
                'disabled': false
            }
            {/literal}
        {elseif $zmgAPI->getParam('subview') eq 'zmg_view_mm'}
            {literal}
            'zmg_view_mm',
            {
                'id': 'mmUpload',
                'title': {/literal}{t escape='json'}Upload{/t}{literal},
                'disabled': false
            }
            {/literal}
        {elseif $zmgAPI->getParam('subview') eq 'zmg_view_mm_upload'}
            {literal}
            'zmg_view_mm_upload',
            {
                'id': 'mmUploadStart',
                'title': {/literal}{t escape='json'}Start Upload{/t}{literal},
                'disabled': false
            },
            {
                'id': 'mmUploadClear',
                'title': {/literal}{t escape='json'}Clear Completed{/t}{literal},
                'disabled': false
            }
            {/literal}
        {elseif $zmgAPI->getParam('subview') eq 'zmg_view_gm'}
            {literal}
            'zmg_view_gm',
            {
                'id': 'galleryNew',
                'title': {/literal}{t escape='json'}New{/t}{literal},
                'disabled': false
            },
            {
                'id': 'gallerySave',
                'title': {/literal}{t escape='json'}Save{/t}{literal},
                'disabled': true
            },
            {
                'id': 'galleryDelete',
                'title': {/literal}{t escape='json'}Delete{/t}{literal},
                'disabled': true
            }
            {/literal}
        {elseif $zmgAPI->getParam('subview') eq 'zmg_view_settings'}
            {literal}
            'zmg_view_settings',
            {
                'id': 'settingsSave',
                'title': {/literal}{t escape='json'}Save{/t}{literal},
                'disabled': false
            }
            {/literal}
        {else}
            'clear'
        {/if}
        {literal}
    ],
    {/literal}{$zmgAPI->getMessages()}{literal}
}
{/literal}
