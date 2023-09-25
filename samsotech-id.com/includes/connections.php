<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// Create connection/////////////////////////////////////////////////////////////////////////////////////////////////

$GLOBALS['$servername'] = "localhost";
$GLOBALS['$username'] = "webadminsam";
$GLOBALS['$password'] = "S@ms0Tech#20";
$GLOBALS['$dbname'] = "db_samsotech_mini";

///////////////////////////////////////FUNCTION TO EXECUTE QUERY Like Insert,Update,Delete///////////////////////////////////////

function execute($query) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = $query;

    try {
        if ($conn->query($sql) === TRUE) {
            return "Query Executed Successfully";
        }
        else {
            return "Error";
        }
    } catch (Exception $e) {
        return $e;
    }

    $conn->close();
}

////////////////////////////////////////////////FUNCTION ENDS HERE
///////////////////////////////////////FUNCTION TO GET LAST INSERTED ID/////////////////////////////////////////////////////////////////////////////////////////////////
function get_insertid($query) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = $query;

    try {
        if ($conn->query($sql) === TRUE) {

            $last_id = $conn->insert_id;
            return $last_id;
        }
        else {
            return "Error";
        }
    } catch (Exception $e) {
        return $e;
    }


    $conn->close();
}

///////////////////////////////////////////////FUNCTION ENDS HERE/////////////////////
////////////////////////////////////////////////FUNCTION TO SELECT ALL DATA from table name////////////////////////////////////////////////////////////
function get_alldata($table_name) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "Select * from " . $table_name;
    try {
        $result = $conn->query($sql);
        return $result;
    } catch (Exception $e) {
        return $e;
    }


    $conn->close();
}

////////////////////////////////FUNCTION ENDS HERE////////////////////////
//////////////////////////////////////// FUNCTION TO GET ONE column FROM TABLE/////////////////////////////////////////////////////////////////////////////////////////////////
function get_onedata($table_name, $colum_name) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $sql = "Select " . $colum_name . " from " . $table_name;
    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

/////////////////////////////////////////////FUNCTION ENDS HERE/////////////////////
//////////////////////////////////////////////FUNCTION TO SELECT with a limit//////////////////////////
function get_data_limit($table, $limit) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM " . $table . " Limit " . $limit;
    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

//////////////////////////////////////FUNCTION ENDS HERE
/////////////////////////////////FUNCTION TO SELECT specific columns with a limit column name seperated by , (comma) /////////////////////////////////////////////////////////////////////////////////////////////////
function get_columns_limit($colums, $table, $limit) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT " . $colums . " FROM " . $table . " Limit " . $limit;
    try {
        $result = $conn->query($sql);
        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

/////////////////////////////////////////////////////////FUNCTION ENDS HERE
////////////////////////////////////////////////////FUNCTION TO SELECT within a limit//////////////////////////////////////////
//eg:return only limited records, start on record $start_limit (OFFSET $limit)": ->from $start_limit, to count $limit
function get_within_limit($columns, $table, $limit, $start_limit) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "Select " . $columns . " from " . $table . "Limit " . $start_limit . " OFFSET " . $offset_limit;
    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

//////////////////////////////////////////////FUNCTION ENDS HERE
////////////////////////////////FUNCTION TO SELECT distinct column names seperated by ,(comma)    /////////////////////////////////////////////////////////////////////////////////////////////////

function get_distinct($colum_name, $table) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT DISTINCT " . $colum_name . " FROM " . $table;
//$sql = $query."Limit ".$start_limit." OFFSET ".$offset_limit
    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

/////////////////////////////////FUNCTION ENDS HERE
//////////////////////////FUNCTION TO SELECT count column name     /////////////////////////////////////////////////////////////////////////////////////////////////

function get_count_col($colum_name, $table) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT COUNT(" . $colum_name . ") AS `count` FROM " . $table;
//$sql = $query."Limit ".$start_limit." OFFSET ".$offset_limit
    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

/////////////////////////////////////////FUNCTION ENDS HERE//////////////////////
//////////////////////////////////////// LOGIN function  //////////////////////////////

function login($table, $colum_name1, $value1, $colum_name2, $value2) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM " . $table . " WHERE " . $colum_name1 . "='" . $value1 . "' AND " . $colum_name2 . "='" . $value2 . "' ";

    try {
        $result = $conn->query($sql);
        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

////////////////////////////////////////////////FUNCTION ENDS HERE///////////
///////////////////////////////////////////FUNCTION TO SELECT WITH AND CONDITION   ////////////////////////////////////////////////////

function get_and_cond($table, $colum_name1, $value1, $colum_name2, $value2) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM " . $table . " WHERE " . $colum_name1 . "='" . $value1 . "' AND " . $colum_name2 . "='" . $value2 . "'";

    try {
        $result = $conn->query($sql);
        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

///////////////////////////////////////////////FUNCTION ENDS HERE///////////////////////
////////////////////////////////////////////FUNCTION TO SELECT WITH OR CONDITION  /////////////////////////////////////////////////////////////////////////////////////////////////

function get_or_cond($table, $colum_name1, $value1, $colum_name2, $value2) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM " . $table . " WHERE " . $colum_name1 . "='" . $value1 . "' OR " . $colum_name2 . "='" . $value2 . "'";

    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

///////////////////////////////////////////////////FUNCTION ENDS HERE/////////////////////////
//////////////////////////////////////////FUNCTION TO SELECT WITH custom 'where' condition  ///////////////////////////

function get_where_cond($table, $where) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM " . $table . " where " . $where;

    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

/////////////////////////////////////////////////////FUNCTION ENDS HERE
///////////////////////////////////////////////FUNCTION TO SELECT WITH order by  ///////////

function get_orderby($table, $colum_name) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM " . $table . " ORDER BY " . $colum_name;

    try {
        $result = $conn->query($sql);
        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

/////////////////////////////////////////////////FUNCTION ENDS HERE////////////
///////////////////////////////////FUNCTION TO SELECT WITH custom 'order by' condition ////////////////////

function get_pattern_LIKE($table, $colum, $cond) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM " . $table . "WHERE " . $colum . " LIKE '%" . $cond . "%'";

    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

////////////////////////////////////////////////////FUNCTION ENDS HERE///////////////////////////////////////////
///////////////////////////////FUNCTION TO SELECT WITH IN condition columns seperated by comma(,) ////////////////////////

function get_In_cond($table, $colum, $cond) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM " . $table . "WHERE " . $colum . " IN (" . $cond . ")";

    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

//////////////////////////////////////////FUNCTION ENDS HERE/////////////////////////////////////////////////////
/////////////////////////////////////FUNCTION TO SELECT WITH Between condition  //////////////////

function get_btw_cond($table, $colum, $cond1, $cond2) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM " . $table . "WHERE " . $colum . " BETWEEN " . $cond . " AND " . $cond2;

    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

///////////////////////////////////////////////////FUNCTION ENDS HERE/////////////////////////////////////
//////////////////////////////////////////////////////////FUNCTION TO INNER JOIN ////////////////////////

function get_INNER_JOIN($colums, $table1, $table2, $oncond) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT " . $colums . " FROM " . $table1 . " INNER JOIN " . $table2 . " ON " . $oncond;

    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

////////////////////////////////////////////FUNCTION ENDS HERE//////////////////////////////////////
////////////////////////////////////////////////FUNCTION TO LEFT JOIN  //////////////////////

function get_LEFT_JOIN($colums, $table1, $table2, $oncond) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT " . $colums . " FROM " . $table1 . " LEFT JOIN " . $table2 . " ON " . $oncond;

    try {
        $result = $conn->query($sql);
        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

////////////////////////////////FUNCTION ENDS HERE///////////////////////////
/////////////////////////////////////////FUNCTION TO RIGHT JOIN  ////////////////////////////

function get_RIGHT_JOIN($colums, $table1, $table2, $oncond) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT " . $colums . " FROM " . $table1 . " RIGHT JOIN " . $table2 . " ON " . $oncond;

    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

//////////////////////////////////FUNCTION ENDS HERE////////////////////////////
/////////////////////////////////FUNCTION TO RIGHT JOIN  ///////////////////////////

function get_FULL_JOIN($colums, $table1, $table2, $oncond) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT " . $colums . " FROM " . $table1 . " FULL OUTER JOIN " . $table2 . " ON " . $oncond;

    try {
        $result = $conn->query($sql);
        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

function get_max_col($colum_name, $table) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT MAX(" . $colum_name . ") AS `max` FROM " . $table;
//$sql = $query."Limit ".$start_limit." OFFSET ".$offset_limit
    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

function get_min_col($colum_name, $table) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT min(" . $colum_name . ") AS `min` FROM " . $table;
//$sql = $query."Limit ".$start_limit." OFFSET ".$offset_limit
    try {
        $result = $conn->query($sql);

        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

///////////////////////////////////////////////FUNCTION TO SELECT WITH order by  ///////////

function get_alldata_order($table, $sort) {

    $conn = new mysqli($GLOBALS['$servername'], $GLOBALS['$username'], $GLOBALS['$password'], $GLOBALS['$dbname']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM " . $table . " ORDER BY " . $sort;

    try {
        $result = $conn->query($sql);
        return $result;
    } catch (Exception $e) {
        return $e;
    }
    $conn->close();
}

///////////////////////////////////////////////FUNCTION TO SELECT WITH order by  ///////////
?>