{literal}
{
    'result': '{/literal}{$zmgAPI->getParam('result_ok')}{literal}',
    'data': {
        {/literal}{$zmgAPI->getMedium($zmgAPI->getParam('subview'), 'json')}{literal}
    }
}
{/literal}
