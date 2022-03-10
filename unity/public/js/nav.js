$(document).ready(function()
{
    const header = $("header");
    
    const navButton = $("span#header__navbutton");
    const nav = $("nav");

    nav.css("top", function()
    {
        let value = parseInt(header.css("height"));
        value = value + 16;
        value = value + "px";

        return value;
    });

    $(document).on("click", "span#header__navbutton", function()
    {
        if (nav.attr("active") == "true")
        {
            navButton.text("menu");
            nav.attr("active", "false");
            return;
        }
        
        navButton.text("menu_open");
        nav.attr("active", "true");
    });

    return;
});