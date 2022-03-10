<?php namespace App\Controllers;

require_once "../vendor/core/bootstrap.php";

require_once "../app/models/ArticleModel.php";

require_once "../app/Application.php";

use Core\Controllers\ControllerBase;

use Core\View;

use App\Models\ArticleModel;

use App\Application;

final class ArticleController extends ControllerBase
{
    public final function article(): void
    {
        $viewParams = array();

        $params = Application::getRouter()->getParams();

        if (isset($params["id"]))
        {
            $model = ArticleModel::getSingle(function($item) use($params)
            {
                return $params["id"] == $item["id"];
            });

            if ($model)
            {
                if ($model->cover)
                {
                    $viewParams["cover"] = base64_encode($model->cover);
                }

                $viewParams["title"] = $model->title;
                $viewParams["description"] = $model->description;
            }

            View::render("MainLayout", "article/ArticleView", $viewParams, $model);
            return;
        }

        header("Location: /error");
        return;
    }

    public final function list(): void
    {
        $viewParams = [
            "title" => "Articles",
            "description" => "See all the articles on the resource"
        ];
        
        $values = null;

        if (Application::getRouter()->isPost())
        {
            $params = Application::getRouter()->sanitize();
            
            if (isset($params["search-articles"]))
            {
                $values = ArticleModel::getMany(function($item) use($params)
                {
                    $valueA = strtolower($item["title"]);
                    $valueB = strtolower($params["search-articles"]);

                    return str_contains($valueA, $valueB);
                });
            }
        }
        else
        {
            $values = ArticleModel::getAll();
        }

        View::render("MainLayout", "article/ListView", $viewParams, $values);
        return;
    }

    public final function create()
    {
        $viewParams = [
            "icon" => "create",
            "title" => "Writing an article",
            "description" => "Please, fill the fields"
        ];

        if (Application::getRouter()->isPost())
        {
            $params = Application::getRouter()->sanitize();

            $model = ArticleModel::createModel($params);
            $model->user_id = Application::getAuthService()->getUserId();

            ArticleModel::create($model);

            header("Location: /articles");
            return;
        }

        View::render("MainLayout", "article/operation/CreateView", $viewParams);
        return;
    }

    public final function delete(): void
    {
        $params = Application::getRouter()->getParams();

        $model = ArticleModel::getSingle(function($item) use($params)
        {
            return $params["id"] == $item["id"];
        });
        ArticleModel::delete($model);

        header("Location: /articles");
        return;
    }

    public final function edit(): void
    {
        $viewParams = [
            "icon" => "edit",
            "title" => "Editing an article",
            "description" => "Please, fill the fields"
        ];

        $params = Application::getRouter()->getParams();

        $model = ArticleModel::getSingle(function($item) use($params)
        {
            return $params["id"] == $item["id"];
        });

        if (Application::getRouter()->isPost())
        {
            $params = array_merge($params, Application::getRouter()->sanitize());

            $model = ArticleModel::createModel($params);
            ArticleModel::update($model);

            header("Location: /article/$model->id");
            return;
        }

        View::render("MainLayout", "article/operation/EditView", $viewParams, $model);
        return;
    }
}

?>