<?php
declare(strict_types=1);
namespace VerteXVaaR\BlueDist\Model;

use VerteXVaaR\BlueSprints\Mvc\AbstractModel;

/**
 * Class Fruit
 *
 * @package VerteXVaaR\BlueWelcome\Model
 */
class Fruit extends AbstractModel
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $color = '';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Fruit
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return Fruit
     */
    public function setColor(string $color): self
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return array
     */
    protected function getIndexColumns(): array
    {
        return ['name'];
    }
}
