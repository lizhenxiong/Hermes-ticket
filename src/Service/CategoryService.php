<?php

namespace App\Service;

use App\Entity\Category;

class CategoryService extends BaseService
{
    public function createCategory($fields)
    {
        $category = new Category();

        $category->setName($fields['name']);
        $category->setDelayedTime($fields['delayedTime']);
        $category->setPriority($fields['priority']);
        $category->setParentId($fields['parentId']);
        $category->setCreatedTime(time());
        $category->setUpdatedTime(time());

        $this->getEntityManage()->persist($category);
        $this->getEntityManage()->flush();

        return $category;
    }

    public function updateCategory($id, $fields)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id '.$id
            );
        }

        $category->setName($fields['name']);
        $category->setDelayedTime($fields['delayedTime']);
        $category->setPriority($fields['priority']);
        $category->setParentId($fields['parentId']);
        $category->setUpdatedTime(time());

        $this->getEntityManage()->flush();

        return $category;
    }

    public function deleteCategory($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        $this->getEntityManage()->remove($category);
        $this->getEntityManage()->flush();

        return null;
    }

    public function getCategory($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'Article with id '.$id.' does not exist!'
            );
        }

        return $category;
    }

    public function findCategories()
    {
        return $this->getDoctrine()->getRepository(Category::class)->findAll();
    }

    public function searchCategories($criteria, $orderBy, $limit, $offset)
    {
        return $this->getDoctrine()->getRepository(Category::class)->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function countCategories($criteria)
    {
        return $this->getEm()->getUnitOfWork()->getEntityPersister(Category::class)->count($criteria);
    }
}