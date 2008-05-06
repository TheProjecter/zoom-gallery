{literal}
{
{/literal}
    'result': '{$zmgAPI->getParam('result_ok')}',
    'data': {literal}{{/literal}
        {$zmgAPI->getMedium($zmgAPI->getViewToken('last'), 'json')}
    {literal}}{/literal}
{literal}
}
{/literal}
