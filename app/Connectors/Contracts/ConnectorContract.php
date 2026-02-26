<?php

namespace App\Connectors\Contracts;

interface ConnectorContract
{
    /**
     * Load config.
     */
    public function loadConfig(): void;

    /**
     * Get unique identifier that maps to config.
     */
    public function getIdentifier(): string;

    /**
     * Get human readable label.
     */
    public function getLabel(): string;

    /**
     * Get description.
     */
    public function getDescription(): string;

    /**
     * Check if connector is enabled.
     */
    public function isEnabled(): bool;
}
