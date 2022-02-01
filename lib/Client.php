<?php

namespace Humanik\Aparser;

use Exception;
use InvalidArgumentException;

/**
 * The A-Parser API Client
 *
 * This client assists in making calls to A-Parser API.
 *
 * @url https://github.com/humanik/aparser-api-php-client
 * @url https://a-parser.com/docs/api/methods
 *
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
     * The ping method, the server should respond by invoking "pong" on the callback data
     * @url https://a-parser.com/docs/api/methods#ping
     *
     * @return string
     */
    public function ping(): string
    {
        return $this->makeRequest(__FUNCTION__);
    }

    /**
     * Single parsing request, any parser and preset can be used
     * @url https://a-parser.com/docs/api/methods#onerequest
     *
     * @param Parser $parser
     * @param string $query
     * @param bool $rawResults
     *
     * @return array
     */
    public function oneRequest(Parser $parser, string $query, bool $rawResults = false): array
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'parser' => $parser->getName(),
                'preset' => $parser->getPreset(),
                'options' => $parser->getOptions(),
                'query' => $query,
                'rawResults' => (int) $rawResults,
            ]
        );
    }

    /**
     * Bulk parsing request, any parser and preset can be used
     * @url https://a-parser.com/docs/api/methods#bulkrequest
     *
     * @param Parser $parser
     * @param array $queries
     * @param string $preset
     * @param int $threads
     * @param bool $rawResults
     *
     * @return array
     */
    public function bulkRequest(Parser $parser, array $queries, int $threads = 5, bool $rawResults = false): array
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'parser' => $parser->getName(),
                'preset' => $parser->getPreset(),
                'options' => $parser->getOptions(),
                'queries' => $queries,
                'threads' => $threads,
                'rawResults' => (int) $rawResults,
            ]
        );
    }

    /**
     * Add a task to turn all options are similar to those that are specified in the interface Add Task
     * @url https://a-parser.com/docs/api/methods#addtask
     *
     * @param string $configPreset
     * @param string $taskPreset
     * @param string $queriesFrom  file|text
     * @param array $queries
     * @param array $options
     *
     * @return int
     *
     * @throws InvalidArgumentException
     */
    public function addTask(Task $task): int
    {
        return (int) $this->makeRequest(__FUNCTION__, $task->toArray());
    }

    /**
     * Return total information (pid, version, tasks in queue);
     * @url https://a-parser.com/docs/api/methods#info
     *
     * @return array
     */
    public function info(): array
    {
        return $this->makeRequest(__FUNCTION__);
    }

    /**
     * Getting of the parser settings and presets
     * @url https://a-parser.com/docs/api/methods#getparserpreset
     *
     * @param $parser
     * @param string $preset
     *
     * @return array
     */
    public function getParserPreset(string $parser, string $preset = 'default'): array
    {
        return $this->makeRequest(__FUNCTION__, ['parser' => $parser, 'preset' => $preset]);
    }

    /**
     * Getting a list of live proxies
     * @url https://a-parser.com/docs/api/methods#getproxies
     *
     * @return array
     */
    public function getProxies(): array
    {
        return $this->makeRequest(__FUNCTION__);
    }

    /**
     * Getting the status of task by uid
     * @url https://a-parser.com/docs/api/methods#gettaskstate
     *
     * @param int $id
     *
     * @return array
     */
    public function getTaskState(int $id): array
    {
        return $this->makeRequest(__FUNCTION__, ['taskUid' => $id]);
    }

    /**
     * Getting configuration task by uid
     * @url https://a-parser.com/docs/api/methods#gettaskconf
     *
     * @param int $id
     *
     * @return array
     */
    public function getTaskConf(int $id): array
    {
        return $this->makeRequest(__FUNCTION__, ['taskUid' => $id]);
    }

    /**
     * Getting the link to Task results file by Task Uid
     * @url https://a-parser.com/docs/api/methods#gettaskresultsfile
     *
     * @param int $id
     *
     * @return string
     */
    public function getTaskResultsFile(int $id): string
    {
        return $this->makeRequest(__FUNCTION__, ['taskUid' => $id]);
    }

    /**
     * Getting the list of tasks
     * @url https://a-parser.com/docs/api/methods#gettaskslist
     *
     * @param bool $completed
     *
     * @return array
     */
    public function getTasksList(bool $completed = false): array
    {
        return $this->makeRequest(__FUNCTION__, ['completed' => (int) $completed]);
    }

    /**
     * Displays a list of all available results that can return the specified parser.
     * @url https://a-parser.com/docs/api/methods#getparserinfo
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
     * Getting the number of active accounts (for Yandex).
     * @url https://a-parser.com/docs/api/methods#getaccountscount
     *
     * @return array
     */
    public function getAccountsCount(): array
    {
        return $this->makeRequest(__FUNCTION__);
    }

    /**
     * Removing results file by Task Uid
     * @url https://a-parser.com/docs/api/methods#deletetaskresultsfile
     *
     * @param int $id
     *
     * @return bool
     */
    public function deleteTaskResultsFile(int $id): bool
    {
        return $this->makeRequest(__FUNCTION__, ['taskUid' => $id]);
    }

    /**
     * Change status of a task by id
     * @url https://a-parser.com/docs/api/methods#changetaskstatus
     *
     * @param int $id
     * @param string $status starting|pausing|stopping|deleting
     *
     * @return bool
     */
    public function changeTaskStatus(int $id, string $status): bool
    {
        return $this->makeRequest(__FUNCTION__, ['taskUid' => $id, 'toStatus' => $status]);
    }

    /**
     * Changing the state of the proxy checker
     * @url https://a-parser.com/docs/api/methods#changeproxycheckerstate
     *
     * @param string $checker
     * @param bool $state
     *
     * @return bool
     */
    public function changeProxyCheckerState(string $checker, bool $state): bool
    {
        return $this->makeRequest(__FUNCTION__, ['checker' => $checker, 'state' => (int) $state]);
    }

    /**
     * Moving a task in the queue by its id
     * @url https://a-parser.com/docs/api/methods#movetask
     *
     * @param int $id
     * @param string $direction start|end|up|down
     *
     * @return bool
     */
    public function moveTask(int $id, string $direction): bool
    {
        return $this->makeRequest(__FUNCTION__, ['taskUid' => $id, 'direction' => $direction]);
    }

    /**
     * Update executable file of the parser to the latest version, after sending the command.
     * @url https://a-parser.com/docs/api/methods#update
     *
     * @return bool
     */
    public function update(): bool
    {
        return $this->makeRequest(__FUNCTION__);
    }

    /**
     * @param string $action
     * @param array $data
     *
     * @return array|string|bool
     */
    private function makeRequest(string $action, array $data = [])
    {
        $request = [
            'action' => $action,
            'password' => $this->password,
        ];

        if ($data) {
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

        $json = json_decode($response, true);

        if (null === $json) {
            throw new Exception(sprintf('Invalid response: %s', $response));
        }

        if (!$json['success']) {
            throw new Exception(sprintf('Response fail: %s', $response['msg'] ?? 'unknow error'));
        }

        return $json['data'] ?? true;
    }
}
