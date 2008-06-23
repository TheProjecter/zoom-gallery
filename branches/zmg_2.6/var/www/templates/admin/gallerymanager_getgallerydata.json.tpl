{literal}
{
    'result': '{/literal}{$zmgAPI->getParam('result_ok')}{literal}',
    'data': {
        {/literal}{$zmgAPI->getGallery($zmgAPI->getParam('subview'), 'json')}{literal}
    },
    {/literal}{$zmgAPI->getMessages()}{literal}
}
{/literal}