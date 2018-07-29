<?php
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
     * Application constructor.
     * @param ApplicationDelegate $delegate
     * @param Config $config
     */
    public function __construct(ApplicationDelegate $delegate, Config $config)
    {
        $this->delegate = $delegate;
        $this->config = $config;
        $this->request = new Request();
    }

    /**
     * Executes request and renders response returned by controller.
     * Handles exceptions thrown by controller.
     */
    public function run(): void
    {
        try
        {
            // Create instance of router.
            $router = new Router();
            
            // Register all routes known in project. This operation is delegated to client.
            $this->delegate->registerRoutes($router);
            
            // Get current request path. It may depend on custom rewrite rules of url
            // or custom assumptions of project so it is delegated to client.
            $currentPath = $this->delegate->currentRequestPath($this->request);
            
            // Execute path and get response from controller.
            $response = $router->execute($this->request->method(), $currentPath);
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
            $this->close();
        }
    }

    protected function close()
    {

    }
}
