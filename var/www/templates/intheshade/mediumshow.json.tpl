{literal}
{
{/literal}
    'result': '{$zmgAPI->getParam('result_ok')}',
    'data': {$zmgAPI->getMedium($zmgAPI->getViewToken('last'), 'json')}
{literal}
}
{/literal}
