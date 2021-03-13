<?php
class Site{
  
    // database connection and table name
    private $conn;
    private $table_name = "site";
  
    // object properties
    public $id_site;
    public $name;
    public $timeout;
    public $state;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    // read record
    function read(){

        // query to read single record

        $query = "SELECT
            *
        FROM
            " . $this->table_name . " p
        WHERE
            p.id_site = ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of site to be updated
        $stmt->bindParam(1, $this->id_site);

        // execute query
        $stmt->execute();

        
             
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->id_site = $row['id_site'];
        $this->name = $row['name'];
        $this->timeout = $row['timeout'];
        $this->state = $row['state'];
    }


    // set state calling api and update record
    function update(){
    
        $url = "http://localhost:8000/state";
        $record = array("id_site"=>$this->id_site, "timeout"=>$this->timeout, "state"=>$this->state);
        $content = json_encode($record);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ( $status != 201 ) {
            //die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
            return false;    
        }   else    {

            // update query
            $query = "UPDATE
                    " . $this->table_name . "
                SET
                    timeout = :timeout,
                    state = :state
                WHERE
                    id_site = :id_site";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->timeout=htmlspecialchars(strip_tags($this->timeout));
            $this->state=htmlspecialchars(strip_tags($this->state));
            $this->id_site=htmlspecialchars(strip_tags($this->id_site));

            // bind new values
            $stmt->bindParam(':timeout', $this->timeout);
            $stmt->bindParam(':state', $this->state);
            $stmt->bindParam(':id_site', $this->id_site);

            // execute the query
            if($stmt->execute()){
                return true;
            }

            return false;
        }

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