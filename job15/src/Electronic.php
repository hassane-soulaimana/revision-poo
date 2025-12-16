<?php

namespace App;

use App\Abstract\AbstractProduct;
use App\Interface\StockableInterface;

class Electronic extends AbstractProduct implements StockableInterface
{
    private string $brand = '';
    private int $waranty_fee = 0;

    public function getBrand(): string
    {
        return $this->brand;
    }
    public function setBrand(string $v): self
    {
        $this->brand = $v;
        return $this;
    }

    public function getWarantyFee(): int
    {
        return $this->waranty_fee;
    }
    public function setWarantyFee(int $v): self
    {
        $this->waranty_fee = $v;
        return $this;
    }

    public function addStocks(int $stock): self
    {
        if ($stock <= 0) return $this;
        $this->setQuantity($this->getQuantity() + $stock);
        return $this;
    }

    public function removeStocks(int $stock): self
    {
        if ($stock <= 0) return $this;
        $new = $this->getQuantity() - $stock;
        $this->setQuantity($new < 0 ? 0 : $new);
        return $this;
    }
}
