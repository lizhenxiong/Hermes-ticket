<?php

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class FaqServiceTest extends BaseTestCase
{
    public function testGetFaq()
    {
        $faq = $this->getFaqService()->getFaq(1);
        $this->assertNull($faq);
    }

    public function testCreateFaq()
    {
        $faq = array(
            'category' => 1,
            'question' => 'lost connection',
            'answer' => 'try connected',
        );
        $resultFaq = $this->getFaqService()->createFaq($faq);
        $this->assertEquals('lost connection', $resultFaq['question']);
        $this->assertEquals('try connected', $resultFaq['answer']);
    }

    /**
     *@expectedException \Exception
     */
    public function testCreateFaqWithfields()
    {
        $faq = array(
            'category' => 1,
            'answer' => 'try connected',
        );
        $resultFaq = $this->getFaqService()->createFaq($faq);
    }

    /**
     *@expectedException \Exception
     */
    public function testCreateFaqWithExistQuestion()
    {
        $faq1 = array(
            'category' => 1,
            'question' => 'lost connection',
            'answer' => 'try connected'
        );
        $faq2 = array(
            'category' => 1,
            'question' => 'lost connection',
            'answer' => 'try connected'
        );
        $this->getFaqService()->createFaq($faq1);
        $this->getFaqService()->createFaq($faq2);
    }

    public function testUpdateFaq()
    {
        $faq = array(
            'category' => 1,
            'question' => 'lost connection',
            'answer' => 'try connected'
        );
        $resultFaq = $this->getFaqService()->createFaq($faq);
        $resultFaq = $this->getFaqService()->updatefaq($resultFaq['id'],array('question'=>'404 not found'));
        $this->assertEquals('404 not found',$resultFaq['question']);
    }

    /**
     *@expectedException \Exception
     */
    public function testUpdateFaqWithNoExist()
    {
        $faq = array(
            'category' => 1,
            'question' => 'lost connection',
            'answer' => 'try connected'
        );
        $resultFaq = $this->getFaqService()->createFaq($faq);
        $this->getFaqService()->deleteFaq($resultFaq['id']);
        $resultFaq = $this->getFaqService()->updatefaq($resultFaq['id'],array('question'=>'404 not found'));
    }

    /**
     *@expectedException \Exception
     */
    public function testUpdateFaqWithExistQuestion()
    {
        $faq1 = array(
            'category' => 1,
            'question' => '500',
            'answer' => 'try connected'
        );
        $faq2 = array(
            'category' => 1,
            'question' => '404',
            'answer' => 'try connected'
        );
        $resultFaq = $this->getFaqService()->createFaq($faq1);
        $this->getFaqService()->createFaq($faq2);
        $this->getFaqService()->updatefaq($resultFaq['id'],array('question'=>'404'));
    }

    public function testDeleteFaq()
    {
        $faq = array(
            'category' => 1,
            'question' => 'lost connection',
            'answer' => 'try connected'
        );
        $resultFaq = $this->getFaqService()->createFaq($faq);
        $this->getFaqService()->deleteFaq($resultFaq['id']);
        $faq = $this->getFaqService()->getfaq($resultFaq['id']);
        $this->assertNull($faq);
    }

    /**
     *@expectedException \Exception
     */
    public function testDeleteFaqNoExist()
    {
        $faq = array(
            'category' => 1,
            'question' => 'lost connection',
            'answer' => 'try connected'
        );
        $resultFaq = $this->getFaqService()->createFaq($faq);
        $this->getFaqService()->deleteFaq($resultFaq['id']);
        $this->getFaqService()->deleteFaq($resultFaq['id']);
    }

    public function testGetFaqByQuestion()
    {
        $faq = array(
            'category' => 1,
            'question' => 'lost connection',
            'answer' => 'try connected'
        );
        $resultFaq = $this->getFaqService()->createFaq($faq);
        $faq = $this->getFaqService()->getFaqByQuestion($resultFaq['question']);
        $this->assertEquals(1, $faq['category']);
    }

    public function testSearchFaqs()
    {
        $faq1 = array(
            'category' => 1,
            'question' => 'lost connection',
            'answer' => 'try connected'
        );
        $faq2 = array(
            'category' => 1,
            'question' => 'not open page',
            'answer' => 'try again'
        );
        $this->getFaqService()->createFaq($faq1);
        $this->getFaqService()->createFaq($faq2);
        $resultFaqs = $this->getFaqService()->searchFaqs(
            array('category' => 1),
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('2',count($resultFaqs));
    }

    public function testSearchFaqCount()
    {
        $faq1 = array(
            'category' => 1,
            'question' => 'lost connection',
            'answer' => 'try connected'
        );
        $faq2 = array(
            'category' => 1,
            'question' => 'not open page',
            'answer' => 'try again'
        );
        $this->getFaqService()->createFaq($faq1);
        $this->getFaqService()->createFaq($faq2);
        $count = $this->getFaqService()->searchFaqCount(
            array('category' => 1),
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('2',$count);
    }

    public function testFindFaqByCategory()
    {
        $faq1 = array(
            'category' => 1,
            'question' => 'lost connection',
            'answer' => 'try connected'
        );
        $faq2 = array(
            'category' => 1,
            'question' => 'not open page',
            'answer' => 'try again'
        );
        $this->getFaqService()->createFaq($faq1);
        $this->getFaqService()->createFaq($faq2);
        $faqs = $this->getFaqService()->findFaqByCategory(1);
        $this->assertEquals('2',count($faqs));
    }

    protected function getFaqService()
    {
        return self::$kernel['faq_service'];
    }
}