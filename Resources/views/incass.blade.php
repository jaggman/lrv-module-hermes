<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
@extends('hermes::layouts.master')

@section('title', 'Front')

@section('sidebar')
    @parent
@stop

@section('content')
<form>
    <label>C: <input type="text" name="date[start]" id="datetimepicker1" value="<?= $data['date']['start'] ?>" /></label>
    <label> по: <input type="text" name="date[end]" id="datetimepicker2" value="<?= $data['date']['end'] ?>" /></label>
    <select id="timerid">
        <option value="">-</option>
        <option value="today">Сегодня</option>
        <option value="yesterday">Вчера</option>
        <option value="yestoday">Вчера/Сегодня</option>
        <option value="week">Неделя</option>
        <option value="month">Месяц</option>
        <option value="year">Год</option>
    </select>
    <label>Терминал:
        <select name="id">
            <option value="">-все-</option>
            <option value="1"<?= $data['id'] == 1 ? ' selected="selected"' : '' ?>>Тестовый терминал</option>
        </select>
    </label>
    <input type="submit" value="Найти" />
</form>
<span style="color:red;">* <i>в скобках - реальное время с точки (не учитывается при фильтрации)<br />выведены последние 100 записей по заданым параметрам</i></span>
<h3>Инкасация</h3>
    <table id="incass" class="table table-hover">
        <thead>
            <tr>
                <th>№ Инкассации</th>
                <th>№ Терминала</th>
                <th>Дата</th>
                <th>(Дата)</th>
                <th>Банкнот</th>
                <th>Сумма</th>
                <th>Купюры</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($incass as $k=>$inca){?>
            <tr>
                <td><?= $inca['number'] ?></td>
                <!--<td><?= $point[$inca['pointId']] ?></td>-->
                <td><?= $inca['pointId'] ?></td>
                <td><?= @$inca['created'] ?></td>
                <td>(<?= @$inca['currentDate'] ?>)</td>
                <td><?= @$inca['banknotes'] ?></td>
                <td><?= @$inca['sum'] ?></td>
                <td><?php $b = json_decode($inca['variables'], 1); while($a = each($b)){ echo '['.$a['key'].'] => '.$a['value']."шт; \n"; } ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
@stop

@section('scripts')
    @parent
<link href="http://themes.tur8.ru/absadmin/vendor/plugins/datepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
<script src="http://themes.tur8.ru/absadmin/vendor/plugins/moment/moment.min.js"></script>
<script src="http://themes.tur8.ru/absadmin/vendor/plugins/datepicker/js/bootstrap-datetimepicker.min.js"></script>
@stop

@section('script')
<script type="text/javascript">
    $('#datetimepicker1').datetimepicker({
        locale: 'ru',
        format: 'YYYY-MM-DD HH:mm:ss',
    });
    $('#datetimepicker2').datetimepicker({
        locale: 'ru',
        format: 'YYYY-MM-DD HH:mm:ss',
    });
</script>
<script type="text/javascript">
    $('#timerid').on('change', function(){
        var date = {
            today: ['<?= date('Y-m-d 00:00:00', time()) ?>','<?= date('Y-m-d 23:59:59', time()) ?>'],
            yesterday: ['<?= date('Y-m-d 00:00:00', time()-60*60*24) ?>','<?= date('Y-m-d 23:59:59', time()-60*60*24) ?>'],
            yestoday: ['<?= date('Y-m-d 00:00:00', time()-60*60*24) ?>','<?= date('Y-m-d 23:59:59', time()) ?>'],
            week: ['<?= date('Y-m-d 00:00:00', time()-60*60*24*7) ?>','<?= date('Y-m-d 23:59:59', time()) ?>'],
            month: ['<?= date('Y-m-d 00:00:00', time()-60*60*24*30) ?>','<?= date('Y-m-d 23:59:59', time()) ?>'],
            year: ['<?= date('Y-m-d 00:00:00', time()-60*60*24*365) ?>','<?= date('Y-m-d 23:59:59', time()) ?>'],
        }
        date = date[$(this).val()]; 
        if(date){
            $('#datetimepicker1').val(date[0]);
            $('#datetimepicker2').val(date[1]);
            //alert(date[0]+' - '+date[1]);
        }
    })
</script>
<style type="text/css">
    .table tbody tr {
        background-color: #ececec;
    }
    #incass {
        width: inherit;
    }
</style>
@stop
