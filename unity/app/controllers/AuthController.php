<?php namespace App\Controllers;

require_once "../vendor/core/controllers/ControllerBase.php";

require_once "../app/models/UserModel.php";

require_once "../app/Application.php";

use Core\Controllers\ControllerBase;

use Core\View;

use App\Models\UserModel;

use App\Application;

final class AuthController extends ControllerBase
{
    public final function signIn(): void
    {
        $viewParams = [
            "icon" => "login",
            "title" => "Sign In",
            "description" => "Sign your account in extends your abilities on the resource"
        ];

        if (Application::getAuthService()->isGuest())
        {
            if (Application::getRouter()->isPost())
            {
                $params = Application::getRouter()->sanitize();

                if (! isset($params["email"]) or ! isset($params["password"]))
                {
                    View::render("MainLayout", "auth/SignInView", $viewParams);
                    return;
                }

                $model = UserModel::getSingle(function($item) use($params)
                {
                    if ($params["email"] <> $item["email"])
                    {
                        return false;
                    }

                    if (base64_encode($params["password"]) <> $item["password"])
                    {
                        return false;
                    }

                    return true;
                });

                if ($model)
                {
                    Application::getAuthService()->setUserId($model->id);
                    
                    header("Location: /");
                    return;
                }
            }

            View::render("MainLayout", "auth/SignInView", $viewParams);
            return;
        }

        header("Location: /");
        return;
    }

    public final function signUp(): void
    {
        $viewParams = [
            "icon" => "person_add",
            "title" => "Sign Up",
            "description" => "Let's make an account, ya"
        ];

        if (Application::getRouter()->isPost())
        {
            $params = Application::getRouter()->sanitize();
            $params = array_merge($params, [ "role_id" => 2]);

            if ($params["password"] <> $params["repeat-password"])
            {
                View::render("MainLayout", "auth/SignUpView", $viewParams);
                return;
            }

            $params["password"] = base64_encode($params["password"]);
            unset($params["repeat-password"]);

            $model = UserModel::getSingle(function($item) use($params)
            {
                return $params["username"] == $item["username"];
            });

            if ($model)
            {
                View::render("MainLayout", "auth/SignUpView", $viewParams);
                return;
            }

            $model = UserModel::createModel($params);
            UserModel::create($model);

            header("Location: /auth/signIn");
            return;
        }

        View::render("MainLayout", "auth/SignUpView", $viewParams);
        return;
    }

    public final function signOut(): void
    {
        Application::getAuthService()->setUserId(-1);

        header("Location: /auth/signIn");
        return;
    }
}

?>