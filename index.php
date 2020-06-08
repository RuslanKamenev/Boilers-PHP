<?php

$directory = dirname(__FILE__);
require_once ($directory.'/autoload.php');

$model = new BoilersModel();
$boilers = $model->getBoilersAmount();
$boilersName = $model->getBoilersTitle();
$boilerData = $model->getBoilerData();

for ( $i=0; $i < $boilers; $i++ ) {
    $boiler[] = new Boilers( $boilerData[$i]['consumption'], $boilerData[$i]['efficiency'], $boilersName[$i]['name']);
    $boilerOriginal[] = new Boilers( $boilerData[$i]['consumption'], $boilerData[$i]['efficiency'],$boilersName[$i]['name']);
}

//Начало работы программы, если введено значение расхода
if ( isset( $_POST['consumption-submit'] ) ) {

    //Округление расхода в зависимости от точности расчета
    $currentConsumption = $_POST['consumption-value'];
    switch ( $_POST['calculation-accuracy'] ) {
        case 1:
            $accuracy = 5000;
            break;
        case 2:
            $accuracy = 1000;
            break;
    }
    $currentConsumption = roundValue($currentConsumption, $accuracy);

     // Максимальное и минимальное потребление газа - сумма максимальных и минимальных расходов по всем котлам.
    $maxConsumption = 0;
    foreach ($boiler as $value) {
        $maxConsumption += $value->consumption[count($value->consumption) - 1];
    }
    $minConsumption = 0;
    foreach ($boiler as $value) {
        $minConsumption += $value->consumption[0];
    }

    //Неправильно введены исходные значения
    if ( $currentConsumption < $minConsumption || $currentConsumption > $maxConsumption ) {
        $searchError = 'Указанное вами значение расхода не входит в возможный диапазон значений, введите правильный расход';
        require_once($directory . '/view/common/header.tpl');
        require_once($directory . '/view/index/search.tpl');
        require_once ($directory. '/view/common/footer.tpl');
        return;
    }

    //Интерполярия расхода и КПД с заданной точностью
    calculateIntermediateValues($boiler, $boilers, $accuracy);
    $preparedConsumptionData = prepareDataForIteration($boiler, 'consumption');
    $preparedEfficiencyData = prepareDataForIteration($boiler, 'efficiency');

    //Количество значений расход/КПД по каждому котлу
    $columnLength = [];
    foreach ( $boiler as $value ) {
        $columnLength[] = count($value->consumption);
    }
    $columnLengthOriginal = [];
    foreach ( $boilerOriginal as $value ) {
        $columnLengthOriginal[] = count($value->consumption);
    }

    //Номера итераций при которых расчетный расход == расходу указаному пользователем
    $consumptionMatchList = [];
    $i = 0;
    do {
        $sum = iteration($i, $preparedConsumptionData, $columnLength, $boilers);
        if ($sum == $currentConsumption) {
            $consumptionMatchList[] = $i;
        }
        $i++;
    } while ($sum < $maxConsumption);

    //Расчет всех возможных КПД для указаных выше расходов и максимального КПД среди них
    $efficiencyList = [];
    foreach ($consumptionMatchList as $value) {
        $efficiencyList[] = iteration($value, $preparedEfficiencyData, $columnLength, $boilers);
    }
    $maxEfficiency = max($efficiencyList);

    //Данные для расчета при равномерном распределении газа
    $gasConsumption = $currentConsumption;
    $similarConsumptionArray = [];
    $countedBoilers = 0;

    //Проверка на частные случаи, когда при равномерном распределении минимальный или максимальный расход не входят в диапазон. Заполнение этих элементов
    do {
        $similarConsumptionDistribution = $gasConsumption / ($boilers - $countedBoilers);
        $similarConsumptionDistribution = roundValue( $similarConsumptionDistribution, $accuracy );
        $similarConsumptionDistribution = floor( $similarConsumptionDistribution );
        $reduction = 0;

        for ($i = 0; $i < $boilers; $i++) {
            if ($similarConsumptionDistribution < $boiler[$i]->consumption[0] && !isset($similarConsumptionArray[$i]) ) {
                $similarConsumptionArray[$i] = $boiler[$i]->consumption[0];
                $reduction += $similarConsumptionArray[$i];
                $countedBoilers += 1;
            } elseif ($similarConsumptionDistribution > $boiler[$i]->consumption[$columnLength[$i]-1] && !isset($similarConsumptionArray[$i]) ) {
                $similarConsumptionArray[$i] = $boiler[$i]->consumption[$columnLength[$i]-1];
                $reduction += $similarConsumptionArray[$i];
                $countedBoilers += 1;
            }
        }

        $gasConsumption -= $reduction;
    } while ( $reduction != 0 );

    //Заполнение оставшихся котлов газом
    $balance = $gasConsumption;
    for ($i = 0; $i < $boilers; $i++) {
        $similarConsumptionDistribution = $gasConsumption / ($boilers - $countedBoilers);
        $similarConsumptionDistribution = roundValue( $similarConsumptionDistribution, $accuracy );
        $similarConsumptionDistribution = floor( $similarConsumptionDistribution );

        if ( !isset($similarConsumptionArray[$i]) ) {
            $similarConsumptionArray[$i] = $similarConsumptionDistribution;
            $balance -= $similarConsumptionArray[$i];
        }
    }
    $similarConsumptionArray[$boilers-1] += $balance;

    //КПД при равномерном расчпределении газа
    $similarEfficiency = 0;
    for ( $i=0; $i < $boilers; $i++ ) {
        $index = array_search( $similarConsumptionArray[$i], $preparedConsumptionData[$i] );
        $similarEfficiency += $preparedEfficiencyData[$i][$index];
    }

    //Прирост КПД
    $profit = $maxEfficiency - $similarEfficiency;
    $profit = number_format( $profit, 2 );


    //Обработка данных для вывода
    $maxEfficiencyIteration = array_search($maxEfficiency, $efficiencyList);
    $consumptionIteration = $consumptionMatchList[$maxEfficiencyIteration];
    $separateConsumptionArray = iteration($consumptionIteration, $preparedConsumptionData, $columnLength, $boilers, 'separate');
    $separateConsumptionArray = array_reverse($separateConsumptionArray);
    $optimalConsumptionArray = iteration($consumptionIteration, $preparedConsumptionData, $columnLength, $boilers, 'array');
    $optimalConsumptionArray = array_reverse($optimalConsumptionArray);
    $separateConsumption = implode($separateConsumptionArray);
    $separateConsumption = rtrim( $separateConsumption, '/' );

    require_once($directory . '/view/common/header.tpl');
    require_once($directory . '/view/index/search.tpl');
    require_once($directory . '/view/index/result.tpl');
    require_once($directory . '/view/index/table.tpl');
    require_once ($directory. '/view/common/footer.tpl');
} else {
    require_once($directory . '/view/common/header.tpl');
    require_once($directory . '/view/index/search.tpl');
    require_once ($directory. '/view/common/footer.tpl');
}




//Сумма строк из различных столбцов в зависимости от шага $step
function iteration ($step, $table, $columnLength, $boilers, $setting = '') {
    if ( $setting == 'separate' || $setting == 'array' ) {
        $result = [];
    } else {
        $result = 0;
    }

    for ( $i = $boilers - 1; $i >= 0; $i-- ) {
        $line = ($step % $columnLength[$i]);
        $step = $step - $line;
        if ( $step >= ( $columnLength[$i] - 1 ) ) {
            $step = $step / $columnLength[$i];
        }

        if ( $setting == 'separate' ) {
            $result[] = $table[$i][$line].'/';
        }
        elseif ( $setting == 'array' ) {
            $result[] = $table[$i][$line];
        }
        else{
            $result +=  $table[$i][$line];
        }
    }
    return $result;
}

//Добавление в массив новых значений, если разность между значениями $j и $j+1 больше чем шаг $step
function calculateIntermediateValues ($boiler, $boilers, $step) {
    for ($i = 0; $i < $boilers; $i++) {
        $j = 0;
        do {
            if (($boiler[$i]->consumption[$j+1] - $boiler[$i]->consumption[$j]) > $step) {
                insertValueIntoArray($boiler[$i], $j, $step);
            }
            else
                $j++;
        } while ( isset($boiler[$i]->consumption[$j+1]) );
    }
    return;
}

//Получение двумерного масива с данными для использования в функции с итерациями
function prepareDataForIteration ( $data, $element) {
    $result = [];
    foreach ( $data as $value ) {
        $result[] = $value->$element;
    }
    return $result;
}

//Добавление значения в середину массива
function insertValueIntoArray ( $array, $step, $increment ) {
    $insertConsumptionValue = $array->consumption[$step] + $increment;
    $insertEfficiencyValue = ($array->efficiency[$step+1] - $array->efficiency[$step]) / ($array->consumption[$step+1] - $array->consumption[$step]) * ($insertConsumptionValue - $array->consumption[$step]) + $array->efficiency[$step];
    array_splice ( $array->consumption, $step+1, 0, $insertConsumptionValue );
    array_splice ( $array->efficiency, $step+1, 0, $insertEfficiencyValue );
    return;
}

//Округление введенного значения расхода в соответствии с заданным шагом
function roundValue ($value, $step) {
    $balance = $value % $step;
    if ( $balance == 0 )
        return $value;
    else {
        $halfStep = $step / 2;
        if ( $balance >= $halfStep ) {
            $adding = $step - $balance;
            $result = $value + $adding;
        }
        else {
            $result = $value - $balance;
        }
    }
    return $result;
}