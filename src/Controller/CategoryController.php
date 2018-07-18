<?php

namespace App\Controller;

use App\Entity\Category;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends FOSRestController
{
    /**
     * Create a Category resource
     * @Rest\Post("/categories")
     * $param Request $request
     * @return View
     */
    public function postCategory(Request $request): View
    {
        $entityManage = $this->getDoctrine()->getManager();

        $category = new Category();
        $category->setName($request->get('name'));
        $category->setDelayedTime($request->get('delayedTime'));
        $category->setPriority($request->get('priority'));
        $category->setParentId($request->get('parentId'));
        $category->setCreatedTime(time());
        $category->setUpdatedTime(time());

        $entityManage->persist($category);
        $entityManage->flush();

        return View::create($category, Response::HTTP_CREATED);
    }

    /**
     * Update Category resource
     * @Rest\Put("/categories/{categoryId}")
     */
    public function putCategory(int $categoryId, Request $request): View
    {
        $entityManage = $this->getDoctrine()->getManager();
        $category = $entityManage->getRepository(Category::class)->find($categoryId);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id '.$categoryId
            );
        }

        $category->setName($request->get('name'));
        $category->setDelayedTime($request->get('delayedTime'));
        $category->setPriority($request->get('priority'));
        $category->setParentId($request->get('parentId'));
        $category->setUpdatedTime(time());

        $entityManage->flush();

        return View::create($category, Response::HTTP_OK);
    }

    /**
     * Remove the Category resource
     * @Rest\Delete("categories/{categoryId}")
     */
    public function deleteCategory(int $categoryId): View
    {
        $entityManage = $this->getDoctrine()->getManager();
        $category = $entityManage->getRepository(Category::class)->find($categoryId);

        $entityManage->remove($category);
        $entityManage->flush();

        return View::create([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Return a Category resource
     * @Rest\Get("/categories/{categoryId}")
     */
    public function getCategory(int $categoryId): View
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($categoryId);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id '.$categoryId
            );
        }

        return View::create($category, Response::HTTP_OK);
    }

    /**
     * Return a collection of Category resource
     * @Rest\Get("/categories")
     */
    public function findCategories(): View
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $projects = $repository->findAll();

        return View::create($projects, Response::HTTP_OK);
    }
}