<?php namespace App\Controllers;

require_once "../vendor/core/bootstrap.php";

require_once "../app/models/UserModel.php";

require_once "../app/Application.php";

use Core\Controllers\ControllerBase;

use Core\View;

use App\Models\UserModel;

use App\Application;

final class HomeController extends ControllerBase
{
    public final function home(): void
    {
        $viewParams = [
            "title" => "Hello, stranger",
            "description" => "Let's learn about what's here"
        ];

        if (! Application::getAuthService()->isGuest())
        {
            $model = UserModel::getSingle(function($item)
            {
                if (Application::getAuthService()->getUserId() != $item["id"])
                {
                    return false;
                }

                return true;
            });

            if ($model)
            {
                $viewParams["title"] = "Hello, $model->username";
            }
        }

        View::render("MainLayout", "HomeView", $viewParams);
        return;
    }

    public final function goodbye(): void
    {
        $viewParams = [
            "icon" => "mood_bad",
            "title" => "Goodbye, stranger already",
            "description" => "We are sorry for being incapable to help you to reach your goal"
        ];
        
        View::render("MainLayout", "GoodbyeView", $viewParams);
        return;
    }
}

?>