<?php


namespace App\Config\DAO;


class Twitter
{
    private string $consumerKey;
    private string $consumerSecret;
    private string $accessTokenKey;
    private string $accessTokenSecret;
    private string $inputEncoding;

    final public function getConsumerKey(): string
    {
        return $this->consumerKey;
    }

    final public function setConsumerKey(string $consumerKey): Twitter
    {
        $this->consumerKey = $consumerKey;
        return $this;
    }

    final public function getConsumerSecret(): string
    {
        return $this->consumerSecret;
    }

    final public function setConsumerSecret(string $consumerSecret): Twitter
    {
        $this->consumerSecret = $consumerSecret;
        return $this;
    }

    final public function getAccessTokenKey(): string
    {
        return $this->accessTokenKey;
    }

    final public function setAccessTokenKey(string $accessTokenKey): Twitter
    {
        $this->accessTokenKey = $accessTokenKey;
        return $this;
    }

    final public function getAccessTokenSecret(): string
    {
        return $this->accessTokenSecret;
    }

    final public function setAccessTokenSecret(string $accessTokenSecret): Twitter
    {
        $this->accessTokenSecret = $accessTokenSecret;
        return $this;
    }

    final public function getInputEncoding(): string
    {
        return $this->inputEncoding;
    }

    final public function setInputEncoding(string $inputEncoding): Twitter
    {
        $this->inputEncoding = $inputEncoding;
        return $this;
    }

    final public function toArray(): array
    {
        return [
            'oauth_access_token' => $this->accessTokenKey,
            'oauth_access_token_secret' => $this->accessTokenSecret,
            'consumer_key' => $this->consumerKey,
            'consumer_secret' => $this->consumerSecret,
            "inputEncoding" => $this->inputEncoding,
        ];
    }
}