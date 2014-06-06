<?php

namespace Intercom\Object;

use \LengthException,
    \OutOfBoundsException,
    \Datetime,
    \InvalidArgumentException;

/**
 * Metadata can be used to submit an event with extra key value data
 */
class EventMetadata
{
    private $metadata;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->metadata = [];

        foreach ($data as $key => $value) {
            $this->add($key, $value);
        }
    }

    /**
     * Get metadata
     * 
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Add a metadata
     * 
     * @param string $key
     * @param string $value
     */
    public function add($key, $value)
    {
        if (false === $this->checkMetadataSize()) { 
            throw new LengthException('Metadata array is full');
        }

        $this->metadata[$key] = $value;

        return $this;
    }

    /**
     * Get a metadata
     * 
     * @param  string $key
     * 
     * @return mixed
     */
    public function get($key)
    {
        if (false === isset($this->metadata[$key])) {
            throw new OutOfBoundsException('This key doesn\'t exists');
        }

        return $this->metadata[$key];
    }

    /**
     * Add a metadata Date
     * 
     * @param string   $key  You can juste passed the key name. "_date" will be added.
     * @param Datetime $date 
     */
    public function addDate($key, Datetime $date)
    {
        if (false === $this->checkMetadataSize()) { 
            throw new LengthException('Metadata array is full');
        }

        $this->metadata[$key . '_date'] = $date->getTimestamp();

        return $this;
    }

    /**
     * Get Date
     * 
     * @param  string $key You can juste passed the key name. "_date" will be added.
     * 
     * @return array
     */
    public function getDate($key)
    {
        if (false === isset($this->metadata[$key . '_date'])) {
            throw new OutOfBoundsException('This key doesn\'t exists');
        }

        return $this->metadata[$key . '_date'];
    }

    /**
     * Add rich link
     * 
     * @param string $key
     * @param string $url
     * @param string $value
     */
    public function addRichLink($key, $url, $value)
    {
        if (false === $this->checkMetadataSize()) { 
            throw new LengthException('Metadata array is full');
        }

        $this->metadata[$key] = ['url' => $url, 'value' => $value];

        return $this;
    }

    /**
     * Add a metadata Stripe
     * 
     * @param string $type       You can juste passed the key name. "stripe_" will be added.
     * @param string $identifier 
     */
    public function addStripe($type, $identifier)
    {
        if (false === $this->checkMetadataSize()) { 
            throw new LengthException('Metadata array is full');
        }

        if (!in_array('stripe_' . $type, ['stripe_customer', 'stripe_invoice', 'stripe_charge'])) {
            throw new OutOfBoundsException('Stripe must belong a correct list');
        }

        $this->metadata['stripe_' . $type] = $identifier;

        return $this;
    }

    /**
     * Get Stripe
     * 
     * @param  string $type You can juste passed the key name. "stripe_" will be added.
     * 
     * @return string
     */
    public function getStripe($type)
    {
        if (false === isset($this->metadata['stripe_' . $type])) {
            throw new OutOfBoundsException('This key doesn\'t exists');
        }

        return $this->metadata['stripe_' . $type];
    }

    /**
     * Add a metadata money amount
     * 
     * @param string  $key
     * @param integer $amount Positive integer
     * @param string  $currency 
     */
    public function addMonetaryAmount($key, $amount, $currency)
    {
        if (false === $this->checkMetadataSize()) { 
            throw new LengthException('Metadata array is full');
        }

        if (0 > $amount || false === filter_var($amount, FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException('Amount must be a positive integer');
        }

        $this->metadata[$key] = ['amount' => $amount, 'currency' => $currency];

        return $this;
    }

    /**
     * Check if metadata array is full
     * 
     * @return boolean
     */
    private function checkMetadataSize()
    {
        return count($this->metadata) < 5;
    }
}
