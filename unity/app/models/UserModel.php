<?php namespace App\Models;

require_once "../vendor/core/bootstrap.php";

use Core\Models\ModelBase;

use App\Application;

final class UserModel extends ModelBase
{
    protected static array $source = array();

    public final static function create(ModelBase $model): void
    {
        $values = array();

        foreach ($model as $key => $value)
        {
            $values[$key] = $value;
            continue;
        }

        Application::getDatabaseService()->get()
            ->prepare(" INSERT INTO users
                                    (
                                        role_id,
                                        gender_id,
                                        name,
                                        surname,
                                        middlename,
                                        username,
                                        password,
                                        birthDate,
                                        email,
                                        phoneNumber,
                                        image
                                    )
                        VALUES      (
                                        :role_id,
                                        :gender_id,
                                        :name,
                                        :surname,
                                        :middlename,
                                        :username,
                                        :password,
                                        :birthDate,
                                        :email,
                                        :phoneNumber,
                                        :image
                                    )")
            ->execute($values);

        return;
    }

    public final static function read(): void
    {
        static::$source = Application::getDatabaseService()->get()
            ->query("   SELECT  users.*, 
                                roles.name as roleName,
                                genders.name as genderName
                        FROM    users 
                            INNER JOIN  roles
                                    ON  users.role_id = roles.id
                            INNER JOIN  genders
                                    ON  users.gender_id = genders.id
            ")
            ->fetchAll(\PDO::FETCH_ASSOC);

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
        
        Application::getDatabaseService()->get()
            ->prepare(" UPDATE  users
                        SET     role_id     = :role_id,
                                gender_id   = :gender_id,
                                name        = :name,
                                surname     = :surname,
                                middlename  = :middlename,
                                username    = :username,
                                password    = :password,
                                birthDate   = :birthDate,
                                email       = :email,
                                phoneNumber = :phoneNumber,
                                image       = :image
                        WHERE   id          = :id
            ")
            ->execute($values);
        return;
    }

    public final static function delete(ModelBase $model): void
    {
        Application::getDatabaseService()->get()
            ->query("   DELETE FROM users
                        WHERE   id = $model->id");
        return;
    }
}

?>