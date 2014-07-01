<?php

namespace Intercom\Request\Search;

use \InvalidArgumentException,
    \LengthException;

use Intercom\Request\FormatableInterface;

/**
 * This class is used to create a search for the API which allow you to retrieve a collection of users.
 *
 * @link Api : http://doc.intercom.io/api/#users
 */
class UserSearch implements FormatableInterface
{
    private $page;
    private $perPage;
    private $tagId;
    private $tagName;
    private $sort;
    private $order;

    /**
     * @param integer  $page      the app id for the application’s inbox you wish to access
     * @param integer  $perPage   Users per page, max value of 500
     * @param string   $order     sorts the results in ascending or descending order. Accepted values - asc, desc.
     * @param interger $tagId     The id of the tag to filter by
     * @param integer  $segmentId The id of the segment to filter by
     */
    public function __construct($page = 1, $perPage = 50, $order = 'asc', $tagId = null, $segmentId = null)
    {
        if (null !== $tagId && null !== $segmentId) {
            throw new InvalidArgumentException("You can not combine tag and segment in the same request");
        }

        $this->setPage($page);
        $this->setPerPage($perPage);
        $this->setTagId($tagId);
        $this->setSegmentId($segmentId);
        $this->setOrder($order);
    }

    /**
     * {@inheritdoc}
     */
    public function format()
    {
        return [
            'page'       => $this->page,
            'per_page'   => $this->perPage,
            'tag_id'     => $this->tagId,
            'segment_id' => $this->segmentId,
            'order'      => $this->order,
        ];
    }

    /**
     * Set Page
     *
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get Page
     *
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set Page
     *
     * @param mixed $perPage
     */
    public function setPerPage($perPage)
    {
        if (50 < $perPage) {
            throw new LengthException(sprintf("perPage must be minus than 50, %d given.", $perPage));
        }

        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Get per page
     *
     * @return mixed
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Set tag id
     *
     * @param mixed $tagId
     */
    public function setTagId($tagId = null)
    {
        $this->tagId = $tagId;

        return $this;
    }

    /**
     * Get tag id
     *
     * @return mixed
     */
    public function getTagId()
    {
        return $this->tagId;
    }

    /**
     * Set SegmentId
     *
     * @param  integer $segmentId
     *
     * @return $this
     */
    public function setSegmentId($segmentId)
    {
        $this->segmentId = $segmentId;

        return $this;
    }

    /**
     * Get SegmentId
     *
     * @return integer
     */
    public function getSegmentId()
    {
        return $this->segmentId;
    }

    /**
     * Set order
     *
     * @param string $order asc or desc
     */
    public function setOrder($order)
    {
        if (!in_array($order, ['asc', 'desc'])) {
            throw new InvalidArgumentException('order must belong a correct type list. See the API doc.');
        }

        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }
}
