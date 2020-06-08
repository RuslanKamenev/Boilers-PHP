$(document).ready(function(){

    //Добавление новой строки на странице ввода параметров котлов
    $(document).on('click', '.add-new-row', function() {
        let boilerNumber = lastCharFromClass($(this));
        let appendData = '<tr><td><input type="text" name="consumption-' +boilerNumber+ '[]"</td><td><input type="text" name="efficiency-' +boilerNumber+ '[]" ></td></tr>';
        $(this).prev().append(appendData);
    } );

    //Добавление формы под новый котел на стрнице вводы параметров котлов
    $('#add-boiler').click( function () {
        let boilerNumber = lastCharFromClass($(this));
        let appendData = '<div class="boiler-table"><div class="boiler-name">Котел<br>' +
            '<input type="text" name="boiler[]"></div>'+
            '<table><thead><tr><th>Потребление, м3/ч\n</th><th>КПД, %</th></tr></thead>' +
            '<tbody><tr><td>' +
            '<input type="text" name="consumption-'+boilerNumber+'[]">' +
            '</td><td>' +
            '<input type="text" name="efficiency-'+boilerNumber+'[]">' +
            '</td></tr></tbody></table>' +
            '<input type="button" value="Добавить строку" class="add-new-row boiler-'+boilerNumber+'"></div>';
        $('.boilers-table').append(appendData);

        $(this).toggleClass('add-boiler-'+ boilerNumber );
        $(this).toggleClass('add-boiler-'+ ( +boilerNumber+1) );
    } );

    //Получение последнего элемента из названия класса
    function lastCharFromClass(classData) {
        let className = $(classData).attr('class');
        return className.charAt(className.length-1);
    }

});