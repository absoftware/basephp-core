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
use Base\Tools\Resolver;

/**
 * Class Application is main class of BasePHP Framework.
 * It creates controller and executes its code, renders response returned by controller and checks exceptions.
 * @package Base\Core
 */
class Application
{
    /**
     * Delegate of application must be implemented by client.
     * @var ApplicationDelegate
     */
    protected ApplicationDelegate $delegate;

    /**
     * Instance of configuration. It must be delivered by client.
     * @var Config
     */
    protected Config $config;

    /**
     * Session.
     * @var Session|null
     */
    protected ?Session $session;

    /**
     * Resolved controller.
     * @var Controller|null
     */
    protected ?Controller $controller = null;

    /**
     * Application constructor.
     * @param ApplicationDelegate $delegate
     * @param Config $config
     * @param Session|null $session
     */
    public function __construct(ApplicationDelegate $delegate, Config $config, Session $session = null)
    {
        $this->delegate = $delegate;
        $this->config = $config;
        $this->session = $session;
    }

    /**
     * Executes request and renders response returned by controller.
     * Handles exceptions thrown by controller.
     */
    public function run(): void
    {
        // Initialize request.
        $request = null;

        try
        {
            // Create request.
            $request = new Request($this->config->ports());

            // Create resolver.
            $resolver = new Resolver();

            // Open client's resources.
            $this->delegate->open($this->config, $request, $resolver);

            // Create session if it was not delivered from client.
            $this->session = $this->session ?? new Session($this->config->sessionTime(), $this->delegate->sessionDomain($request));

            // Create router.
            $router = $this->delegate->createRouter();

            // Get current request path. It may depend on custom rewrite rules of url
            // or custom assumptions of project so it is delegated to client.
            $currentPath = $this->delegate->currentRequestPath($request);

            // Create subpage.
            $subpage = $this->delegate->createSubpage($request, $this->session);

            // Create visitor.
            $visitor = $this->delegate->createVisitor($request, $this->session, $subpage);
            
            // Search callback for current request.
            $callbackInfo = $router->callbackInfo($request->method(), $currentPath);

            // Create authorization service.
            $authorization = $this->delegate->createAuthorizationService();
            $authorization->authorize($request, $this->session, $subpage, $visitor, $callbackInfo->authorizationIds());

            // Set defaults for resolver.
            $resolver->setDefaultTypeValue(Authorization::class, $authorization);
            $resolver->setDefaultTypeValue(Request::class, $request);
            $resolver->setDefaultTypeValue(Session::class, $this->session);
            $resolver->setDefaultTypeValue(Subpage::class, $subpage);
            $resolver->setDefaultTypeValue(Visitor::class, $visitor);
            $resolver->setDefaultTypeValue(Resolver::class, $resolver);

            // Set common resources for controllers. It helps to create new controllers and views easily.
            Common::singleton()->set(Controller::COMMON_REQUEST, $request);
            Common::singleton()->set(Controller::COMMON_RESOLVER, $resolver);
            Common::singleton()->set(Controller::COMMON_SESSION, $this->session);
            Common::singleton()->set(Controller::COMMON_VISITOR, $visitor);

            // Create controller.
            $this->controller = $resolver->create($callbackInfo->className());
            if (!$this->controller instanceof Controller)
            {
                throw new InternalError("Resolved class name '{$callbackInfo->className()}' is not Controller based class.");
            }

            // Execute method of controller.
            $callback = new Call($this->controller, $callbackInfo->classMethod(), $callbackInfo->params());
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
            $response = $this->controller?->responseForException($exception);
            if (!$response) {
                $response = $this->delegate->responseForException($request, $exception);
            }
            $response->display();
        }
        catch (\Throwable $throwable)
        {
            $response = $this->controller?->responseForThrowable($throwable);
            if (!$response) {
                $response = $this->delegate->responseForThrowable($request, $throwable);
            }
            $response->display();
        }
        finally
        {
            $this->session->close();
            $this->delegate->close();
        }
    }
}
