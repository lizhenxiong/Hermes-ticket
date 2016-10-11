<?php 
namespace Hermes\Biz\Ticket;

interface CategoryService
{
    public function createCategory($category);

    public function updateCategory($id, $fields);

    public function deleteCategory($id);

    public function getCategory($id);

    public function getCategoryStructureTree();

    public function findCategories();

    public function searchCategorys($conditions, $orderBy, $start, $limit);

    public function searchCategoryCount($conditions);

    public function getCategoryByName($name);

    public function sortCategories($ids);
}