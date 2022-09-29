<?php

declare(strict_types=1);

/*
 *
 *     This file is part of the Transaction.Cloud PHP SDK.
 *     Copyright Humbly Arrogant Ltd 2022
 *
 *     This source file is subject to the MIT license that is bundled
 *     with this source code in the file LICENSE.
 */

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
