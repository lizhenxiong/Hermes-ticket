<?php 
namespace Hermes\Biz\Ticket\Impl;

use Hermes\Common\ArrayToolkit;
use Hermes\Common\TreeToolkit;
use Hermes\Biz\Ticket\CategoryService;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class CategoryServiceImpl extends KernelAwareBaseService implements CategoryService
{
    private $biz;

    public function __construct($biz)
    {
        $this->biz = $biz;
    }

    public function createCategory($category)
    {
        if (!ArrayToolkit::requireds($category, array('priority', 'name', 'delayedTime'), true)) {
            throw new \Exception('缺少必要字段,创建工单类型失败!');
        }

        if($category['delayedTime'] < 10)
        {
            throw new \Exception('滞留时间不能少于10分钟,请更换!');
        }

        $category['delayedTime'] = $category['delayedTime'] * 60;

        $existCategory = $this->getCategoryByName($category['name']);
        if (!empty($existCategory)) {
            throw new \Exception('工单类型名称已存在,请更换!');
        }

        return $this->getCategoryDao()->create($category);
    }

    public function updateCategory($id, $fields)
    {
        $existCategory = $this->getCategory($id);
        if (empty($existCategory)) {
            throw new \Exception('工单类型不存在!');
        }
        $existCategory = $this->getCategoryByName($fields['name']);
        if (!empty($existCategory) && $existCategory['id'] != $id) {
            throw new \Exception('工单类型名称已存在,请更换!');
        }
        return $this->getCategoryDao()->update($id, $fields);
    }

    public function deleteCategory($id)
    {
        $existCategory = $this->getCategory($id);
        if (empty($existCategory)) {
            throw new \Exception('工单类型不存在!');
        }
        $faqs = $this->getFaqService()->findFaqByCategory($id);
        $tickets = $this->getTicketService()->findTicketsByCategory($id);
        
        if (!empty($faqs)) {
            throw new \Exception('存在相关联的FAQ,删除失败!');
        }
        if (!empty($tickets)) {
            throw new \Exception('存在相关联的工单,删除失败!');
        }

        $ids = $this->findCategoryChildrenIds($id);
        $ids[] = $id;

        foreach ($ids as $id) {
            $this->getCategoryDao()->delete($id);
        }

        return $this->getCategoryDao()->delete($id);
    }

    public function getCategory($id)
    {
        return $this->getCategoryDao()->get($id);
    }

    public function getCategoryStructureTree()
    {
        return TreeToolkit::makeTree($this->getCategoryTree(), 'weight');
    }

    public function getCategoryTree()
    {
        $prepare = function ($categories) {
            $prepared = array();

            foreach ($categories as $category) {
                if (!isset($prepared[$category['parentId']])) {
                    $prepared[$category['parentId']] = array();
                }

                $prepared[$category['parentId']][] = $category;
            }

            return $prepared;
        };
        $data       = $this->findCategories();
        $categories = $prepare($this->findCategories());

        $tree = array();
        $this->makeCategoryTree($tree, $categories, 0);

        return $tree;
    }

    public function searchCategorys($conditions, $orderBy, $start, $limit)
    {
        return $this->getCategoryDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function findCategoryChildrenIds($id)
    {
        $category = $this->getCategory($id);

        if (empty($category)) {
            return array();
        }

        $tree = $this->getCategoryTree();

        $childrenIds = array();
        $depth       = 0;

        foreach ($tree as $node) {
            if ($node['id'] == $category['id']) {
                $depth = $node['depth'];
                continue;
            }

            if ($depth > 0 && $depth < $node['depth']) {
                $childrenIds[] = $node['id'];
            }

            if ($depth > 0 && $depth >= $node['depth']) {
                break;
            }
        }

        return $childrenIds;
    }

    public function findCategories($orderBy = array('id', 'ASC'))
    {
        return $this->getCategoryDao()->search(
            array(),
            $orderBy,
            0,
            PHP_INT_MAX
        );
    }

    public function searchCategoryCount($conditions)
    {
        return $this->getCategoryDao()->count($conditions);
    }

    public function getCategoryByName($name)
    {
        return $this->getCategoryDao()->getCategoryByFields(array('name' => $name));
    }

    public function sortCategories($ids)
    {
        foreach ($ids as $index => $id) {
            $this->updateCategory($id, array('weight' => $index+1));
        }
    }

    private function makeCategoryTree(&$tree, &$categories, $parentId)
    {
        static $depth = 0;
        static $leaf  = false;

        if (isset($categories[$parentId]) && is_array($categories[$parentId])) {
            foreach ($categories[$parentId] as $category) {
                $depth++;
                $category['depth'] = $depth;
                $tree[]            = $category;
                $this->makeCategoryTree($tree, $categories, $category['id']);
                $depth--;
            }
        }

        return $tree;
    }

    private function getCategoryDao()
    {
        return $this->biz['category_dao'];
    }

    private function getFaqService()
    {
        return $this->biz['faq_service'];
    }

    private function getTicketService()
    {
        return $this->biz['ticket_service'];
    }
}