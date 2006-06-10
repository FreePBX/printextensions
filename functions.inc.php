<?php 
/* $Id:$ */

function printextensions_allusers() {
        global $db;
        $sql = "SELECT extension,name,directdid FROM users ORDER BY extension";
        $results = $db->getAll($sql);
        if(DB::IsError($results)) {
                $results = null;
        }
        foreach($results as $result){
                if (checkRange($result[0])){
                        $users[] = array($result[0],$result[1],$result[2]);
                }
        }
        if (isset($users)) sort($users);
        return $users;
}
?>
