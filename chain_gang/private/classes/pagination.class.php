<?php

/* 
SQL OFFSET FORMULA
//Retrieve a page of results
SELECT * FROM bicycles
LIMIT $perpage /own phpvar
OFFSET $offset; /own phpvar

//get total record count 
SELECT COUNT(*) FROM bicycles;

//PHP
$per_page = 20;
$offset = $per_page * ($current_page - 1);
*/

class Pagination
{

    public $current_page;
    public $per_page;
    public $total_count;

    public function __construct($page = 1, $per_page = 20, $total_count = 0)
    {
        $this->current_page = (int) $page;
        $this->per_page = (int) $per_page;
        $this->total_count = (int) $total_count;
    }

    //offset refer to photos in camera roll for formula
    public function offset()
    {
        return $this->per_page * ($this->current_page - 1);
    }

    //rounds up to whole page number so even partial pages are own pages
    public function total_pages()
    {
        return ceil($this->total_count / $this->per_page);
    }

    public function previous_page()
    {
        $prev = $this->current_page - 1;
        return ($prev > 0) ? $prev : false;
    }

    public function next_page()
    {
        $next = $this->current_page + 1;
        return ($next <= $this->total_pages()) ? $next : false;
    }

    public function previous_link($url = "")
    {
        $link = ""; //value for link even if the condition is not met
        if ($this->previous_page() != false) {
            $link = "<a href=\"{$url}?page={$this->previous_page()}\">";
            //html entity for two arrows to the right
            $link .= "&laquo; Previous</a>";
        }
        return $link;
    }

    public function next_link($url = "")
    {
        $link = "";
        if ($this->next_page() != false) {
            $link = "<a href=\"{$url}?page={$this->next_page()}\">";
            //html entity for two arrows to the right
            $link .= "Next &raquo;</a>";
        }
        return $link;
    }

    public function number_links($url="")
    {
        $output_element = "";
        for ($i = 1; $i <= $this->total_pages(); $i++) {
            if ($i == $this->current_page) { //current page we are on is not a link elemnet
                $output_element .= "<span class=\"selected\">{$i}</span>";
            } else {
                $output_element .= "<a href=\"{$url}?page={$i}\">{$i}</a>"; //increment while we go through each one of these with a new link while ascending
            }
        }
        return $output_element;
    }

    public function page_links($url) {
        $output_element = "";
        if ($this->total_pages() > 1) {
            $output_element .= "<div class=\"pagination\">";
            $output_element .= $this->previous_link($url);
            $output_element .= $this->number_links($url);
            $output_element .= $this->next_link($url);
            $output_element .= "</div>";
          }
          return $output_element;
    }
}
