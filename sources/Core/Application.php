<?php
/**
 * @project BasePHP Core
 * @file Application.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Exceptions\Exception;
use Base\Exceptions\InternalError;
use Base\Responses\Response;

/**
 * Class Application is main class of BasePHP Framework.
 * It creates all dependencies including router and request objects,
 * creates controller and executes its code,
 * renders response returned by controller and checks exceptions.
 * @package Base\Core
 */
class Application
{
    /**
     * Delegate of application must be implemented by client.
     * @var ApplicationDelegate
     */
    protected $delegate;

    /**
     * Instance of configuration. It must be delivered by client.
     * @var Config
     */
    protected $config;

    /**
     * Request.
     * @var Request
     */
    protected $request;

    /**
     * Session.
     * @var Session
     */
    protected $session;

    /**
     * Application constructor.
     * @param ApplicationDelegate $delegate
     * @param Config $config
     * @param Session $session
     */
    public function __construct(ApplicationDelegate $delegate, Config $config, Session $session = null)
    {
        $this->delegate = $delegate;
        $this->config = $config;
        $this->request = new Request($this->config->ports());
        $this->session = $session ?? new Session($this->config->sessionTime(), $this->delegate->sessionDomain($this->request));
    }

    /**
     * Executes request and renders response returned by controller.
     * Handles exceptions thrown by controller.
     */
    public function run(): void
    {
        try
        {
            // Open client's resources.
            $this->delegate->open();

            // Create instance of router.
            $router = new Router();
            
            // Register all routes known in project. This operation is delegated to client.
            $this->delegate->registerRoutes($router);
            
            // Get current request path. It may depend on custom rewrite rules of url
            // or custom assumptions of project so it is delegated to client.
            $currentPath = $this->delegate->currentRequestPath($this->request);
            
            // Search callback for current request.
            $callback = $router->callback($this->request->method(), $currentPath);

            // Add dependencies to controller.
            $controller = $callback->controller();
            $controller->setRequestObject($this->request);
            $controller->setSessionObject($this->session);

            // Execute method of controller.
            $response = $callback->call();
            if (!$response instanceof Response)
            {
                throw new InternalError("Response doesn't conform to Response interface.");
            }
            
            // Put response to output buffer.
            $response->display();
        }
        catch (Exception $e)
        {
            $response = $this->delegate->responseForException($this->request, $e);
            $response->display();
        }
        catch (\Throwable $t)
        {
            $response = $this->delegate->responseForThrowable($this->request, $t);
            $response->display();
        }
        finally
        {
            $this->session->close();
            $this->delegate->close();
        }
    }
}
