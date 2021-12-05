<?php
/**
 * @project BasePHP Core
 * @file BaseEngine.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Exceptions\Exception;
use Base\Exceptions\InternalError;
use Base\Tools\Resolver;
use Throwable;

/**
 * Class BaseApp is main class of BasePHP Framework.
 * It creates controller and executes its code, renders response returned by controller and checks exceptions.
 */
class BaseEngine
{
    /**
     * Application must be implemented by client.
     * @var BaseApplication
     */
    protected BaseApplication $app;

    /**
     * Resolved controller.
     * @var BaseController|null
     */
    protected ?BaseController $controller = null;

    /**
     * Application constructor.
     * @param BaseApplication $app
     */
    public function __construct(BaseApplication $app)
    {
        $this->app = $app;
    }

    /**
     * Executes request and renders response returned by controller.
     * Handles exceptions thrown by controller.
     */
    public function run(): void
    {
        try
        {
            // Create resolver.
            $resolver = new Resolver();

            // Open client's resources.
            $this->app->open($resolver);

            // Get request.
            $request = $this->app->getRequest();

            // Create router.
            $router = $this->app->getRouter();

            // Get current request path. It may depend on custom rewrite rules of url
            // or custom assumptions of project, so it is delegated to client.
            $currentPath = $this->app->currentRequestPath();

            // Search callback for current request.
            $callbackInfo = $router->callbackInfo($request->method(), $currentPath);

            // Create controller.
            $this->controller = $resolver->create($callbackInfo->className());
            if (!$this->controller instanceof BaseController)
            {
                throw new InternalError("Resolved class name '{$callbackInfo->className()}' is not Controller based class.");
            }

            // Execute method of controller.
            $callback = new Call($this->controller, $callbackInfo->classMethod(), $this->app->getContext(), $callbackInfo->getParams());
            $response = $callback->call();
            if (!$response instanceof Response)
            {
                throw new InternalError("Response doesn't conform to Response interface.");
            }

            // Put response to output buffer.
            $response->display();
        }
        catch (Exception $exception)
        {
            $response = $this->controller?->responseForException($this->app->getContext(), $exception);
            if (!$response) {
                $response = $this->app->responseForException($exception);
            }
            $response->display();
        }
        catch (Throwable $throwable)
        {
            $response = $this->controller?->responseForThrowable($this->app->getContext(), $throwable);
            if (!$response) {
                $response = $this->app->responseForThrowable($throwable);
            }
            $response->display();
        }
        finally
        {
            $this->app->close();
        }
    }
}
