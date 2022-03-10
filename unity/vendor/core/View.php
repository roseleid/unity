<?php namespace Core;

final class View
{
    public final static function render(string $layoutName, string $viewName, array $viewParams, array | object | null $values = null)
    {
        foreach ($viewParams as $key => $value)
        {
            $$key = $value;
            continue;
        }

        include "../app/views/layouts/$layoutName.phtml";
    }
}

?>