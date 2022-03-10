<?php namespace Core\Controllers;

require_once "../vendor/core/bootstrap.php";

use Core\Exceptions\BadActionException;

use Core\View;

abstract class ControllerBase
{
    protected string $action = "unimplemented";

    public final function executeAction(): void
    {
        $this->{ $this->action }();
        return;
    }

    public final function setAction(string $action): void
    {
        if (method_exists($this, $action))
        {
            $this->action = $action;
            return;
        }

        throw new BadActionException();
    }

    public final function unimplemented(): void
    {
        View::render("MainLayout", "errors/404",
        [
            "title" => "Unimplemented functionality",
            "description" => "We are sorry for this message, but this page has not been implemented yet"
        ]);
    }
}

?>