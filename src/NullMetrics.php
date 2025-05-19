<?php
declare(strict_types=1);

namespace MonkeysLegion\Telemetry;

final class NullMetrics implements MetricsInterface
{
    public function counter(string $name, float $delta = 1, array $labels = []): void {}
    public function histogram(string $name, float $value, array $labels = []): void {}
}