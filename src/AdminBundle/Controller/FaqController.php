<?php 

namespace Hermes\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Hermes\WebBundle\Controller\BaseController;
use Hermes\Common\Paginator;

class FaqController extends BaseController
{
    public function createAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $faq = $request->request->all();
            $faq = $this->getFaqService()->createFaq($faq);
            return $this->redirectToRoute('admin_faq_list');
        }
        $categorys = $this->getCategoryService()->findCategories();

        return $this->render('AdminBundle:Faq:add-faq.html.twig', array(
            'categorys' => $categorys
        ));
    }

    public function updateAction(Request $request, $id)
    {
        if ($request->getMethod() == 'POST') {
            $faq = $request->request->all();
            $faq = $this->getFaqService()->updateFaq($id,$faq);
            return $this->redirectToRoute('admin_faq_list');  
        } 
        $categorys = $this->getCategoryService()->findCategories();
        $faq = $this->getFaqService()->getFaq($id);
        return $this->render('AdminBundle:Faq:update-faq.html.twig',array(
            'faq' => $faq,
            'categorys' => $categorys
        ));
    }

    public function deleteAction(Request $request, $id)
    {
        $result = $this->getFaqService()->deleteFaq($id);
        if (empty($result)) {
            return new JsonResponse(array('success' => false));
        }
        return new JsonResponse(array('success' => true));
    }

    public function listAction(Request $request)
    {
        $conditions = $request->query->all();

        $count = $this->getFaqService()->searchFaqCount($conditions);
        $paginator = new Paginator(
            $request,
            $count,
            5
        );

        $faqs = $this->getFaqService()->searchFaqs(
            $conditions, 
            array('id', 'DESC'), 
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );
        $categorys = $this->getCategoryService()->findCategories();

        return $this->render('AdminBundle:Faq:faq-list.html.twig',array(
            'faqs' => $faqs,
            'categorys' => $categorys,
            'paginator' => $paginator
        ));
    }

    public function createFieldsCheckAction(Request $request)
    {
        $question = $request->query->get('question');
        if ($question) {
            $faq = $this->getFaqService()->getFaqByQuestion($question);
            
            if ($faq) {
                return new JsonResponse(false);
            }
        }
        return new JsonResponse(true);
    }

    public function updateFieldsCheckAction(Request $request)
    {
        $question = $request->query->get('question');
        if ($question) {
            $faq = $this->getFaqService()->getFaqByQuestion($question);
            $id = $request->query->get('id');
            if ($id == $faq['id']) {
                return new JsonResponse(true);
            }
            if ($faq) {
                return new JsonResponse(false);
            }
        }
        return new JsonResponse(true);
    }

    public function getFaqService()
    {
        return $this->biz['faq_service'];
    }

    public function getCategoryService()
    {
        return $this->biz['category_service'];
    }
}
