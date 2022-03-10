<?php namespace App\Controllers;

require_once "../vendor/core/controllers/ControllerBase.php";

require_once "../app/models/UserModel.php";

require_once "../app/Application.php";

use Core\Controllers\ControllerBase;

use Core\View;

use App\Models\UserModel;

use App\Application;

final class AccountController extends ControllerBase
{
    public final function account(): void
    {
        $viewParams = [
            "icon" => "account_circle",
            "title" => "Account Overview",
            "description" => "Review your account and make changes if necessary"
        ];

        $params = Application::getRouter()->getParams();

        if (isset($params["id"]))
        {
            $model = UserModel::getSingle(function($item) use($params)
            {
                return $item["id"] == $params["id"];
            });

            if ($model)
            {
                $viewParams = [
                    "icon" => "account_circle",
                    "title" => "Account Overview",
                    "description" => "This is an account of another user"
                ];

                View::render("MainLayout", "account/AccountView", $viewParams, $model);
                return;
            }
        }

        if (Application::getAuthService()->isGuest())
        {
            header("Location: /auth/signIn");
            return;
        }

        $model = UserModel::getSingle(function($item)
        {
            return $item["id"] == Application::getAuthService()->getUserId();
        });

        View::render("MainLayout", "account/AccountView", $viewParams, $model);
        return;
    }

    public final function edit(): void
    {
        $viewParams = [
            "icon" => "edit",
            "title" => "Edit Account",
            "description" => "Here you can make changes into your account"
        ];

        $model = UserModel::getSingle(function($item)
        {
            return Application::getAuthService()->getUserId() == $item["id"];
        });

        if (Application::getRouter()->isPost())
        {
            $params = Application::getRouter()->sanitize();

            if ($params["password"])
            {
                if ($params["password"] <> $params["repeat-password"])
                {
                    View::render("MainLayout", "account/operation/EditView", $viewParams, $model);
                    return;
                }
            }
            else
            {
                $params["password"] = $model->password;
            }

            $params["password"] = base64_encode($params["password"]);
            unset($params["repeat-password"]);

            $params["id"] = $model->id;
            $params["role_id"] = $model->role_id;

            $model = UserModel::createModel($params);
            UserModel::update($model);

            header("Location: /account");
            return;
        }

        View::render("MainLayout", "account/operation/EditView", $viewParams, $model);
        return;
    }

    public final function delete(): void
    {
        $viewParams = [
            "icon" => "delete",
            "title" => "Deleting Account",
            "description" => "You are about to delete your account"
        ];

        if (Application::getRouter()->isPost())
        {
            $model = UserModel::getSingle(function($item)
            {
                return Application::getAuthService()->getUserId() == $item["id"];
            });
            UserModel::delete($model);

            Application::getAuthService()->setUserId(-1);

            header("Location: /goodbye");
            return;
        }

        View::render("MainLayout", "account/operation/DeleteView", $viewParams);
        return;
    }
}

?>