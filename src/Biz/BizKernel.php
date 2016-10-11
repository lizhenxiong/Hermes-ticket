<?php
namespace Hermes\Biz;

use Codeages\Biz\Framework\Context\Kernel;
use Hermes\Biz\User\Impl\UserServiceImpl;
use Hermes\Biz\User\Dao\Impl\UserDaoImpl;
use Hermes\Biz\User\Impl\NotificationServiceImpl;
use Hermes\Biz\User\Dao\Impl\NotificationDaoImpl;
use Hermes\Biz\Operator\Impl\OperatorServiceImpl;
use Hermes\Biz\Operator\Dao\Impl\OperatorDaoImpl;
use Hermes\Biz\Ticket\Impl\TicketServiceImpl;
use Hermes\Biz\Ticket\Dao\Impl\TicketDaoImpl;
use Hermes\Biz\Ticket\Impl\TicketItemServiceImpl;
use Hermes\Biz\Ticket\Dao\Impl\TicketItemDaoImpl;
use Hermes\Biz\File\Impl\FileServiceImpl;
use Hermes\Biz\File\Dao\Impl\FileDaoImpl;
use Hermes\Biz\Faq\Impl\FaqServiceImpl;
use Hermes\Biz\Faq\Dao\Impl\FaqDaoImpl;
use Hermes\Biz\Ticket\Dao\Impl\CategoryDaoImpl;
use Hermes\Biz\Ticket\Impl\CategoryServiceImpl;

class BizKernel extends Kernel
{
    protected $extraContainer;

    public function __construct($config, $extraContainer)
    {
        parent::__construct($config);
        $this->extraContainer = $extraContainer;
    }

    public function boot($options = array())
    {
        $this->registerService();
        $this->put('migration_directories', dirname(dirname(__DIR__)). '/migrations');
        parent::boot();
    }

    public function registerProviders()
    {
        return [];
    }

    protected function registerService()
    {
        $this['user_dao'] = $this->dao(function($container) {
            return new UserDaoImpl($container);
        });
        $this['user_service'] = function($container) {
            return new UserServiceImpl($container);
        };

        $this['ticket_dao'] = $this->dao(function($container) {
            return new TicketDaoImpl($container);
        });
        $this['ticket_service'] = function($container) {
            return new TicketServiceImpl($container);
        };

        $this['ticketItem_dao'] = $this->dao(function($container) {
            return new TicketItemDaoImpl($container);
        });
        $this['ticketItem_service'] = function($container) {
            return new TicketItemServiceImpl($container);
        };

        $this['file_dao'] = $this->dao(function($container) {
            return new FileDaoImpl($container);
        });
        $this['file_service'] = function($container) {
            return new FileServiceImpl($container);
        };

        $this['notification_dao'] = $this->dao(function($container) {
            return new NotificationDaoImpl($container);
        });

        $this['notification_service'] = function($container) {
            return new NotificationServiceImpl($container);
        };

        $this['faq_dao'] = $this->dao(function($container) {
            return new FaqDaoImpl($container);
        });

        $this['faq_service'] = function($container) {
            return new FaqServiceImpl($container);
        };

        $this['category_dao'] = $this->dao(function($container) {
            return new CategoryDaoImpl($container);
        });

        $this['category_service'] = function($container) {
            return new CategoryServiceImpl($container);
        };

        $this['password_encoder'] = function($container) {
            $class = $this->extraContainer->getParameter('app.current_user.class');
            $user = new $class(array());
            return $this->extraContainer->get('security.encoder_factory')->getEncoder($user);
        };
    }
}