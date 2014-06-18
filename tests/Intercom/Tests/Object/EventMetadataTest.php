<?php

namespace Intercom\Tests\Object;

use \PHPUnit_Framework_TestCase,
    \Datetime;

use Intercom\Object\EventMetadata;

class EventMetadataTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException LengthException
     * @expectedMessageException Metadata array is full
     */
    public function testConstructWithTooMuchData()
    {
        $metadata = new EventMetadata(['foo', 'bar', 'baz', 'fooFoo', 'barBar', 'BazBaz']);
    }

    /**
     * @expectedException LengthException
     * @expectedMessageException Metadata array is full
     */
    public function testAddWhenTheMetadataArrayIsFull()
    {
        $metadata = new EventMetadata;

        // Fill 5 metadata
        for ($i=0; $i < 5; $i++) { 
            $metadata->add($i, 'foo');
        }

        // Add one
        $metadata->add(5, 'foo');
    }

    /**
     * @expectedException OutOfBoundsException
     * @expectedMessageException This key doesn't exists
     */
    public function testGetWithUnknownKey()
    {
        $metadata = new EventMetadata;
        $metadata->get('foo');
    }

    /**
     * @expectedException LengthException
     * @expectedMessageException Metadata array is full
     */
    public function testAddDateWithTooMuchData()
    {
        $metadata = new EventMetadata(['foo', 'bar', 'baz', 'fooFoo', 'barBar']);
        $metadata->addDate('contact', new Datetime('2014-01-01'));
    }

    /**
     * @expectedException OutOfBoundsException
     * @expectedMessageException This key doesn't exists
     */
    public function testGetDateWithUnknownKey()
    {
        $metadata = new EventMetadata;
        $metadata->getDate('foo');
    }

    public function testAddDate()
    {
        $metadata = new EventMetadata;
        $metadata->addDate('contact', new Datetime('2014-01-01'));

        $this->assertEquals((new Datetime('2014-01-01'))->getTimestamp(), $metadata->get('contact_date'));
    }

    /**
     * @expectedException LengthException
     * @expectedMessageException Metadata array is full
     */
    public function testAddRichLinkWithTooMuchData()
    {
        $metadata = new EventMetadata(['foo', 'bar', 'baz', 'fooFoo', 'barBar']);
        $metadata->addRichLink('google', 'http://google.com', 'Search website');
    }

    public function testAddRichLink()
    {
        $metadata = new EventMetadata;
        $metadata->addRichLink('google', 'http://google.com', 'Search website');

        $this->assertEquals([
            'url'   => 'http://google.com',
            'value' => 'Search website',
        ], $metadata->get('google'));
    }

    /**
     * @expectedException LengthException
     * @expectedMessageException Metadata array is full
     */
    public function testAddStripeWithTooMuchData()
    {
        $metadata = new EventMetadata(['foo', 'bar', 'baz', 'fooFoo', 'barBar']);
        $metadata->addStripe('customer', '01234567789');
    }

    /**
     * @expectedException OutOfBoundsException
     * @expectedMessageException Stripe must belong a correct list
     */
    public function testAddStripeWithUnknownStripe()
    {
        $metadata = new EventMetadata;
        $metadata->addStripe('foo', '01234567789');
    }

    /**
     * @expectedException OutOfBoundsException
     * @expectedMessageException This key doesn't exists
     */
    public function testGetStripeWithUnknownKey()
    {
        $metadata = new EventMetadata;
        $metadata->getStripe('foo');
    }

    public function testAddStripe()
    {
        $metadata = new EventMetadata;
        $metadata->addStripe('customer', '01234567789');

        $this->assertEquals('01234567789', $metadata->getStripe('customer'));
    }

    /**
     * @expectedException LengthException
     * @expectedMessageException Metadata array is full
     */
    public function testAddMonetaryAmountWithTooMuchData()
    {
        $metadata = new EventMetadata(['foo', 'bar', 'baz', 'fooFoo', 'barBar']);
        $metadata->addMonetaryAmount('price', 100, 'eur');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedMessageException Amount must be a positive integer
     */
    public function testAddMonetaryAmountWithNegativeAmount()
    {
        $metadata = new EventMetadata;
        $metadata->addMonetaryAmount('price', -100, 'eur');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedMessageException Amount must be a positive integer
     */
    public function testAddMonetaryAmountWithStringAmount()
    {
        $metadata = new EventMetadata;
        $metadata->addMonetaryAmount('price', 'foo', 'eur');
    }

    public function testAddMonetaryAmount()
    {
        $metadata = new EventMetadata;
        $metadata->addMonetaryAmount('price', 100, 'eur');

        $this->assertEquals([
            'amount'   => 100,
            'currency' => 'eur',
        ], $metadata->get('price'));
    }
}
