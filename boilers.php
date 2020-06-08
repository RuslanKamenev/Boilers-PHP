<?php

$directory = dirname(__FILE__);
require_once ($directory.'/autoload.php');

$model = new BoilersModel();

//Обновление данных расход/КПД по котлам
if ( isset($_POST['boilers-table-update'])) {
    $model->deleteFromTable('work_regime');
    $model->deleteFromTable('boilers_name');
    $boilers = count( $_POST['boiler'] );

    for ( $i=0; $i < $boilers; $i++ ) {
        $model->insertBoilersName($i, $_POST['boiler'][$i]);
        $arrayLength = count( $_POST['consumption-'.$i] );
        for ( $j=0; $j < $arrayLength; $j++ ) {
            if ( isset($_POST['consumption-'.$i][$j]) && isset($_POST['efficiency-'.$i][$j]) ) {
                $model->insertBoilerWorkRegime( $i, $_POST['consumption-'.$i][$j], $_POST['efficiency-'.$i][$j] );
            }
        }
    }
}

$boilers = $model->getBoilersAmount();
$boilerData = $model->getBoilerData();
$boilersName = $model->getBoilersTitle();

for ( $i=0; $i < $boilers; $i++ ) {
    $boiler[] = new Boilers( $boilerData[$i]['consumption'], $boilerData[$i]['efficiency'], $boilersName[$i]);
}

$columnLength = [];
foreach ( $boiler as $value ) {
    $columnLength[] = count($value->consumption);
}

require_once($directory . '/view/common/header.tpl');
require_once ($directory.'/view/boilers/table.tpl');
require_once ($directory. '/view/common/footer.tpl');
