<?php
declare(strict_types=1);
namespace VerteXVaaR\BlueDist\Model\SubFolder;

use VerteXVaaR\BlueSprints\Mvc\AbstractModel;

/**
 * Class Leaf
 */
class Leaf extends AbstractModel
{
    /**
     * @var int
     */
    protected $number = 0;

    /**
     * @param int $number
     */
    public function __construct(int $number)
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
}
