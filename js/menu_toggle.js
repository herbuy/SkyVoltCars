window.addEventListener(
    'load',
    function(){
        document.getElementById("cmp_admin_menu_toggle_button").onclick = function(){
            var menuOffsetWidth = document.getElementById("cmp_admin_menu").offsetWidth;
            var test = menuOffsetWidth == 0;
            var newDisplay = test ? "inline-block" : "none";
            //alert(document.getElementById("cmp_admin_menu").offsetWidth);
            document.getElementById("cmp_admin_menu").style.display = newDisplay;
        };
    }
);
