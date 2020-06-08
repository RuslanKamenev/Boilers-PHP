<div class="boiler-consumption-result">
    <div class="table-title">
        <h3>Распределение газа по котлам</h3>
    </div>
    <div id="boilers-consumption-table">
        <?php for ( $i=0; $i < $boilers; $i++ ): ?>
        <div class="boiler-column">
            <div class="boiler-title">
                Котел<br>
                <?= $boilerOriginal[$i]->name ?>
            </div>

            <?php for ( $j=0; $j < $columnLengthOriginal[$i]; $j++ ): ?>

                <?php if ( $optimalConsumptionArray[$i] == $boilerOriginal[$i]->consumption[$j] ): ?>
                    <div class="boiler-consumption consumption-selected">
                        <?= $boilerOriginal[$i]->consumption[$j] ?>
                    </div>
                <?php else: ?>
                    <div class="boiler-consumption consumption-not-selected">
                        <?= $boilerOriginal[$i]->consumption[$j] ?>
                    </div>
                <?php endif; ?>

                <?php if ( $optimalConsumptionArray[$i] > $boilerOriginal[$i]->consumption[$j] && $optimalConsumptionArray[$i] < $boilerOriginal[$i]->consumption[$j+1] ): ?>
                    <div class="boiler-consumption consumption-selected">
                        <?= $optimalConsumptionArray[$i] ?>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
            </div>
        <?php endfor; ?>
    </div>
</div>