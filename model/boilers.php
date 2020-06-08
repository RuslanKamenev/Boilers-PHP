<?php

class BoilersModel {

    public function getBoilerData () {
        $result = [];
        $rows = $this->getBoilersAmount();
        $sql = DB::$pdo->prepare('SELECT * FROM work_regime ORDER BY boiler, consumption');
        $sql->execute();
        $fetch = $sql->fetchAll();
        //Массив результатов в массив с котлами, где $result[$i]; $i - бойлер
        foreach ( $fetch as $value ) {
            for ( $i=0; $i <= $rows; $i++ ) {
                if ( $value['boiler'] == $i ) {
                    $result[$i]['consumption'][] = $value['consumption'];
                    $result[$i]['efficiency'][] = $value['efficiency'];
                }
            }
        }
        return $result;
    }

    //Количество котлов
    public function getBoilersAmount () {
        $sql = DB::$pdo->prepare('SELECT DISTINCT boiler FROM work_regime ORDER BY boiler');
        $sql->execute();
        $rows = $sql->rowCount();
        return $rows;
    }

    //Названия котлов
    public function getBoilersTitle () {
        $query = 'SELECT number, name FROM boilers_name ORDER BY number';
        $sql = DB::$pdo->prepare($query);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function insertBoilersName ($number, $name) {
        $query = 'INSERT INTO boilers_name (number, name) VALUE (:number, :name)';
        $sql = DB::$pdo->prepare($query);
        $sql->bindValue(':number', $number);
        $sql->bindValue(':name', $name);
        $sql->execute();
    }

    public function insertBoilerWorkRegime ($boiler, $consumption, $efficiency) {
        $query = 'INSERT INTO work_regime (boiler, consumption, efficiency) VALUE (:boiler, :consumption, :efficiency) ';
        $sql = DB::$pdo->prepare($query);
        $sql->bindValue(':boiler', $boiler);
        $sql->bindValue(':consumption', $consumption);
        $sql->bindValue(':efficiency', $efficiency);
        $sql->execute();
    }

    public function deleteFromTable ($table) {
        $sql = DB::$pdo->prepare('DELETE FROM :table');
        $sql->bindValue(':boiler', $table);
        $sql->execute();

    }

}
