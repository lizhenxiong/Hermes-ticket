<?php
namespace Hermes\WebBundle\Twig\Extension;

use Hermes\Biz\BizKernel;
use Hermes\Common\ExtensionManager;

class DataExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        $options = array('is_safe' => array('html'));
        return array(
            new \Twig_SimpleFunction('data', array($this, 'getData'), $options),
            new \Twig_SimpleFunction('datas', array($this, 'getDatas'), $options),
            new \Twig_SimpleFunction('datas_count', array($this, 'getDatasCount'), $options),
            new \Twig_SimpleFunction('service', array($this, 'callService'), $options),
        );
    }

    public function getData($name, $arguments)
    {
        $datatag = ExtensionManager::instance()->getDataTag($name);
        return $datatag->getData($arguments);
    }

    public function getDatas($name, $conditions, $sort = null, $start = null, $limit = null)
    {
        $method = 'get' . ucfirst($name) . 'Datas';
        if (!method_exists($this, $method)) {
            throw new \RuntimeException("尚未定义批量获取'{$name}'数据");
        }
        return $this->{$method}($conditions, $sort, $start, $limit);
    }

    public function getDatasCount($conditions)
    {
        $method = 'get' . ucfirst($name) . 'DatasdeCount';
        if (!method_exists($this, $method)) {
            throw new \RuntimeException("尚未定义获取'{$name}'数据的记录条数");
        }
        return $this->{$method}($condihtions);
    }

    public function callService($name, $method, $arguments)
    {
        $service = $this->createService($name);
        $reflectionClass = new \ReflectionClass($service);
        return $reflectionClass->getMethod($method)->invokeArgs($service, $arguments);
    }

    public function getName ()
    {
        return 'data_twig';
    }

}