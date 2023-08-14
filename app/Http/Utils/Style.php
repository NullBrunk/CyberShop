<?php

/**
 * 
 * Convert \n and Markdown like syntax in displayable HTML 
 *
 * @param string $data    The string to convert
 *
 * @return string         The string with MD like & \n converted into HTML 
 * 
 */

function style(string $data) : string
{

    # Contact messages come from <input>, not textarea.
    # So we don't have some \r\n to convert **when a - is passed as first char**.
    # We need to test this manually

    if(strlen($data) > 2 && $data[0] === "-" && $data[1] === " "){
        $data = substr($data, 1);
        $data = "<i style='margin-left: 0;' class='bi bi-dot'></i> " . $data;

    }


    # Texts coming from product comments, or produt description come from
    # a textarea. So we convert all the lines that starts with - to a line
    # that start with an icon of a dot (sort of a list)

    $data = str_replace(
        "\n-", 
        "\n<i class='bi bi-dot'></i>", 
        $data
    );

    
    # Now, we did what we wanted with the \n we can convert them to <br>
    # so that users can jump lines in their comments / products description

    $data = nl2br($data);
    
    
    # Finally, we apply our markdown like syntax to allow user to type
    # links, bold, italic or strikethrough text

    $conver = [
        "\*\*(.*?)\*\*" => "<strong>$1</strong>",
        "\*(.*?)\*" => "<em>$1</em>",
        "~~(.*?)~~" => "<strike>$1</strike>",
        "!\((.*?)\\)" => "<a href=\"$1\">$1</a>",
    ];

    foreach($conver as $k => $v){
        $data = preg_replace('/' . $k . '/', $v, $data);
    }
    
    # And we return the newly created string
    return $data;
}


