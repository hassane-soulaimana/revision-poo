<?php

namespace App;

use App\Abstract\AbstractProduct;
use App\Interface\StockableInterface;

class Clothing extends AbstractProduct implements StockableInterface
{
    private string $size = '';
    private string $color = '';
    private string $type = '';
    private int $material_fee = 0;

    public function getSize(): string
    {
        return $this->size;
    }
    public function setSize(string $v): self
    {
        $this->size = $v;
        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }
    public function setColor(string $v): self
    {
        $this->color = $v;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }
    public function setType(string $v): self
    {
        $this->type = $v;
        return $this;
    }

    public function getMaterialFee(): int
    {
        return $this->material_fee;
    }
    public function setMaterialFee(int $v): self
    {
        $this->material_fee = $v;
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
