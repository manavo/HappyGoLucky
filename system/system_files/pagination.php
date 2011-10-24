<?php

function pagination($base_url, $total_number, $items_per_page, $current_page = 1, $separator = '')
{
    if (!$current_page)
    {
        $current_page = 1;
    }
    
    $number_of_pages = ((int)($total_number / $items_per_page)) + 1;

    if (substr($base_url, -1, 1) != '/')
    {
        $base_url .= '/';
    }

    $links = array();

    if ($number_of_pages > 1)
    {
        $links[] = '<a href="'.$base_url.'">&laquo;First</a>';
        $link = '<a href="';
        if ($current_page > 1)
        {
            $link .= $base_url.($current_page-1);
        }
        else
        {
            $link .= '#';
        }
        $link .= '">&lt;Prev</a>';
        $links[] = $link;
        
        for ($i = 1; $i <= $number_of_pages; $i++)
        {
            $link = '<a href="'.$base_url.$i.'"';
            if ($i == $current_page)
            {
                $link .= ' class="active"';
            }
            $link .= '>'.$i.'</a>';
            $links[] = $link;
        }

        $link = '<a href="';
        if ($current_page < $number_of_pages)
        {
            $link .= $base_url.($current_page+1);
        }
        else
        {
            $link .= '#';
        }
        $link .= '">Next&gt;</a>';
        $links[] = $link;
        $links[] = '<a href="'.$base_url.$number_of_pages.'">Last&raquo;</a>';
    }

    $html = '<div class="pagination">'.implode($separator, $links).'</div>';

    return $html;
}

?>