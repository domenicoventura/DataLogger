<?php
class Event{
  
    // database connection and table name
    private $conn;
    private $table_name = "event";
  
    // object properties
    public $id_event;
    public $id_site;
    public $datatime;
    public $temp_indoor;
    public $temp_outdoor;
    public $humid_indoor;
    public $humid_outdoor;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read events
    function read(){
    
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " p
                ORDER BY
                    p.datatime ASC
                LIMIT
                    1000";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create event
    function create(){
    
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET id_site=:id_site, temp_indoor=:temp_indoor, humid_indoor=:humid_indoor";
        /*    
        $query = "INSERT INTO " 
                . $this->table_name 
                . " SET id_site="
                . $this->id_site
                . ", temp_indoor="
                . $this->temp_indoor
                . ", humid_indoor="
                . $this->humid_indoor;
        */

        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id_site=htmlspecialchars(strip_tags($this->id_site));
        $this->temp_indoor=htmlspecialchars(strip_tags($this->temp_indoor));
        $this->humid_indoor=htmlspecialchars(strip_tags($this->humid_indoor));
    
        // bind values
        $stmt->bindParam(":id_site", $this->id_site);
        $stmt->bindParam(":temp_indoor", $this->temp_indoor);
        $stmt->bindParam(":humid_indoor", $this->humid_indoor);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    // used when filling up the update event form
    function readOne(){

        // query to read single record

        if (!isset($this->id_event)) {
            
            $query = "SELECT
                *
            FROM
                " . $this->table_name . " p
            ORDER BY
                p.datatime DESC
            LIMIT
                1";

            // prepare query statement
            $stmt = $this->conn->prepare( $query );

            // execute query
            $stmt->execute();

        } else {

            $query = "SELECT
                *
            FROM
                " . $this->table_name . " p
            WHERE
                p.id_event = ?";

            // prepare query statement
            $stmt = $this->conn->prepare( $query );
        
            // bind id of event to be updated
            $stmt->bindParam(1, $this->id_event);

            // execute query
            $stmt->execute();

        }
             
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->id_event = $row['id_event'];
        $this->id_site = $row['id_site'];
        $this->datatime = $row['datatime'];
        $this->temp_indoor = $row['temp_indoor'];
        $this->temp_outdoor = $row['temp_outdoor'];
        $this->humid_indoor = $row['humid_indoor'];
        $this->humid_outdoor = $row['humid_outdoor'];
    }

    // update the event
    function update(){
    
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    temp_indoor = :temp_indoor,
                    humid_indoor = :humid_indoor
                WHERE
                    id_event = :id_event";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->temp_indoor=htmlspecialchars(strip_tags($this->temp_indoor));
        $this->humid_indoor=htmlspecialchars(strip_tags($this->humid_indoor));
        $this->id_event=htmlspecialchars(strip_tags($this->id_event));
    
        // bind new values
        $stmt->bindParam(':temp_indoor', $this->temp_indoor);
        $stmt->bindParam(':humid_indoor', $this->humid_indoor);
        $stmt->bindParam(':id_event', $this->id_event);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    // delete the event
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_event = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id_event=htmlspecialchars(strip_tags($this->id_event));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id_event);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    // search events
    function search($keywords){
    
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " p
                WHERE
                    p.datatime LIKE ?
                ORDER BY
                    p.datatime DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $keywords);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // search events with pagination
    function searchPaging($keywords, $from_record_num, $records_per_page){
    
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " p
                WHERE
                    p.datatime LIKE ?
                ORDER BY
                    p.datatime DESC
                    LIMIT ?, ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // read events with pagination
    public function readPaging($from_record_num, $records_per_page){
    
        // select query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " p
                ORDER BY p.datatime DESC
                LIMIT ?, ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
    
        // execute query
        $stmt->execute();
    
        // return values from database
        return $stmt;
    }


    // count rows
    public function count($stringa=NULL){
        
        if(isset($stringa)) {
            $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . " p WHERE p.datatime LIKE '%" . $stringa . "%'";
        } else {
            $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
        }
        
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row['total_rows'];
    }


}
?>