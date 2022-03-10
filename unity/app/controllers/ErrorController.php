<?php namespace App\Controllers;

require_once "../vendor/core/bootstrap.php";

use Core\Controllers\ControllerBase;

use Core\View;

final class ErrorController extends ControllerBase
{
    public final function error(): void
    {
        View::render("MainLayout", "error/404", [
            "icon" => "error",
            "title" => "Oops... Page has not been found",
            "description" => "Seems like it was removed"
        ]);
        return;
    }
}

?>