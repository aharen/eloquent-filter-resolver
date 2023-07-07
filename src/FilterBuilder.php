<?php

namespace Salim\FilterResolver;

use Stringable;

class FilterBuilder
{
    private array $passthru = [
        'eq',
        'ne',
        'gt',
        'lt',
        'gte',
        'lte',
        'contains',
        'in',
        'notIn',
    ];

    public function __construct(
        private string $filter = ''
    )
    {
    }

    public function __call(string $condition, array $arguments): self
    {
        if (!in_array($condition, $this->passthru)) {
            throw new \InvalidArgumentException("Invalid condition: {$condition}");
        }

        $this->add($condition, ...$arguments);
        return $this;
    }

    public function get(): string
    {
        return $this->filter;
    }

    private function add(string $condition, string $column, array|string $value, string $operator = 'and'): void
    {
        $prefix = (empty($this->filter)) ? null : " {$operator} ";

        $value = !is_array($value) ?: '"' . implode('|', $value) . '"';

        $this->filter .= "${prefix}{$condition}({$column}, {$value})";
    }
}