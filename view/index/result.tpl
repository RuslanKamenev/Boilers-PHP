
<div class="result-text">
    Текущий расход - <?= $currentConsumption ?> м3/ч
    <br>Необходимо распределить газ по котлам в соответствии со следующим расходом: <b><?= $separateConsumption ?></b>
    <br>КПД при равномерном распределении расхода: <?= $similarEfficiency ?>%
    <br>КПД при оптимальном распределении расхода: <?= $maxEfficiency ?>%
    <br>Прирост КПД: <b><?= $profit ?>%</b>
</div>
