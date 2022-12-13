<?php

declare(strict_types=1);

namespace Onliner\Materialized\Statement;

use Onliner\Materialized\Command;
use Onliner\Materialized\Envelope;

class CreateSink implements Command
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $definition;

    /**
     * @var string
     */
    private $size = '1';

    /**
     * @var bool
     */
    private $strict = false;

    /**
     * @param string $name
     * @param string $from
     * @param string $definition
     */
    private function __construct(string $name, string $from, string $definition)
    {
        $this->name = $name;
        $this->from = $from;
        $this->definition = $definition;
    }

    /**
     * @param string $name
     * @param string $from
     * @param string $connection
     * @param string $topic
     * @param array<string> $keys
     * @param Envelope|null $envelope
     *
     * @return self
     */
    public static function kafka(
        string $name,
        string $from,
        string $connection,
        string $topic,
        array $keys = [],
        Envelope $envelope = null
    ): self {
        $envelope = $envelope ?? Envelope::default();
        $definition = sprintf('KAFKA CONNECTION %s (TOPIC \'%s\') ', $connection, $topic);

        if (!empty($keys)) {
            $definition .= sprintf('KEY (%s) ', implode(',', $keys));
        }

        return new self($name, $from, $definition . $envelope);
    }

    /**
     * @param string $size
     *
     * @return self
     */
    public function size(string $size): self
    {
        $this->size = $size;

        return $this;
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
     * @link https://materialize.com/docs/sql/create-sink
     *
     * @return string
     */
    public function __toString(): string
    {
        $query = 'CREATE SINK';

        if (!$this->strict) {
            $query .= ' IF NOT EXISTS';
        }

        $query .= ' %s FROM %s INTO %s WITH (SIZE = \'%s\')';

        return sprintf($query, $this->name, $this->from, $this->definition, $this->size);
    }
}
