<?php


namespace App\Config\DAO;


class Config
{
    private General $general;
    private GeoStructure $geoStructure;
    private Twitter $twitter;

    public function __construct(General $general, GeoStructure $geoStructure, Twitter $twitter)
    {
        $this->general = $general;
        $this->geoStructure = $geoStructure;
        $this->twitter = $twitter;
    }

    final public function getGeneral(): General
    {
        return $this->general;
    }

    final public function setGeneral(General $general): Config
    {
        $this->general = $general;
        return $this;
    }

    final public function getGeoStructure(): GeoStructure
    {
        return $this->geoStructure;
    }

    final public function setGeoStructure(GeoStructure $geoStructure): Config
    {
        $this->geoStructure = $geoStructure;
        return $this;
    }

    final public function getTwitter(): Twitter
    {
        return $this->twitter;
    }

    final public function setTwitter(Twitter $twitter): Config
    {
        $this->twitter = $twitter;
        return $this;
    }

}
