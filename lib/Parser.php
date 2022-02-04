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

    public function override(string $id, $value): void
    {
        $this->addOption([
            'id' => $id, 
            'value' => $value,
            'type' => 'override', 
         ]);
    }

    public function filter(string $field, string $operator, string $value, string $option): void
    {
        $this->addOption([
            'result' => $field,
            'filterType' => $operator,
            'value' => $value,
            'type' => 'filter',
            'option' => $option
        ]);
    }

    public function addOption(array $option)
    {
        $this->options[] = $option;
    }

    public function toArray(): array
    {
        return [$this->name, $this->preset, ...$this->options];
    }
}
