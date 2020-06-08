
<h3>Расходы и КПД по котлам</h3>

<a href="/">На главную</a>

<form method="post">

    <div class="boilers-table">
        <?php for ( $i=0; $i < $boilers; $i++ ): ?>
            <div class="boiler-table">
                <div class="boiler-name">Котел<br>
                <input type="text" value="<?= $boilersName[$i]['name'] ?>" name="boiler[]"></div>

                <table>
                    <thead>
                    <tr>
                        <th>
                            Расход, м3/ч
                        </th>
                        <th>
                            КПД, %
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php for ( $j=0; $j < $columnLength[$i]; $j++ ): ?>
                        <tr>
                            <td>
                                <input type="text" name="consumption-<?= $i ?>[]" value="<?= $boiler[$i]->consumption[$j] ?>">
                            </td>
                            <td>
                                <input type="text" name="efficiency-<?= $i ?>[]" value="<?= $boiler[$i]->efficiency[$j] ?>">
                            </td>
                        </tr>
                    <?php endfor; ?>
                    </tbody>
                </table>

                <input type="button" value="Добавить строку" class="add-new-row boiler-<?= $i ?>">
            </div>
        <?php endfor; ?>
    </div>

    <input type="submit" value="Сохранить изменения" name="boilers-table-update"><br>
    <input type="button" id="add-boiler" class="add-boiler-<?= $boilers ?>" value="Добавить котел">

</form>