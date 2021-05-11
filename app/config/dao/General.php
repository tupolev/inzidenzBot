<?php


namespace App\Config\DAO;


class General
{
    private array $settings;

    final public function setSettings(array $settings): General
    {
        $this->settings = $settings;
        return $this;
    }

    final public function getSettings(): array
    {
        return $this->settings;
    }
}