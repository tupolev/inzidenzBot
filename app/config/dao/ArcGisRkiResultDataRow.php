<?php


namespace App\Config\DAO;


class ArcGisRkiResultDataRow
{
    private string $stateInternal;
    private string $provinceInternal;
    private string $inzidenzValue;
    private string $lastUpdated;
    private string $reftype;

    final public function __construct(string $stateInternal, string $provinceInternal, string $inzidenzValue, string $lastUpdated, string $reftype)
    {
        $this->stateInternal = $stateInternal;
        $this->provinceInternal = $provinceInternal;
        $this->inzidenzValue = $inzidenzValue;
        $this->lastUpdated = $lastUpdated;
        $this->reftype = $reftype;
    }

    final public function getReftype(): string
    {
        return $this->reftype;
    }

    final public function setReftype(string $reftype): ArcGisRkiResultDataRow
    {
        $this->reftype = $reftype;
        return $this;
    }

    final public function getStateInternal(): string
    {
        return $this->stateInternal;
    }

    final public function setStateInternal(string $stateInternal): ArcGisRkiResultDataRow
    {
        $this->stateInternal = $stateInternal;
        return $this;
    }

    final public function getProvinceInternal(): string
    {
        return $this->provinceInternal;
    }

    final public function setProvinceInternal(string $provinceInternal): ArcGisRkiResultDataRow
    {
        $this->provinceInternal = $provinceInternal;
        return $this;
    }

    final public function getInzidenzValue(): string
    {
        return $this->inzidenzValue;
    }

    final public function setInzidenzValue(string $inzidenzValue): ArcGisRkiResultDataRow
    {
        $this->inzidenzValue = $inzidenzValue;
        return $this;
    }

    final public function getLastUpdated(): string
    {
        return $this->lastUpdated;
    }

    final public function setLastUpdated(string $lastUpdated): ArcGisRkiResultDataRow
    {
        $this->lastUpdated = $lastUpdated;
        return $this;
    }
}