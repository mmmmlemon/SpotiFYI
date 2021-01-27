
    //trumblewyg paragraph margin workaround
    var paragraph_workaround = function () {
        var p_fixes =  $(".p_fix"); //get all elements that require a workaround
        for(p of p_fixes)
        {
            //get all p elements in
            var elems = $(p).find("p");  
            //remove bottom margin
            for(el of elems)
            {
                $(el).css("margin-bottom" ,"0")
                $(el).css("margin-top" ,"2px")
            }
        }
    }