<?php 

namespace Hermes\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Hermes\WebBundle\Controller\BaseController;
use Hermes\Common\Paginator;

class UserController extends BaseController
{
    public function indexAction(Request $request)
    {
        $fields = $request->query->all();
        $conditions = array(
            'keywordType' => '',
            'keyword' => '',
            'roles' => ''
        );
        
        $conditions = array_merge($conditions, $fields);
        if (isset($conditions['roles'])) {
            $conditions['roles'] = "%{$conditions['roles']}%";
        }

        if (isset($conditions['keywordType']) && isset($conditions['keyword'])){
                if ($conditions['keywordType'] == "username" || $conditions['keywordType'] == "name") {
                    $conditions[$conditions['keywordType']] = "%{$conditions['keyword']}%";
                } elseif ($conditions['keywordType'] == "operatorNo") {
                    $conditions[$conditions['keywordType']] = "{$conditions['keyword']}";
                }           
            unset($conditions['keywordType']);
            unset($conditions['keyword']);
           
        }

        $count = $this->getUserService()->searchUserCount($conditions);
        $paginator = new Paginator(
            $request,
            $count,
            20
        );

        $users = $this->getUserService()->searchUsers(
            $conditions, 
            array('id', 'DESC'), 
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );
        return $this->render('AdminBundle:User:index.html.twig',array(
            'users' => $users,
            'paginator' => $paginator
        ));
    }

    public function deleteAction(Request $request, $id)
    {   
        $result = $this->getUserService()->deleteUser($id);
        if (empty($result)) {
            return new JsonResponse(array('success' => false));
        }
        return new JsonResponse(array('success' => true));
    }

    public function updateAction(Request $request,$id)
    {
        if ($request->getMethod() == 'POST') {
 
            $user = $request->request->all();
            $user = $this->getUserService()->updateUser($id,$user);
            return $this->render('AdminBundle:User:update-tr-model.html.twig',array(
                'user' => $user
            ));  
        } 
        $user = $this->getUserService()->getUser($id);
        return $this->render('AdminBundle:User:update-model.html.twig',array('user' => $user
        ));
    }

    public function serviceListAction(Request $request)
    {
        $fields = $request->query->all();
        $conditions = array(
            'keywordType' => '',
            'keyword' => '',
            'roles' => 'ROLE_SERVICE'
        );
        
        $conditions = array_merge($conditions, $fields);
        if (isset($conditions['roles'])) {
            $conditions['roles'] = "%{$conditions['roles']}%";
        }

        if (isset($conditions['keywordType']) && isset($conditions['keyword'])){
                if ($conditions['keywordType'] == "username" || $conditions['keywordType'] == "name") {
                    $conditions[$conditions['keywordType']] = "%{$conditions['keyword']}%";
                } elseif ($conditions['keywordType'] == "operatorNo") {
                    $conditions[$conditions['keywordType']] = "{$conditions['keyword']}";
                }           
            unset($conditions['keywordType']);
            unset($conditions['keyword']);
           
        }

        $count = $this->getUserService()->searchUserCount($conditions);
        $paginator = new Paginator(
            $request,
            $count,
            20
        );

        $users = $this->getUserService()->searchUsers(
            $conditions, 
            array('id', 'DESC'), 
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );
        return $this->render('AdminBundle:User:index-service.html.twig',array(
            'users' => $users,
            'paginator' => $paginator
        ));
    }

    public function serviceDetailAction(Request $request, $id)
    {
        $user = $this->getUserService()->getUser($id);
        return $this->render('AdminBundle:User:index-service-detail.html.twig', array(
            'user' => $user
        ));
    }

    public function getUserService()
    {
        return $this->biz['user_service'];
    }
}
