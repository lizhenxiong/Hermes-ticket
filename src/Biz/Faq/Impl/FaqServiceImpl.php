<?php 
namespace Hermes\Biz\Faq\Impl;

use Hermes\Biz\Faq\FaqService;
use Hermes\Common\ArrayToolkit;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class FaqServiceImpl extends KernelAwareBaseService implements FaqService
{
    private $biz;

    public function __construct($biz)
    {
        $this->biz = $biz;
    }

    public function createFaq($faq)
    {
        unset($faq['file']);
        if (!ArrayToolkit::requireds($faq, array('category', 'question', 'answer'), true)) {
            throw new \Exception('缺少必要字段,创建问题模板失败!');
        }

        $existFaq = $this->getFaqByQuestion($faq['question']);
        if (!empty($existFaq)) {
            throw new \Exception('问题标题名称已存在,请更换!');
        }

        $faq['attachment1'] = isset($faq['attachment1'])?$faq['attachment1']: null;
        $faq['attachment2'] = isset($faq['attachment2'])?$faq['attachment2']: null;
        $faq['attachment3'] = isset($faq['attachment3'])?$faq['attachment3']: null;
        $faq['attachment4'] = isset($faq['attachment4'])?$faq['attachment4']: null;
        $faq['attachment5'] = isset($faq['attachment5'])?$faq['attachment5']: null;
        return $this->getFaqDao()->create($faq);
    }

    public function updateFaq($id, $fields)
    {
        unset($fields['file']);
        $existFaq = $this->getFaq($id);

        $fields['attachment1'] = isset($fields['attachment1'])?$fields['attachment1']: null;
        $fields['attachment2'] = isset($fields['attachment2'])?$fields['attachment2']: null;
        $fields['attachment3'] = isset($fields['attachment3'])?$fields['attachment3']: null;
        $fields['attachment4'] = isset($fields['attachment4'])?$fields['attachment4']: null;
        $fields['attachment5'] = isset($fields['attachment5'])?$fields['attachment5']: null;

        if (empty($existFaq)) {
            throw new \Exception('FAQ不存在!');
        }

        $existFaq = $this->getFaqByQuestion($fields['question']);
        if (!empty($existFaq) && $existFaq['id'] != $id) {
            throw new \Exception('问题标题名称已存在,请更换!');
        }
        return $this->getFaqDao()->update($id, $fields);
    }

    public function deleteFaq($id)
    {
        $existFaq = $this->getFaq($id);
        if (empty($existFaq)) {
            throw new \Exception('FAQ不存在!');
        }
        return $this->getFaqDao()->delete($id);
    }

    public function getFaq($id)
    {
        return $this->getFaqDao()->get($id);
    }

    public function getFaqByQuestion($question)
    {
        return $this->getFaqDao()->getFaqByQuestion(array('question' => $question));
    }

    public function searchFaqs($conditions, $orderBy, $start, $limit)
    {
        return $this->getFaqDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function searchFaqCount($conditions)
    {
        return $this->getFaqDao()->count($conditions);
    }

    public function findFaqByCategory($category)
    {
        return $this->getFaqDao()->search(
            array('category' => $category),
            array('id','ASC'),
            0,
            PHP_INT_MAX
        );
    }

    private function getFaqDao()
    {
        return $this->biz['faq_dao'];
    }
}