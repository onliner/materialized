<?php

declare(strict_types=1);

namespace Onliner\Materialized\Format;

use Onliner\Materialized\Format;

class Protobuf implements Format
{
    /**
     * @var string
     */
    private $connection;

    /**
     * @var array<string, string>
     */
    private $options;

    /**
     * @param string $connection
     * @param array<string, string> $options
     */
    public function __construct(string $connection, array $options = [])
    {
        $this->connection = $connection;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $definition = sprintf('FORMAT PROTOBUF USING CONFLUENT SCHEMA REGISTRY CONNECTION %s', $this->connection);

        if (!empty($this->options)) {
            $options = implode(',', array_map(static function (string $key, string $value) {
                return sprintf('%s = %s', $key, $value);
            }, array_keys($this->options), array_values($this->options)));

            $definition .= sprintf(' WITH (%s)', $options);
        }

        return $definition;
    }
}
