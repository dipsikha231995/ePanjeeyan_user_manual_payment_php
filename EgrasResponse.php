<?php

class EgrasResponse
{
    private $pdo;

    public function __construct()
    {
        // create the db connection
        $dns = 'mysql:host=localhost;dbname=metadatabase;charset=utf8mb4';
        $username = 'root';
        $password = 'root';

        $this->pdo = new PDO($dns, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function updateTransaction($arr)
    {
        try {
            $sql = "update payment set grnno=?, responseparameters=?, amount=?, cin=?, challan_date=?, status=?, mop=? where departmentid=?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($arr);
        } 
        catch (Exception $ex) {
            
        }
    }
}