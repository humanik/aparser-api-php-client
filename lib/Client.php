<?php

namespace Humanik\Aparser;

use Exception;
use InvalidArgumentException;

/**
 * The A-Parser API Client
 *
 * This client assists in making calls to A-Parser API.
 *
 * @url https://github.com/ice2038/aparser-api-php-client
 * @url http://a-parser.com/wiki/user-api/
 *
 * @version 1.0.0
 */
class Client
{
    private string $host;
    private string $password;

    /**
     * Create new Client
     *
     * @param string $host
     * @param string $password
     *
     * @return void
     */
    public function __construct(string $host, string $password)
    {
        $this->host = $host;
        $this->password = $password;
    }

    /**
     * The ping method, the server should respond by invoking "pong" on
     * the callback data
     *
     * @return string
     */
    public function ping()
    {
        return $this->makeRequest(__FUNCTION__);
    }

    /**
     * Return total information (pid, version, tasks in queue);
     *
     * @return array
     */
    public function info()
    {
        return $this->makeRequest(__FUNCTION__);
    }

    /**
     * Getting a list of live proxies
     *
     * @return array
     */
    public function getProxies()
    {
        return $this->makeRequest(__FUNCTION__);
    }

    /**
     * Installation of the current preset of proxy checker
     *
     * @param string $preset
     *
     * @return array
     */
    public function setProxyCheckerPreset($preset = 'default')
    {
        return $this->makeRequest(__FUNCTION__, ['preset' => $preset]);
    }

    /**
     * @param string $query
     * @param string $parser
     * @param string $preset
     * @param int    $rawResults
     * @param array  $options
     *
     * @return mixed
     */
    public function oneRequest(
        string $query,
        string $parser,
        string $preset = 'default',
        int $rawResults = 0,
        array $options = []
    ): array {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'query' => $query,
                'parser' => $parser,
                'preset' => $preset,
                'rawResults' => $rawResults,
                'options' => $options,
            ]
        );
    }

    /**
     * @param array  $queries
     * @param string $parser
     * @param string $preset
     * @param int    $threads
     * @param int    $rawResults
     * @param array  $options
     *
     * @return array
     */
    public function bulkRequest(
        array $queries,
        string $parser,
        string $preset = 'default',
        int $threads = 5,
        int $rawResults = 0,
        array $options = []
    ): array {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'queries' => $queries,
                'parser' => $parser,
                'preset' => $preset,
                'threads' => $threads,
                'rawResults' => $rawResults,
                'options' => $options,
            ]
        );
    }

    /**
     * Getting of the parser settings and presets
     *
     * @param $parser
     * @param string $preset
     *
     * @return array
     */
    public function getParserPreset(string $parser, string $preset = 'default')
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'parser' => $parser,
                'preset' => $preset,
            ]
        );
    }

    /**
     * Add a task to turn all options are similar to those that are
     * specified in the interface Add Task
     *
     * @param string $configPreset
     * @param string $taskPreset
     * @param string $queriesFrom  file|text
     * @param array  $queries
     * @param array  $options
     *
     * @return string taskUid
     *
     * @throws InvalidArgumentException
     */
    public function addTask($configPreset, $taskPreset, $queriesFrom, $queries, $options = [])
    {
        $data['configPreset'] = $configPreset ? $configPreset : 'default';

        if ($taskPreset) {
            $data['preset'] = $taskPreset;
        } else {
            $data['resultsFileName'] = $options['resultsFileName'] ?? '$datefile.format().txt';
            $data['parsers']         = $options['parsers'] ?? [];
            $data['uniqueQueries']   = $options['uniqueQueries'] ?? 0;
            $data['keepUnique']      = $options['keepUnique'] ?? 0;
            $data['resultsPrepend']  = $options['resultsPrepend'] ?? '';
            $data['moreOptions']     = $options['moreOptions'] ?? '';
            $data['resultsUnique']   = $options['resultsUnique'] ?? 'no';
            $data['doLog']           = $options['doLog'] ?? 'no';
            $data['queryFormat']     = $options['queryFormat'] ?? '$query';
            $data['resultsSaveTo']   = $options['resultsSaveTo'] ?? 'file';
            $data['configOverrides'] = $options['configOverrides'] ?? [];
            $data['resultsFormat']   = $options['resultsFormat'] ?? '';
            $data['resultsAppend']   = $options['resultsAppend'] ?? '';
            $data['queryBuilders']   = $options['queryBuilders'] ?? [];
            $data['resultsBuilders'] = $options['resultsBuilders'] ?? [];
        }

        switch ($queriesFrom) {
            case 'file':
                $data['queriesFrom'] = 'file';
                $data['queriesFile'] = isset($options['queriesFile']) ? $options['queriesFile'] : false;
                break;
            case 'text':
                $data['queriesFrom'] = 'text';
                $data['queries'] = $queries ? $queries : [];
                break;
            default:
                throw new InvalidArgumentException('Argument $queriesFrom is incorrect!');
        }

        return $this->makeRequest(__FUNCTION__, $data);
    }

    /**
     * Getting the status of task by uid
     *
     * @param int $taskUid
     *
     * @return array
     */
    public function getTaskState(int $taskUid): array
    {
        return $this->makeRequest(__FUNCTION__, ['taskUid' => $taskUid]);
    }

    /**
     * Getting configuration task by uid
     *
     * @param int $taskUid
     *
     * @return array
     */
    public function getTaskConf(int $taskUid): array
    {
        return $this->makeRequest(__FUNCTION__, ['taskUid' => $taskUid]);
    }

    /**
     * Change status of a task by id
     *
     * @param int    $taskUid
     * @param string $toStatus starting|pausing|stopping|deleting
     *
     * @return array
     */
    public function changeTaskStatus(int $taskUid, string $toStatus): array
    {
        return $this->makeRequest(__FUNCTION__, ['taskUid' => $taskUid, 'toStatus' => $toStatus]);
    }

    /**
     * @param int    $taskUid
     * @param string $direction start|end|up|down
     *
     * @return array
     */
    public function moveTask(int $taskUid, string $direction): array
    {
        return $this->makeRequest(__FUNCTION__, ['taskUid' => $taskUid, 'direction' => $direction]);
    }

    /**
     * Getting the link to Task results file by Task Uid
     *
     * @param int $taskUid
     *
     * @return array
     */
    public function getTaskResultsFile(int $taskUid): array
    {
        return $this->makeRequest(__FUNCTION__, ['taskUid' => $taskUid]);
    }

    /**
     * Removing results file by Task Uid
     *
     * @param $taskUid
     *
     * @return mixed
     */
    public function deleteTaskResultsFile(int $taskUid)
    {
        return $this->makeRequest(__FUNCTION__, ['taskUid' => $taskUid]);
    }

    /**
     * Getting the list of tasks
     *
     * @param $completed
     *
     * @return mixed
     */
    public function getTasksList($completed = 0)
    {
        return $this->makeRequest(__FUNCTION__, ['completed' => $completed]);
    }

    /**
     * Displays a list of all available results that can return the specified parser.
     *
     * @param string $parser
     *
     * @return array
     */
    public function getParserInfo(string $parser): array
    {
        return $this->makeRequest(__FUNCTION__, ['parser' => $parser]);
    }

    /**
     * Update executable file of the parser to the latest version, after sending the command.
     *
     * @return mixed
     */
    public function update()
    {
        return $this->makeRequest(__FUNCTION__);
    }


    /**
     * Getting the number of active accounts (for Yandex).
     *
     * @return mixed
     */
    public function getAccountsCount()
    {
        return $this->makeRequest(__FUNCTION__);
    }

    /**
     * @param string $action
     * @param array  $data
     *
     * @return array|true
     */
    private function makeRequest(string $action, array $data = [])
    {
        $request = [
            'action' => $action,
            'password' => $this->password,
        ];

        if (!empty($data)) {
            $request['data'] = $data;
        }

        $body = json_encode($request);

        $ch = curl_init($this->host);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Length: ' . strlen($body)]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/plain; charset=UTF-8']);

        $response = curl_exec($ch);
        curl_close($ch);

        if (false === $response) {
            throw new Exception(sprintf('Response fail: %s', curl_error($ch)));
        }

        $response = json_decode($response, true);

        if (!$response['success']) {
            throw new Exception(sprintf('Response fail: %s', $response['msg'] ?? 'unknow error'));
        }

        return $response['data'] ?? true;
    }
}
