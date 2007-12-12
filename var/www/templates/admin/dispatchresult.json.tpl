{literal}
{
    'action': '{/literal}{$subview}{literal}',
    'result': '{/literal}{$zoom->getResult()}{literal}',
    'messages': {
        'title': {/literal}
            {if $subview eq 'settings_store'}
                {t escape='json'}Settings{/t}
            {elseif $subview eq 'medium_store'}
                {t escape='json'}Media Manager{/t}
            {elseif $subview eq 'gallery_store'}
                {t escape='json'}Gallery Manager{/t}
            {/if}
        {literal},
        'ok': {/literal}
            {if $subview eq 'settings_store'}
                {t escape='json'}Your settings have been saved successfully.{/t}
            {elseif $subview eq 'medium_store'}
                {t escape='json'}Medium properties have been saved.{/t}
            {elseif $subview eq 'gallery_store'}
                {t escape='json'}Gallery properties have been saved.{/t}
            {/if}
        {literal},
        'ko': {/literal}
            {if $subview eq 'settings_store'}
                {t escape='json'}Your settings could not be saved.{/t}
            {elseif $subview eq 'medium_store'}
                {t escape='json'}Medium properties could not be saved.{/t}
            {elseif $subview eq 'gallery_store'}
                {t escape='json'}Gallery properties could not be saved.{/t}
            {/if}
        {literal}
    }
}
{/literal}
