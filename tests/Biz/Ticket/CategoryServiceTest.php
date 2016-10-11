<?php

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class CategoryServiceTest extends BaseTestCase
{
    public function testGetCategory()
    {
        $category = $this->getCategoryService()->getCategory(1);
        $this->assertNull($category);
    }

    public function testCreateCategory()
    {
        $category = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );

        $resultCategory = $this->getCategoryService()->createCategory($category);
        $this->assertEquals('haha', $resultCategory['name']);
        $this->assertEquals(1000, $resultCategory['delayedTime']);
    }

    /**
     *@expectedException \Exception
     */
    public function testCreateCategoryWithfields()
    {
        $category = array();

        $this->getCategoryService()->createCategory($category);
    }

    /**
     *@expectedException \Exception
     */
    public function testCreateCategoryWithName()
    {
        $category1 = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $category2 = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 100
        );
        $this->getCategoryService()->createCategory($category1);
        $this->getCategoryService()->createCategory($category2);
    }

    /**
     *@expectedException \Exception
     */
    public function testCreateCategoryWithDelayedTime()
    {
        $category1 = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $category2 = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 100
        );
        $this->getCategoryService()->createCategory($category1);
        $this->getCategoryService()->createCategory($category2);
    }

    public function testUpdateCategory()
    {
        $category = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $resultCategory = $this->getCategoryService()->createCategory($category);
        $resultCategory = $this->getCategoryService()->updateCategory($resultCategory['id'],array('name'=>'heihei'));
        $this->assertEquals('heihei',$resultCategory['name']);
    }

    /**
     *@expectedException \Exception
     */
    public function testUpdateCategoryWithNoExist()
    {
        $category = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $resultCategory = $this->getCategoryService()->createCategory($category);
        $this->getCategoryService()->deleteCategory($resultCategory['id']);
        $resultCategory = $this->getCategoryService()->updateCategory($resultCategory['id'],array('name'=>'heihei'));
    }

    /**
     *@expectedException \Exception
     */
    public function testUpdateCategoryWithExistName()
    {
        $category1 = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $category2 = array(
            'priority' => 2,
            'name' => 'heihei',
            'delayedTime' => 1000
        );
        $resultCategory = $this->getCategoryService()->createCategory($category1);
        $this->getCategoryService()->createCategory($category2);
        $resultCategory = $this->getCategoryService()->updateCategory($resultCategory['id'],array('name'=>'heihei'));
    }

    public function testDeleteCategory()
    {
        $category = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $resultCategory = $this->getCategoryService()->createCategory($category);
        $this->getCategoryService()->deleteCategory($resultCategory['id']);
        $category = $this->getCategoryService()->getCategory($resultCategory['id']);
        $this->assertNull($category);
    }

    /**
     *@expectedException \Exception
     */
    public function testDeleteCategoryWithNoExist()
    {
        $category = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $resultCategory = $this->getCategoryService()->createCategory($category);
        $this->getCategoryService()->deleteCategory($resultCategory['id']);
        $this->getCategoryService()->deleteCategory($resultCategory['id']);
    }

    /**
     *@expectedException \Exception
     */
    public function testDeleteCategoryWithExistAssociateFaq()
    {
        $category = array(
            'id' => 1,
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $faq = array(
            'category' => 1,
            'question' => 'lost connection',
            'answer' => 'try connected',
        );
        $resultCategory = $this->getCategoryService()->createCategory($category);
        $this->getFaqService()->createFaq($faq);
        $this->getCategoryService()->deleteCategory($resultCategory['id']);
    }

    /**
     *@expectedException \Exception
     */
    public function testDeleteCategoryWithExistAssociateTicket()
    {
        $category = array(
            'id' => 1,
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $ticket = array(
            'category' => 1,
            'title' => 'haha',
            'about' => 'xixi',
            'status' => 'waiting',
            'operatorId' => 0,
            'role' => 'customer',
        );
        $resultCategory = $this->getCategoryService()->createCategory($category);
        $this->getTicketService()->createTicket($ticket);
        $this->getCategoryService()->deleteCategory($resultCategory['id']);
    }

    public function testSearchCategorys()
    {
        $category1 = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $category2 = array(
            'priority' => 2,
            'name' => 'heihei',
            'delayedTime' => 1000
        );
        $this->getCategoryService()->createCategory($category1);
        $this->getCategoryService()->createCategory($category2);
        $resultCategorys = $this->getCategoryService()->searchCategorys(
            array('priority' => 2),
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('2',count($resultCategorys));
    }

    public function testSearchCategoryCount()
    {
        $category1 = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $category2 = array(
            'priority' => 2,
            'name' => 'heihei',
            'delayedTime' => 1000
        );
        $this->getCategoryService()->createCategory($category1);
        $this->getCategoryService()->createCategory($category2);
        $count = $this->getCategoryService()->searchCategoryCount(
            array('priority' => 2),
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('2',$count);
    }

    public function testFindCategories()
    {
        $category1 = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $category2 = array(
            'priority' => 2,
            'name' => 'heihei',
            'delayedTime' => 1000
        );
        $this->getCategoryService()->createCategory($category1);
        $this->getCategoryService()->createCategory($category2);
        $resultCategorys = $this->getCategoryService()->findCategories();
        $this->assertEquals('2',count($resultCategorys));
    }

    public function testGetCategoryByName()
    {
        $category = array(
            'priority' => 2,
            'name' => 'haha',
            'delayedTime' => 1000
        );
        $this->getCategoryService()->createCategory($category);
        $resultCategory = $this->getCategoryService()->getCategoryByName('haha');
        $this->assertEquals(2, $resultCategory['priority']);
    }

    protected function getCategoryService()
    {
        return self::$kernel['category_service'];
    }

    protected function getFaqService()
    {
        return self::$kernel['faq_service'];
    }

    protected function getTicketService()
    {
        return self::$kernel['ticket_service'];
    }
}