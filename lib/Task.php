<?php

namespace Humanik\Aparser;

class Task
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = array_replace($this->defaultConfig(), $config);
    }

    public static function fromArray(array $config): self
    {
        $instance = new self($config);

        return $instance;
    }

    public function getOption(string $name, $default = null)
    {
        return $this->config[$name] ?? $default;
    }

    public function setPreset(string $preset): void
    {
        $this->config['preset'] = $preset;
    }

    public function setConfigPreset(string $configPreset): void
    {
        $this->config['configPreset'] = $configPreset;
    }

    public function setQueries(array $queries): void
    {
        $this->config['queries'] = $queries;
        $this->config['queriesFrom'] = 'text';
    }

    public function setQueriesFile($fileName): void
    {
        $this->config['queriesFile'] = (array) $fileName;
        $this->config['queriesFrom'] = 'file';
    }

    public function setQueryFormat($format): void
    {
        $this->config['queryFormat'] = (array) $format;
    }

    public function setResultsFileName(string $filename): void
    {
        $this->config['resultsFileName'] = $filename;
    }

    public function addParser(Parser $parser): Parser
    {
        $this->config['parsers'][] = $parser;

        return $parser;
    }

    public function toArray(): array
    {
        $result = array_slice($this->config, 0);
        $result['parsers'] = array_map(function ($parser) {
            return $parser instanceof Parser ? $parser->toArray() : $parser;
        }, $result['parsers']);

        return $result;
    }

    public function __toArray(): array
    {
        return $this->toArray();
    }

    public function defaultConfig(): array
    {
        return [
            'preset' => 'default',
            'configPreset' => 'default',
            'parsers' => [],
            'resultsFormat' => '$p1.preset',
            'resultsSaveTo' => 'file',
            'resultsFileName' => '$datefile.format().txt',
            'additionalFormats' => [],
            'resultsUnique' => 'no',
            'queriesFrom' => 'text',
            'queryFormat' => ['$query'],
            'uniqueQueries' => false,
            'saveFailedQueries' => false,
            'iteratorOptions' => [
                'onAllLevels' => false,
                'queryBuildersAfterIterator' => false,
                'queryBuildersOnAllLevels' => false,
            ],
            'resultsOptions' => [
                'overwrite' => false,
                'writeBOM' => false,
            ],
            'doLog' => 'no',
            'limitLogsCount' => '0',
            'keepUnique' => 'No',
            'moreOptions' => false,
            'resultsPrepend' => '',
            'resultsAppend' => '',
            'queryBuilders' => [],
            'resultsBuilders' => [],
            'configOverrides' => [],
            'runTaskOnComplete' => null,
            'useResultsFileAsQueriesFile' => false,
            'runTaskOnCompleteConfig' => 'default',
            'toolsJS' => '',
            'prio' => 5,
            'removeOnComplete' => false,
            'callURLOnComplete' => '',
            'queries' => '',
        ];
    }
}
