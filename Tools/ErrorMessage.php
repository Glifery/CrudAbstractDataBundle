<?php

namespace Glifery\CrudAbstractDataBundle\Tools;

class ErrorMessage
{
    const STATUS_ERROR = '1';

    /** @var integer */
    private $status;

    /** @var string */
    private $title;

    /** @var string */
    private $message;

    /**
     * @param string $titleOrMessage
     * @param string $message
     */
    public function __construct($titleOrMessage = '', $message = '')
    {
        $this->status = self::STATUS_ERROR;
        $this->title = '';
        $this->message = '';

        if ($message) {
            if ($titleOrMessage) {
                $this->title = $titleOrMessage;
            }

            $this->message = $message;
        } else {
            if ($titleOrMessage) {
                $this->message = $titleOrMessage;
            }
        }
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}