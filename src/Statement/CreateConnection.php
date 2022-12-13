<?php

declare(strict_types=1);

namespace Onliner\Materialized\Statement;

use Onliner\Materialized\Command;

class CreateConnection implements Command
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $definition;

    /**
     * @var bool
     */
    private $strict = false;

    /**
     * @param string $name
     * @param string $definition
     */
    private function __construct(string $name, string $definition)
    {
        $this->name = $name;
        $this->definition = $definition;
    }

    /**
     * @param string $name
     * @param string ...$brokers
     *
     * @return self
     */
    public static function kafka(string $name, string ...$brokers): self
    {
        if (count($brokers) > 1) {
            $definition = sprintf('KAFKA (BROKERS (%s))', implode(', ', array_map(static function (string $dsn) {
                return sprintf('\'%s\'', $dsn);
            }, $brokers)));
        } else {
            $definition = sprintf('KAFKA (BROKER \'%s\')', current($brokers));
        }

        return new self($name, $definition);
    }

    /**
     * @param string $name
     * @param string $url
     *
     * @return self
     */
    public static function registry(string $name, string $url): self
    {
        return new self($name, sprintf('CONFLUENT SCHEMA REGISTRY (URL \'%s\')', $url));
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
     * @link https://materialize.com/docs/sql/create-connection
     *
     * @return string
     */
    public function __toString(): string
    {
        $query = 'CREATE CONNECTION';

        if (!$this->strict) {
            $query .= ' IF NOT EXISTS';
        }

        $query .= ' %s TO %s';

        return sprintf($query, $this->name, $this->definition);
    }
}
