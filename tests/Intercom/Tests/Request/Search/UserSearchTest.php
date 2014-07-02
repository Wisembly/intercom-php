<?php

namespace Intercom\Tests\Request\Search;

use \PHPUnit_Framework_TestCase;

use Intercom\Request\Search\UserSearch;

class UserSearchTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getSearchParameters
     */
    public function testCreateASearchWithWrongParameters($page, $perPage, $order, $tagId, $segmentId, $expectedMessage)
    {
        $this->setExpectedException(
            'InvalidArgumentException', $expectedMessage
        );

        new UserSearch($page, $perPage, $order, $tagId, $segmentId);
    }

    public function getSearchParameters()
    {
        return [
            [1, 50, 'foo', null, null, 'order must belong a correct type list. See the API doc.'],
            [1, 50, 'asc', 1, 1, 'You can not combine tag and segment in the same request.'],
        ];
    }

    /**
     * @expectedException LengthException
     * @expectedExceptionMessage perPage must be minus than 50, 100 given.
     */
    public function testCreateASearchWithWrongPerPage()
    {
        new UserSearch(1, 100);
    }
}
