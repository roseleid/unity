$(document).ready(function()
{
    $(document).on("scroll", function()
    {
        if (window.scrollY > 32)
        {
            $("header").attr("active", true);
            return;
        }

        $("header").attr("active", false);
        return;
    });

    return;
});