<?php 
namespace Hermes\Biz\Faq;

interface FaqService
{
    public function createFaq($faq);

    public function updateFaq($id, $fields);

    public function deleteFaq($id);

    public function getFaq($id);

    public function searchFaqs($conditions, $orderBy, $start, $limit);

    public function searchFaqCount($conditions);

    public function findFaqByCategory($category);
}