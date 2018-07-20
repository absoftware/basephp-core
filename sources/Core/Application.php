<?php
namespace Base\Core;

use Base\Core\Exceptions\Exception;
use Base\Core\Exceptions\InternalException;

class Application
{
    protected $delegate;
    protected $config;
    protected $request;
    
    public function __construct(ApplicationDelegate $delegate, Config $config)
    {
        $this->delegate = $delegate;
        $this->config = $config;
        $this->request = new Request();
    }
    
    public function run()
    {        
        try
        {
            // Open output buffer.
            ob_start();
            
            // Create instance of router.
            $router = new Router();
            
            // Register all routes known in project. This operation is delegated to client.
            $this->delegate->registerRoutes($router);
            
            // Get current request path. It may depend on custom rewrite rules of url
            // or custom assumptions of project so it is delegated to client.
            $currentPath = $this->delegate->currentRequestPath($this->request);
            
            // Execute path and get response from controler.
            $response = $router->execute($currentPath);
            if (!$response instanceof Response)
            {
                throw new InternalException("Response doesn't conform to Response interface.");
            }
            
            // Put response to output buffer.
            $response->output();
            
            // TODO: Close all resources before flush. In case of errors we will see this in output buffer.
            
            // Flush output buffer.
            ob_end_flush();
        }
        catch (InternalException $e)
        {
            // Clean output buffer.
            ob_end_clean();
            
            // TODO: Handle internal exception.
        }
        catch (Exception $e)
        {
            // Clean output buffer.
            ob_end_clean();
            
            // TODO: Handle generic exception.
        }
        catch (Throwable $t)
        {
            // Clean output buffer.
            ob_end_clean();
            
            // TODO: Handle unexpected exception.
        }
        finally
        {
            // TODO: Close all resources.
        }
    }
}
