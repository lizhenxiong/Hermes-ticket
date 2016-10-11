<?php
namespace Hermes\WebBundle\Twig\Extension;

use Hermes\Common\ExtensionManager;
use Hermes\WebBundle\Util\CategoryBuilder;

class WebExtension extends \Twig_Extension
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('dict', array($this, 'getDict')),
            new \Twig_SimpleFunction('dict_text', array($this, 'getDictText'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('category_choices', array($this, 'getCategoryChoices'))
        );
    }

    public function getName()
    {
        return 'web_twig';
    }

    public function getDictText($type, $key)
    {
        $dict = $this->getDict($type);

        if (empty($dict) || !isset($dict[$key])) {
            return '';
        }

        return $dict[$key];
    }

    public function getDict($type)
    {
        return ExtensionManager::instance()->getDataDict($type);
    }

    public function getCategoryChoices()
    {
        return $this->buildChoices();
    }

    private function buildChoices($indent = '&nbsp&nbsp&nbsp')
    {
        $choices = array();
        $categories = $this->getCategoryService()->getCategoryTree();

        foreach ($categories as $category) {
            $choices[$category['id']] = str_repeat(is_null($indent) ? '' : $indent, ($category['depth']-1)) . $category['name'];
        }

        return $choices;
    }

    protected function getCategoryService()
    {
        return $this->container->get('biz')['category_service'];
    }

}
