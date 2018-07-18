<?php

namespace App\Controller;

use App\Entity\Category;
use App\Service\CategoryService;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends FOSRestController
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Create a Category resource
     * @Rest\Post("/categories")
     * $param Request $request
     * @return View
     */
    public function postCategory(Request $request): View
    {
        $category = $this->categoryService->createCategory(
            array(
                'name' => $request->get('name'),
                'delayedTime' => $request->get('delayedTime'),
                'priority' => $request->get('priority'),
                'parentId' => $request->get('parentId'),
                'createdTime' => time(),
                'updatedTime' => time(),
            )
        );

        return View::create($category, Response::HTTP_CREATED);
    }

    /**
     * Update Category resource
     * @Rest\Put("/categories/{categoryId}")
     */
    public function putCategory(int $categoryId, Request $request): View
    {
        $category = $this->categoryService->updateCategory(
            $categoryId,
            array(
                'name' => $request->get('name'),
                'delayedTime' => $request->get('delayedTime'),
                'priority' => $request->get('priority'),
                'parentId' => $request->get('parentId'),
                'updatedTime' => time(),
            )
        );

        return View::create($category, Response::HTTP_OK);
    }

    /**
     * Remove the Category resource
     * @Rest\Delete("categories/{categoryId}", name="category_delete")
     */
    public function deleteCategory(int $categoryId): View
    {
        $this->categoryService->deleteCategory($categoryId);

        return View::create([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Return a Category resource
     * @Rest\Get("/categories/{categoryId}")
     */
    public function getCategory(int $categoryId): View
    {
        $category = $this->categoryService->getCategory($categoryId);

        return View::create($category, Response::HTTP_OK);
    }

    /**
     * Return a collection of Category resource
     * @Rest\Get("/categories")
     */
    public function findCategories(): View
    {
        $projects = $this->categoryService->findCategories();

        return View::create($projects, Response::HTTP_OK);
    }
}