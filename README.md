## The A-Parser API Client

This client assists in making calls to A-Parser API.

### Requirements

-   PHP version >= 7.4;
-   curl function enabled.

### Usage

```php
<?php

$aparser = new Humanik\Aparser\Client('http://127.0.0.1:9091/API', 'pass');

//List of supported requests
$aparser->ping();
$aparser->info();
$aparser->oneRequest('compact keyboard', 'SE::Google', 'default');
$aparser->bulkRequest(['compact keyboard', 'usb compact keyboard'], 'SE::Google');
$aparser->getParserPreset('SE::Google', 'default');
$aparser->getProxies();
$aparser->changeProxyCheckerState('default', true);

# 1 way
$aparser->addTask('default', 'default', 'text', ['keyboard', 'usb keyboard']);

# 2 way. Advanced
$options = [
    'parsers' => [
        [
            'SE::Google::Position',
            'default'
        ]
    ],
    'resultsFormat'   => '$p1.domain:$p1.key:$p1.position\n',
];
$id = $aparser->addTask('default', '', 'text', ['msn.com microsoft'], $options);

$aparser->getTaskConf($id);
$aparser->getTaskState($id);
$aparser->changeTaskStatus($id, 'deleting');

```

### License

Released under the MIT License.
