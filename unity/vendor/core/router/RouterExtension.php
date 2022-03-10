<?php namespace Core\Router;

require_once "../vendor/core/router/MethodType.php";

trait RouterExtension
{
    protected array $params = array();

    public final function getParams(): array
    {
        return $this->params;
    }

    public final function setParams(array $params): void
    {
        $this->params = $params;
        return;
    }

    public final function getPath(): string
    {
        $path = $_SERVER["REQUEST_URI"] ?? "/";
        $position = strpos($path, "?");

        if ($position)
        {
            return substr($path, $position);
        }

        return $path;
    }

    public final function getMethodType(): string
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public final function isGet(): bool
    {
        return $this->getMethodType() == MethodType::GET;
    }

    public final function isPost(): bool
    {
        return $this->getMethodType() == MethodType::POST;
    }

    public final function sanitize(): array
    {
        $result = array();

        if ($this->isGet())
        {
            foreach ($_GET as $key => $value)
            {
                $result[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                continue;
            }

            return $result;
        }

        foreach ($_POST as $key => $value)
        {
            $result[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            continue;
        }
        
        return $result;
    }
}

?>