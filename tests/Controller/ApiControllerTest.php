<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Dashboard\tests\Controller;

use Model\CoreSettings;
use Modules\Admin\Models\AccountPermission;
use phpOMS\Account\Account;
use phpOMS\Account\AccountManager;
use phpOMS\Account\PermissionType;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\DataStorage\Session\HttpSession;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Module\ModuleAbstract;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;
use phpOMS\Uri\HttpUri;
use phpOMS\Utils\TestUtils;

/**
 * @testdox Modules\Dashboard\tests\Controller\ApiControllerTest: Dashboard api controller
 *
 * @internal
 */
final class ApiControllerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;

    /**
     * @var \Modules\Dashboard\Controller\ApiController
     */
    protected ModuleAbstract $module;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->app = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Api';
        };

        $this->app->dbPool         = $GLOBALS['dbpool'];
        $this->app->unitId          = 1;
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings();
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../../Modules/');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../../Web/Api/Hooks.php');
        $this->app->sessionManager = new HttpSession(36000);

        $account = new Account();
        TestUtils::setMember($account, 'id', 1);

        $permission = new AccountPermission();
        $permission->setUnit(1);
        $permission->setApp(2);
        $permission->setPermission(
            PermissionType::READ
            | PermissionType::CREATE
            | PermissionType::MODIFY
            | PermissionType::DELETE
            | PermissionType::PERMISSION
        );

        $account->addPermission($permission);

        $this->app->accountManager->add($account);
        $this->app->router = new WebRouter();

        $this->module = $this->app->moduleManager->get('Dashboard');

        TestUtils::setMember($this->module, 'app', $this->app);
    }

    /**
     * @covers Modules\Dashboard\Controller\ApiController
     * @group module
     */
    public function testApiBoardCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('title', 'Default Board');

        $this->module->apiBoardCreate($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']->getId());
    }

    /**
     * @covers Modules\Dashboard\Controller\ApiController
     * @group module
     */
    public function testApiBoardCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiBoardCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    /**
     * @covers Modules\Dashboard\Controller\ApiController
     * @group module
     */
    public function testApiComponentCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('board', '1');
        $request->setData('order', '2');
        $request->setData('module', 'TestModule');

        $this->module->apiComponentCreate($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']->getId());
    }

    /**
     * @covers Modules\Dashboard\Controller\ApiController
     * @group module
     */
    public function testApiComponentCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiComponentCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }
}
