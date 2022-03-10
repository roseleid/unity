<?php namespace Core\Router;

require_once "../vendor/core/exceptions/BadRouteException.php";
require_once "../vendor/core/router/RouterExtension.php";

use App\Application;
use Core\Exceptions\BadRouteException;
use Core\View;
use Exception;

class Router
{
    use RouterExtension;

    protected array $routes = array();

    public final function getRoute(string $methodType, string $path): callable | array
    {
        return $this->routes[$methodType][$path];
    }

    public final function setRoute(string $methodType, string $path, callable | array $value): void
    {
        $this->routes[$methodType][$path] = $value;
        return;
    }

    public final function getValue(): callable | array | null
    {
        $methodType = $this->getMethodType();
        $path = trim($this->getPath(), "/");

        $routes = $this->routes[$methodType] ?? array();

        foreach ($routes as $routePath => $routeValue)
        {
            $routePath = trim($routePath, "/");
            $names = array();

            if (! $routePath)
            {
                continue;
            }

            if (preg_match_all("/\{(\w+)(:[^}]+)?}/", $routePath, $matches))
            {
                $names = $matches[1];
            }

            $regex = preg_replace_callback("/\{(\w+)(:[^}]+)?}/", function($match)
            {
                $result = substr($match[2], 1);

                return isset($match[2]) ? "({$result})" : "({\w+})";
            }, $routePath);

            $regex = "@^$regex$@";

            if (preg_match_all($regex, $path, $matches))
            {
                $values = array();

                for ($index = 1; $index < count($matches); $index++)
                {
                    array_push($values, $matches[$index][0]);
                    continue;
                }

                $this->setParams(array_combine($names, $values));
                return $routeValue;
            }
        }

        return null;
    }

    public final function resolve(): void
    {
        $methodType = $this->getMethodType();
        $path = $this->getPath();
        
        $value = $this->routes[$methodType][$path] ?? null;

        if (! $value)
        {
            $value = $this->getValue();

            if (! $value)
            {
                try
                {
                    throw new BadRouteException();
                }
                catch (BadRouteException $exception)
                {

                    print_r($this->getParams());
                    header("Location: /error");
                }
            }
        }

        if (is_array($value))
        {
            $controller = new $value[0]();
            $controller->setAction($value[1]);
            $controller->executeAction();

            Application::setController($controller);
            return;
        }

        $value($this);
        return;
    }
}

?>