<?php

namespace Intercom\Request\Search;

use \InvalidArgumentException;

class UserSearch
{
    private $page;
    private $perPage;
    private $tagId;
    private $tagName;
    private $sort;
    private $order;

    public function __construct($page = 1, $perPage = 100, $tagId = null, $tagName = null, $sort = 'created_at', $order = 'asc')
    {
        $this->setPage($page);
        $this->setPerPage($perPage);
        $this->setTagId($tagId);
        $this->setTagName($tagName);
        $this->setSort($sort);
        $this->setOrder($order);
    }

    public function setPage($page)
    {
        if (!filter_var($page, FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException('page must be an interger');
        }

        $this->page = $page;

        return $this;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPerPage($perPage)
    {
        if (!filter_var($perPage, FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException('perPage must be an interger');
        }

        $this->perPage = $perPage;

        return $this;
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    public function setTagId($tagId)
    {
        if (!filter_var($tagId, FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException('tagId must be an interger');
        }

        $this->tagId = $tagId;

        return $this;
    }

    public function getTagId()
    {
        return $this->tagId;
    }

    public function setTagName($tagName)
    {
        $this->tagName = $tagName;

        return $this;
    }

    public function getTagName()
    {
        return $this->tagName;
    }

    public function setSort($sort)
    {
        if (!in_array($sort, ['created_at'])) {
            throw new InvalidArgumentException('sort must belong a correct field list. See the API doc.');
        }

        $this->sort = $sort;

        return $this;
    }

    public function getSort()
    {
        return $this->sort;
    }

    public function setOrder($order)
    {
        if (!in_array($order, ['asc', 'desc'])) {
            throw new InvalidArgumentException('order must belong a correct type list. See the API doc.');
        }

        $this->order = $order;

        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }
}
