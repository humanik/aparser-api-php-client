## The A-Parser API Client ##

This client assists in making calls to A-Parser API.


### Requirements ###
 
- PHP version >= 5.3.0;
- curl function enabled. 

### Usage ###

```php
<?php
require_once 'aparser-api-php-client.php';

$aparser = new Aparser('http://127.0.0.1:9091/API', 'pass', array('debug'=>'true'));

//List of supported requests
$aparser->ping();
$aparser->info();
$aparser->oneRequest('compact keyboard', 'SE::Google', 'default');
$aparser->bulkRequest(
    array('compact keyboard','usb compact keyboard'),
    'SE::Google'
);
$aparser->getParserPreset('SE::Google', 'default');
$aparser->getProxies();
$aparser->setProxyCheckerPreset();

# 1 way
$aparser->addTask('default', 'default', 'text', array('keyboard','usb keyboard'));

# 2 way. Advanced
$options = array(
    'parsers' => array(
        array(
            'SE::Google::Position',
            'default'
        )
    ),
    'resultsFormat'   => "$p1.domain:$p1.key:$p1.position\n)",

/*  #Default values
    'resultsFileName' => '$datefile.format().txt',
    'uniqueQueries'   => 0,
    'keepUnique'      => 0,
    'resultsPrepend'  => '',
    'moreOptions'     => 0,
    'resultsUnique'   => 'no',
    'doLog'           => 'no',
    'queryFormat'     => '$query',
    'resultsSaveTo'   => 'file',
    'configOverrides' => array(),
    'resultsAppend'   => '',
    'queryBuilders'   => array(),
    'resultsBuilders' => array(),
// */
);
$taskUid = $aparser->addTask('default', FALSE, 'text', array('msn.com microsoft'), $options);

$aparser->getTaskState($taskUid);
$aparser->getTaskConf($taskUid);
$aparser->changeTaskStatus($taskUid,'deleting');


```



### License ###

Released under the MIT License.



