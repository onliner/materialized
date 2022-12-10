<?php

declare(strict_types=1);

namespace Onliner\Materialized\Statement;

use Onliner\Materialized\Command;
use Onliner\Materialized\Envelope;

class CreateSource implements Command
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
     * @var string
     */
    private $size = '1';

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
     * @param string $connection
     * @param string $topic
     * @param Envelope|null $envelope
     *
     * @return self
     */
    public static function kafka(string $name, string $connection, string $topic, Envelope $envelope = null): self
    {
        $envelope = $envelope ?? Envelope::default();
        $definition = sprintf('KAFKA CONNECTION %s (TOPIC \'%s\') ', $connection, $topic);

        return new self($name, $definition . $envelope);
    }

    /**
     * @param string $name
     * @param string $connection
     * @param string $publication
     * @param array<string> $tables
     *
     * @return self
     */
    public static function postgres(string $name, string $connection, string $publication, array $tables = []): self
    {
        $definition = sprintf('POSTGRES CONNECTION %s (PUBLICATION \'%s\')', $connection, $publication);
        $definition .= empty($tables) ? ' FOR ALL TABLES' : sprintf(' FOR TABLES (%s)', implode(',', $tables));

        return new self($name, $definition);
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
     * @link https://materialize.com/docs/sql/create-source
     *
     * @return string
     */
    public function __toString(): string
    {
        $query = 'CREATE SOURCE';

        if (!$this->strict) {
            $query .= ' IF NOT EXISTS';
        }

        $query .= ' %s FROM %s WITH (SIZE = \'%s\')';

        return sprintf($query, $this->name, $this->definition, $this->size);
    }
}
