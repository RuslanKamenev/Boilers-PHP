<a href="/boilers.php">Характеристики котлов</a>

<div class="consumption-input">
    <h3><?= $searchError ?></h3>
    Введите текущий расход газа для расчета максимального КПД<br>
    <form method="post" class="consumption-input-form">
        <input type="text" name="consumption-value"><br>
        Высокая точность (интервал 1000 м3/ч)
        <input type="radio" name="calculation-accuracy" value="2" checked="checked"><br>
        Низкая точность (интервал 5000 м3/ч)
        <input type="radio" name="calculation-accuracy" value="1"><br>
        <input type="submit" name="consumption-submit" value="Расчитать">
    </form>
</div>

