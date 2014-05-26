<?php

namespace Intercom\Request\Search;

use \InvalidArgumentException;

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
     * @param integer  $page     the app id for the application’s inbox you wish to access
     * @param integer  $perPage  Users per page, max value of 500
     * @param interger $tagId    query for users that are tagged with a specific tag.
     * @param string   $tagName  query for users that are tagged with a specific tag.
     * @param string   $sort     sort the query for users based on a field. Accepted values - created_at.
     * @param string   $order    sorts the results in ascending or descending order. Accepted values - asc, desc.
     */
    public function __construct($page = 1, $perPage = 100, $tagId = null, $tagName = null, $sort = 'created_at', $order = 'asc')
    {
        $this->setPage($page);
        $this->setPerPage($perPage);
        $this->setTagId($tagId);
        $this->setTagName($tagName);
        $this->setSort($sort);
        $this->setOrder($order);
    }

    /**
     * {@inheritdoc}
     */
    public function format()
    {
        return [
            'page'     => $this->page,
            'per_page' => $this->perPage,
            'tag_id'   => $this->tagId,
            'tag_name' => $this->tagName,
            'sort'     => $this->sort,
            'order'    => $this->order,
        ];
    }

    /**
     * Set Page
     *
     * @param integer $page
     */
    public function setPage($page)
    {
        if (!filter_var($page, FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException('page must be an interger');
        }

        $this->page = $page;

        return $this;
    }

    /**
     * Get Page
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set Page
     *
     * @param integer $perPage
     */
    public function setPerPage($perPage)
    {
        if (!filter_var($perPage, FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException('perPage must be an interger');
        }

        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Get per page
     *
     * @return integer
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Set tag id
     *
     * @param integer $tagId
     */
    public function setTagId($tagId = null)
    {
        if (null !== $tagId && !filter_var($tagId, FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException('tagId must be an interger');
        }

        $this->tagId = $tagId;

        return $this;
    }

    /**
     * Get tag id
     *
     * @return integer
     */
    public function getTagId()
    {
        return $this->tagId;
    }

    /**
     * Set tag name
     *
     * @param string $tagName
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;

        return $this;
    }

    /**
     * Get tag name
     *
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Set sort
     *
     * @param string $sort Only created_at for the moment
     */
    public function setSort($sort)
    {
        if (!in_array($sort, ['created_at'])) {
            throw new InvalidArgumentException('sort must belong a correct field list. See the API doc.');
        }

        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
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
