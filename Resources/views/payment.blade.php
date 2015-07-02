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
    <label>Сумма: <input type="text" name="sum" value="<?= \Input::get('sum') ?>"/></label>
    <label>Номер договора: <input type="text" name="num" value="<?= \Input::get('num') ?>"/></label>
    <input type="submit" value="Найти" />
</form>
<span style="color:red;">* <i>поля, данных по которым нет в таблице <br />в скобках - реальное время с точки (не учитывается при фильтрации)<br />выведены последние 100 записей по заданым параметрам</i></span>
<h3>Операции</h3>
    <table id="entity" class="table table-hover">
        <thead>
            <tr>
                <th>Трансакция</th>
                <th>Терминал</th>
                <th>Время</th>
                <th>(Время)</th>
                <th>Сумма</th>
                <th>Результат</th>
                <th>Получатель</th>
                <th>Тип платежа</th>
                <th>Номер договора</th>
                <th>Комментарий</th>
            </tr>
        </thead>
        <tbody>
            <?php $agr = [
                'sum' => 0,
                'obr' => 0,
            ]; ?>
            <?php foreach($state as $k=>$sts){?>
            <tr>
                <td><?= @$k ?></td>
                <td><?= $sts['point'] ?> (<?= $point[$sts['point']] ?>)</td>
                <td><?= $sts['created'] ?></td>
                <td>(<?= $sts['date'] ?>)</td>
                <td><?= $sts['sum'] ?></td><?php $agr['sum'] += $sts['sum']; ?>
                <td>OK*</td>
                <td>Global Travel*</td>
                <td><?= @$sts['method'] ?></td>
                <td><?= @$sts['order'] ?></td>
                <td>Комментарий*</td>
            </tr>
            <?php }  ?>
        </tbody>
    </table>
<strong>Сводные данные:</strong><br />
<b>OK: <?= $agr['sum'] ?></b><br />
<b>в обработке: <?= $agr['obr'] ?></b>
<br />
<br />
<br />

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
</style>
@stop
