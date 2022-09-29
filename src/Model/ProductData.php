<?php

namespace TransactionCloud\Model;

class ProductData
{
    private string $link;

    private string $customProductId;

    public function __construct(string $link, string $customProductId)
    {
        $this->link = $link;
        $this->customProductId = $customProductId;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getCustomProductId(): string
    {
        return $this->customProductId;
    }
}