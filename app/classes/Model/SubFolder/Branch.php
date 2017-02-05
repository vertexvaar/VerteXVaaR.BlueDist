<?php
declare(strict_types=1);
namespace VerteXVaaR\BlueDist\Model\SubFolder;

use VerteXVaaR\BlueSprints\Mvc\AbstractModel;

/**
 * Class Branch
 */
class Branch extends AbstractModel
{
    /**
     * @var Leaf[]
     */
    protected $leaves = [];

    /**
     * @var int
     */
    protected $length = 0;

    /**
     * @return Leaf[]
     */
    public function getLeaves(): array
    {
        return $this->leaves;
    }

    /**
     * @param Leaf[] $leaves
     * @return self
     */
    public function setLeaves(array $leaves): self
    {
        $this->leaves = $leaves;
        return $this;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     * @return self
     */
    public function setLength(int $length): self
    {
        $this->length = $length;
        return $this;
    }
}
