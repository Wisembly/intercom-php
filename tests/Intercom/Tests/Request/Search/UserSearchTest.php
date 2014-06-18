<?php

namespace Intercom\Tests\Request\Search;

use \PHPUnit_Framework_TestCase;

use Intercom\Request\Search\UserSearch;

class UserSearchTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getSearchParameters
     */
    public function testCreateASearchWithWrongParameters($page, $perPage, $tagId, $tagName, $sort, $order, $expectedMessage)
    {
        $this->setExpectedException(
            'InvalidArgumentException', $expectedMessage
        );

        new UserSearch($page, $perPage, $tagId, $tagName, $sort, $order);
    }

    public function getSearchParameters()
    {
        return [
            [1, 100, 1, 'foo', 'foo', 'asc', 'sort must belong a correct field list. See the API doc.'],
            [1, 100, 1, 'foo', 'created_at', 'foo', 'order must belong a correct type list. See the API doc.'],
        ];
    }
}
