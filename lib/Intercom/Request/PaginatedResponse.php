<?php

namespace Intercom\Request;

/**
 * This class represents a paginated response from the Intercom API
 */
class PaginatedResponse
{
    private $content;
    private $page;
    private $nextPage;
    private $totalPages;
    private $totalCount;

    /**
     * @param mixed   $content
     * @param integer $page
     * @param integer $nextPage
     * @param integer $totalPages
     * @param integer $totalCount
     */
    public function __construct($content, $page, $nextPage, $totalPages, $totalCount)
    {
        $this->content = $content;
        $this->page = $page;
        $this->nextPage = $nextPage;
        $this->totalPages = $totalPages;
        $this->totalCount = $totalCount;
    }

    /**
     * Get content
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get page
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Get nextPage
     *
     * @return integer
     */
    public function getNextPage()
    {
        return $this->nextPage;
    }

    /**
     * Get totalPages
     *
     * @return integer
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * Get totalCount
     *
     * @return integer
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * Is there another page to be fetch ?
     *
     * @return boolean
     */
    public function hasPageToBeFetch()
    {
        return null !== $this->nextPage;
    }
}
