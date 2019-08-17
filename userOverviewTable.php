<?php
require_once('database.php');
if($_POST['action'] == 'load-users'){
    $userOverviewTable = new userOverviewTable();
    $userOverviewTable->loadUsers($_POST);
    //$userOverviewTable->test($_POST);
}

/**
 * Class userOverviewTable
 */
class userOverviewTable {
    private $DB;

    /**
     * userOverviewTable constructor.
     */
    public function __construct()
    {
        $this->DB = new Database();
    }

    /**
     * @param $columns
     * @param $data
     * @return array
     */
    private function data_output( $columns, $data )
    {
        $out = array();
        for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
            $row = array();
            for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                $column = $columns[$j];
                if ( isset( $column['formatter'] ) ) {
                    $row[ $column['dt'] ] = $column['formatter']( $data[$i][$column['db']], $data[$i] );
                }
                else {
                    $row[ $column['dt'] ] = $data[$i][$columns[$j]['db']];
                }
            }
            $out[] = $row;
        }
        return $out;
    }

    /**
     * For testing purpose only.
     * @param array $datatable_filter
     */
    public function test($datatable_filter = array()){
        $length = $datatable_filter['length'];
        //set the starting point
        $start = $datatable_filter['start'];
        //sort sql
        $sort = null;
        $columns =	array(
            array( 'db' => 'firstname', 'dt' => 0 ),
            array( 'db' => 'surname',  'dt' => 1 ),
            array( 'db' => 'username',  'dt' => 2 ),
            array( 'db' => 'options',  'dt' => 3 ),
        );

        if (isset($datatable_filter['order']))
        {
            foreach ($datatable_filter['order'] AS $sortItem)
            {
                if ($sort)
                    $sort .= ',';

                $sort .= ' '.$columns[$sortItem['column']]['db'].' '.$sortItem['dir'];
            }
        }
        $list_users = $this->DB->getDataTableUsers($start,$length,$sort);
        foreach ($list_users as $user){
            set_error_handler($user[0]);
            set_error_handler($user[1]);
            set_error_handler($user[2]);
        }
    }

    /**
     * @param array $datatable_filter
     */
    public function loadUsers($datatable_filter = array()){
        $data = new \stdClass();
        $data->draw = $datatable_filter['draw'];
        //$data->recordsTotal = 10; //$this->getAllUsersTotalCount();
        $data->recordsTotal = $this->DB->getTotalUserCount();
        $appraisal_year = 0;
        //get columns
        $columns =	array(
            array( 'db' => 'firstname', 'dt' => 0 ),
            array( 'db' => 'surname',  'dt' => 1 ),
            array( 'db' => 'username',  'dt' => 2 ),
            array( 'db' => 'options',  'dt' => 3 ),
        );


        //
        //search object
        // value, regex
        //$search_text = isset($datatable_filter['search']['value']) ? $datatable_filter['search']['value'] : null;
        $data->recordsFiltered = $this->DB->getTotalUserCount(); //$this->getUsersCount();

        //number of results per page
        $length = $datatable_filter['length'];

        //set the starting point
        $start = $datatable_filter['start'];

        //sort sql
        $sort = null;

        if (isset($datatable_filter['order']))
        {
            foreach ($datatable_filter['order'] AS $sortItem)
            {
                if ($sort)
                    $sort .= ',';

                $sort .= ' '.$columns[$sortItem['column']]['db'].' '.$sortItem['dir'];
            }
        }

        $response_arr = array();


        //Query to pull everything from the database
        $list_users = $this->DB->getDataTableUsers($start,$length,$sort);
        foreach($list_users as $user)
        {
        $response_row_arr['firstname'] = $user[0];
        $response_row_arr['surname'] = $user[1];
        $response_row_arr['username'] = $user[2];
        $response_row_arr['options'] = "<button class=\"btn btn-primary a-btn-slide-text viewButton\"><span class=\"glyphicon glyphicon-eye-open\" aria-hidden=\"true\"></span>
                                <span><strong>View</strong></span></button><button class=\"btn btn-primary a-btn-slide-text deleteButton\">
                                <span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span><span><strong>Delete</strong></span></button>
                            <button class=\"btn btn-primary a-btn-slide-text mailButton\"><span class=\"glyphicon glyphicon-send\" aria-hidden=\"true\"></span>
                                <span><strong>Email</strong></span></button>";
        array_push($response_arr,$response_row_arr);
        }
        //datatable data
        $data->data = $this->data_output($columns, $response_arr);
        echo json_encode($data);
    }
}



