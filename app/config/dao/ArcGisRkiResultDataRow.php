<?php


namespace App\Config\DAO;


class ArcGisRkiResultDataRow
{
    private string $stateInternal;
    private string $provinceInternal;
    private string $inzidenzValue;
    private string $lastUpdated;

    final public function __construct(string $stateInternal, string $provinceInternal, string $inzidenzValue, string $lastUpdated)
    {
        $this->stateInternal = $stateInternal;
        $this->provinceInternal = $provinceInternal;
        $this->inzidenzValue = $inzidenzValue;
        $this->lastUpdated = $lastUpdated;
    }

    /**
     * @return string
     */
    final public function getStateInternal(): string
    {
        return $this->stateInternal;
    }

    /**
     * @param string $stateInternal
     * @return ArcGisRkiResultDataRow
     */
    final public function setStateInternal(string $stateInternal): ArcGisRkiResultDataRow
    {
        $this->stateInternal = $stateInternal;
        return $this;
    }

    /**
     * @return string
     */
    final public function getProvinceInternal(): string
    {
        return $this->provinceInternal;
    }

    /**
     * @param string $provinceInternal
     * @return ArcGisRkiResultDataRow
     */
    final public function setProvinceInternal(string $provinceInternal): ArcGisRkiResultDataRow
    {
        $this->provinceInternal = $provinceInternal;
        return $this;
    }

    /**
     * @return string
     */
    final public function getInzidenzValue(): string
    {
        return $this->inzidenzValue;
    }

    /**
     * @param string $inzidenzValue
     * @return ArcGisRkiResultDataRow
     */
    final public function setInzidenzValue(string $inzidenzValue): ArcGisRkiResultDataRow
    {
        $this->inzidenzValue = $inzidenzValue;
        return $this;
    }

    /**
     * @return string
     */
    final public function getLastUpdated(): string
    {
        return $this->lastUpdated;
    }

    /**
     * @param string $lastUpdated
     * @return ArcGisRkiResultDataRow
     */
    final public function setLastUpdated(string $lastUpdated): ArcGisRkiResultDataRow
    {
        $this->lastUpdated = $lastUpdated;
        return $this;
    }
}