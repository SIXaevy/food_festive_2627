<?php
    define('TOTAL_RECORDS_PER_PAGE', 10); // define the number of records to display per page as a constant

    function get_offset($page_no) {
        return ($page_no - 1) * TOTAL_RECORDS_PER_PAGE; //determine the starting point (index) of records to be fetched
    }

    function pagination($total_records, $page_no) {
        $previous_page = $page_no - 1; // previous page
        $next_page = $page_no + 1; // next page

        $total_no_of_pages = ceil($total_records / TOTAL_RECORDS_PER_PAGE); // determines the number of pages to be displayed
    
        return [
            'total_records' => $total_records,
            'total_no_of_pages' => $total_no_of_pages,
            'previous_page' => $previous_page,
            'next_page' => $next_page,
            'current_page' => $page_no
        ];
    }
?>