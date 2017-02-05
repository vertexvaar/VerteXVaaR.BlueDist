<?php
declare(strict_types=1);
namespace VerteXVaaR\BlueDist\Model\SubFolder;

use VerteXVaaR\BlueSprints\Mvc\AbstractModel;

/**
 * Class Tree
 */
class Tree extends AbstractModel
{
    /**
     * @var Branch
     */
    protected $mainBranch = null;

    /**
     * @var Branch[]
     */
    protected $branches = [];

    /**
     * @var string
     */
    protected $genus = '';

    /**
     * @return Branch
     */
    public function getMainBranch(): Branch
    {
        return $this->mainBranch;
    }

    /**
     * @param Branch $mainBranch
     */
    public function setMainBranch(Branch $mainBranch)
    {
        $this->mainBranch = $mainBranch;
    }

    /**
     * @return Branch[]
     */
    public function getBranches(): array
    {
        return $this->branches;
    }

    /**
     * @param Branch[] $branches
     */
    public function setBranches(array $branches)
    {
        $this->branches = $branches;
    }

    /**
     * @return string
     */
    public function getGenus(): string
    {
        return $this->genus;
    }

    /**
     * @param string $genus
     */
    public function setGenus(string $genus)
    {
        $this->genus = $genus;
    }
}
