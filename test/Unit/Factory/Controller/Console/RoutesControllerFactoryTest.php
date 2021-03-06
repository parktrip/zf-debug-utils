<?php
/**
 * ZfDebugModule. WebUI and Console commands for debugging ZF2 apps.
 *
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2016 Vítor Brandão <vitor@noiselabs.org>
 */

namespace Noiselabs\ZfDebugModuletest\Unit\Factory\Controller\Console;

use Noiselabs\ZfDebugModule\Controller\Console\RoutesController;
use Noiselabs\ZfDebugModule\Factory\Controller\Console\RoutesControllerFactory;
use Noiselabs\ZfDebugModule\Factory\Util\Routing\RouteCollectionFactory;
use Noiselabs\ZfDebugModule\Factory\Util\Routing\RouteMatcherFactory;
use Noiselabs\ZfDebugModule\Util\Routing\RouteCollection;
use Noiselabs\ZfDebugModule\Util\Routing\RouteMatcher;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceManager;

class RoutesControllerFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        /** @var RouteCollection $routeCollection */
        $routeCollection = $this
            ->getMockBuilder(RouteCollection::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var RouteMatcher $routeMatcher */
        $routeMatcher = $this
            ->getMockBuilder(RouteMatcher::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var Console $console */
        $console = $this
            ->getMockBuilder(Console::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ServiceManager|PHPUnit_Framework_MockObject_MockObject $serviceManager */
        $serviceManager = $this
            ->getMockBuilder(ServiceManager::class)
            ->getMock();
        $serviceManager
            ->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap([
                [RouteCollectionFactory::SERVICE_NAME, true, $routeCollection],
                [RouteMatcherFactory::SERVICE_NAME, true, $routeMatcher],
                ['Console', true, $console],
            ]));

        /** @var ControllerManager|PHPUnit_Framework_MockObject_MockObject $controllerManager */
        $controllerManager = $this
            ->getMockBuilder(ControllerManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $controllerManager
            ->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));

        $factory = new RoutesControllerFactory();
        $controller = $factory->createService($controllerManager);
        $this->assertInstanceOf(RoutesController::class, $controller);
    }

    public function testServiceNameIsDefined()
    {
        $this->assertTrue(defined(RoutesControllerFactory::class . '::SERVICE_NAME'));
    }
}
