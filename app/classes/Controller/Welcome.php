<?php
declare(strict_types=1);
namespace VerteXVaaR\BlueDist\Controller;

use VerteXVaaR\BlueDist\Model\Fruit;
use VerteXVaaR\BlueDist\Model\SubFolder\Branch;
use VerteXVaaR\BlueDist\Model\SubFolder\Leaf;
use VerteXVaaR\BlueDist\Model\SubFolder\Tree;
use VerteXVaaR\BlueSprints\Mvc\AbstractController;

/**
 * Class Welcome
 */
class Welcome extends AbstractController
{
    /**
     *
     */
    protected function index()
    {
        $this->templateRenderer->setVariable(
            'strings',
            [
                'foo',
                'bar',
                'baz',
            ]
        );
    }

    /**
     *
     */
    protected function listFruits()
    {
        $this->templateRenderer->setVariable('fruits', Fruit::findAll());
    }

    /**
     *
     */
    protected function createDemoFruits()
    {
        $fruitsData = [
            [
                'color' => 'red',
                'name' => 'Apple',
            ],
            [
                'color' => 'yellow',
                'name' => 'Banana',
            ],
            [
                'color' => 'black',
                'name' => 'Blackberry',
            ],
            [
                'color' => 'red',
                'name' => 'Strawberry',
            ],
        ];
        foreach ($fruitsData as $fruitData) {
            $fruit = new Fruit();
            $fruit->setColor($fruitData['color']);
            $fruit->setName($fruitData['name']);
            $fruit->save();
        }
        $this->redirect('listFruits');
    }

    /**
     *
     */
    protected function createFruit()
    {
        if ($this->request->hasArgument('name') && $this->request->hasArgument('color')) {
            $fruit = new Fruit();
            $fruit->setColor($this->request->getArgument('color'));
            $fruit->setName($this->request->getArgument('name'));
            $fruit->save();
        }
        $this->redirect('listFruits');
    }

    /**
     *
     */
    protected function editFruit()
    {
        $fruit = Fruit::findByUuid($this->request->getArgument('fruit'));
        $this->templateRenderer->setVariable('fruit', $fruit);
    }

    /**
     *
     */
    protected function updateFruit()
    {
        $fruit = Fruit::findByUuid($this->request->getArgument('uuid'));
        $fruit->setName($this->request->getArgument('name'));
        $fruit->setColor($this->request->getArgument('color'));
        $fruit->save();
        $this->redirect('listFruits');
    }

    /**
     *
     */
    protected function createTree()
    {
        $tree = new Tree();
        $tree->setGenus($this->request->getArgument('genus'));
        $tree->save();
        $this->templateRenderer->setVariable('tree', $tree);
        $this->templateRenderer->setVariable('branches', range(1, $this->request->getArgument('numberOfBranches')));
    }

    /**
     *
     */
    protected function newTree()
    {
    }

    /**
     *
     */
    protected function growBranches()
    {
        $tree = Tree::findByUuid($this->request->getArgument('tree'));
        $branches = [];
        foreach ($this->request->getArgument('branches') as $data) {
            $branch = new Branch();
            $branch->setLength((int)$data['length']);
            $branches[] = $branch;
        }
        $tree->setBranches($branches);
        $tree->save();
        $this->redirect('applyLeaves?tree=' . $tree->getUuid());
    }

    /**
     *
     */
    protected function applyLeaves()
    {
        $this->templateRenderer->setVariable(
            'tree',
            Tree::findByUuid($this->request->getArgument('tree'))
        );
    }

    /**
     *
     */
    protected function addLeaf()
    {
        $tree = Tree::findByUuid($this->request->getArgument('tree'));
        $branch = $tree->getBranches()[$this->request->getArgument('branch')];
        $leaves = $branch->getLeaves();
        $leaves[] = new Leaf(count($leaves) + 1);
        $branch->setLeaves($leaves);
        $tree->save();
        $this->redirect('applyLeaves?tree=' . $tree->getUuid());
    }

    /**
     *
     */
    protected function deleteFruit()
    {
        $fruit = Fruit::findByUuid($this->request->getArgument('fruit'));
        $fruit->delete();
        $this->redirect('listFruits');
    }
}
