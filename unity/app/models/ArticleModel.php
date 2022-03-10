<?php namespace App\Models;

require_once "../vendor/core/bootstrap.php";

use App\Application;

use Core\Models\ModelBase;

final class ArticleModel extends ModelBase
{
    protected static array $source = array();

    public final static function read(): void
    {
        static::$source = Application::getDatabaseService()->get()
            ->query("SELECT * FROM articles")
            ->fetchAll(\PDO::FETCH_ASSOC);

        return;
    }

    public final static function create(ModelBase $model): void
    {
        $values = array();

        foreach ($model as $key => $value)
        {
            $values[$key] = $value;
            continue;
        }

        Application::getDatabaseService()->get()
            ->prepare(" INSERT INTO articles
                                    (
                                        user_id,
                                        title,
                                        description,
                                        value,
                                        cover
                                    )
                        VALUES      (
                                        :user_id,
                                        :title,
                                        :description,
                                        :value,
                                        :cover
                                    )")
            ->execute($values);

        return;
    }

    public final static function update(ModelBase $model): void
    {
        $values = array();

        foreach ($model as $key => $value)
        {
            $values[$key] = $value;
            continue;
        }

        echo "<pre>";
        print_r($values);
        echo "</pre>";
        
        Application::getDatabaseService()->get()
            ->prepare(" UPDATE  articles
                        SET     title       = :title,
                                description = :description,
                                value       = :value,
                                cover       = :cover
                        WHERE   id          = :id
            ")
            ->execute($values);
        return;
    }

    public final static function delete(ModelBase $model): void
    {
        Application::getDatabaseService()->get()
            ->query("   DELETE FROM articles
                        WHERE   id = $model->id");
        return;
    }
}

?>