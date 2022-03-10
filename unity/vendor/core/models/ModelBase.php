<?php namespace Core\Models;

abstract class ModelBase
{
    protected static array $source = array();

    public final static function getSingle(callable $action): ?ModelBase
    {
        foreach (static::$source as $item)
        {
            if ($action($item) <> false)
            {
                return static::createModel($item);
            }
        }

        return null;
    }

    public final static function getMany(callable $action): array
    {
        $models = array();

        foreach (static::$source as $item)
        {
            if ($action($item) <> false)
            {
                array_push($models, static::createModel($item));
                continue;
            }
        }

        return $models;
    }

    public final static function getAll(): array
    {
        return static::getMany(function()
        {
            return true;
        });
    }

    public final static function createModel(array $item): ?ModelBase
    {
        $model = new static;

        foreach ($item as $key => $value)
        {
            $model->{ $key } = $value;
            continue;
        }

        return $model;
    }

    public final function commit(callable $action): bool
    {
        foreach (static::$source as &$item)
        {
            if ($action($item))
            {
                foreach ($this as $key => $value)
                {
                    $item[$key] = $value;
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    public abstract static function create(ModelBase $model): void;
    public abstract static function read(): void;
    public abstract static function update(ModelBase $model): void;
    public abstract static function delete(ModelBase $model): void;
}

?>