<?php

declare(strict_types=1);

namespace Onliner\Materialized\Statement;

use Onliner\Materialized\Command;

class CreateIndex implements Command
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $target;

    /**
     * @var array<string>
     */
    private $columns;

    /**
     * @var bool
     */
    private $strict = false;

    /**
     * @param string $name
     * @param string $target
     * @param array<string> $columns
     */
    private function __construct(string $name, string $target, array $columns)
    {
        $this->name = $name;
        $this->target = $target;
        $this->columns = $columns;
    }

    /**
     * @param string $name
     * @param string $target
     * @param array<string> $columns
     *
     * @return self
     */
    public static function on(string $name, string $target, array $columns = []): self
    {
        return new self($name, $target, $columns);
    }

    /**
     * @return self
     */
    public function strict(): self
    {
        $this->strict = true;

        return $this;
    }

    /**
     * @link https://materialize.com/docs/sql/create-index
     *
     * @return string
     */
    public function __toString(): string
    {
        $default = empty($this->columns);
        $query = $default ? 'CREATE DEFAULT INDEX' : 'CREATE INDEX';

        if (!$this->strict) {
            $query .= ' IF NOT EXISTS';
        }

        $query .= ' %s ON %s';

        return $default ?
            sprintf($query, $this->name, $this->target) :
            sprintf($query . ' (%s)', $this->name, $this->target, implode(',', $this->columns));
    }
}
