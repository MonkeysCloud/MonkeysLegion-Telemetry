<?php
declare(strict_types=1);

namespace MonkeysLegion\Telemetry;

/**
 * Decouples the codebase from any specific metrics backend.
 */
interface MetricsInterface
{
    /** Increment a counter. */
    public function counter(string $name, float $delta = 1, array $labels = []): void;

    /** Observe a value (histogram / timing). */
    public function histogram(string $name, float $value, array $labels = []): void;
}