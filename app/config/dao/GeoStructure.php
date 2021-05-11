<?php


namespace App\Config\DAO;


class GeoStructure
{
    private array $structure;

    final public function getStructure(): array
    {
        return $this->structure;
    }

    final public function setStructure(array $structure): GeoStructure
    {
        $this->structure = $structure;
        return $this;
    }
}