<?php namespace Core\Auth;

use App\Models\UserModel;

final class AuthService
{
    public final function getUserId(): int
    {
        if (isset($_SESSION["user_id"]))
        {
            return $_SESSION["user_id"];
        }

        return -1;
    }

    public final function setUserId(int $id): void
    {
        $_SESSION["user_id"] = $id;
    }

    public final function isGuest(): bool
    {
        return $this->getUserId() == -1;
    }

    public final function isAdmin(): bool
    {
        return $this->roleId() == 1;
    }

    public final function isModerator(): bool
    {
        return $this->roleId() == 3;
    }

    public final function isDef(): bool
    {
        return $this->roleId() == 2;
    }

    protected final function roleId(): int
    {
        $model = UserModel::getSingle(function($item)
        {
            return $this->getUserId() == $item["id"];
        });

        return $model->role_id;
    }
}

?>