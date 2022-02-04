<?php

namespace Humanik\Aparser;

class Parser
{
    protected string $name;
    protected string $preset;
    protected array $options;

    public function __construct(string $name, string $preset = 'default', array $options = [])
    {
        $this->name = $name;
        $this->preset = $preset;
        $this->options = $options;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPreset(): string
    {
        return $this->preset;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public static function fromArray(array $config): self
    {
        [$name, $preset] = $config;
        $options = array_slice($config, 2);

        return new self($name, $preset, $options);
    }

    public function addOverride(string $id, $value): void
    {
        $this->addRawOption([
            'id' => $id, 
            'value' => $value,
            'type' => 'override', 
         ]);
    }

    public function addFilter(string $field, string $type, string $pattern, string $option): void
    {
        $this->addRawOption([
            'result' => $field,
            'filterType' => $type,
            'value' => $pattern,
            'type' => 'filter',
            'option' => $option
        ]);
    }

    public function addRawOption(array $option)
    {
        $this->options[] = $option;
    }

    public function toArray(): array
    {
        return [$this->name, $this->preset, ...$this->options];
    }
}
