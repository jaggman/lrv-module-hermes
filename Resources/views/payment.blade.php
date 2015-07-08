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
<h2>Финансовая статистика</h2>
    <table id="entity" class="table table-hover">
        <thead>
            <tr>
                <!--<th>Трансакция</th>-->
                <th>TXN</th>
                <th>Терминал</th>
                <th>Время (Сервер)</th>
                <th>Время (Терминал)</th>
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
                <!--<td><?= @$k ?></td>-->
                <td><?= @$sts->txn ?></td>
                <td><?= @$sts->point ?></td>
                <td><?= $sts->created ?></td>
                <td>(<?= @$sts['date'] ?>)</td>
                <td><?= $sts['sum'] ?></td><?php  if($sts->proc) $agr['sum'] += $sts['sum']; else $agr['obr'] += $sts['sum']; ?>
                <td>{{ $sts->proc ? "OK" : "в обработке" }}</td>
                <td>Global Travel*</td>
                <td><?= $sts->typename ?></td>
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
<link href="/bootstrap/datepicker/css/datepicker.css" rel="stylesheet" type="text/css">
<script src="/bootstrap/datepicker/js/bootstrap-datepicker.js"></script>
@stop

@section('script')
<script type="text/javascript">
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    var checkin = $('#datetimepicker1').datepicker({
      format: 'yyyy-mm-dd',
    }).on('changeDate', function(ev) {
      if (ev.date.valueOf() > checkout.date.valueOf()) {
        var newDate = new Date(ev.date)
        newDate.setDate(newDate.getDate() + 1);
        checkout.setValue(newDate);
      }
      checkin.hide();
      //$('#datetimepicker2')[0].focus();
    }).data('datepicker');
    var checkout = $('#datetimepicker2').datepicker({
      format: 'yyyy-mm-dd',
    }).on('changeDate', function(ev) {
      checkout.hide();
    }).data('datepicker');
</script>
<script type="text/javascript">
    $('#timerid').on('change', function(){
        var date = {
            today: ['<?= date('Y-m-d', time()) ?>','<?= date('Y-m-d', time()) ?>'],
            yesterday: ['<?= date('Y-m-d', time()-60*60*24) ?>','<?= date('Y-m-d', time()-60*60*24) ?>'],
            yestoday: ['<?= date('Y-m-d', time()-60*60*24) ?>','<?= date('Y-m-d', time()) ?>'],
            week: ['<?= date('Y-m-d', time()-60*60*24*7) ?>','<?= date('Y-m-d', time()) ?>'],
            month: ['<?= date('Y-m-d', time()-60*60*24*30) ?>','<?= date('Y-m-d', time()) ?>'],
            year: ['<?= date('Y-m-d', time()-60*60*24*365) ?>','<?= date('Y-m-d', time()) ?>'],
        }
        date = date[$(this).val()]; 
        if(date){
            //$('#datetimepicker1').val(date[0]);
            checkin.setValue(new Date(date[0]));
            checkout.setValue(new Date(date[1]));
            //$('#datetimepicker2').val(date[1]);
            //alert(date[0]+' - '+date[1]);
        }
    })
</script>
<style type="text/css">
    .table tbody tr {
        background-color: #ececec;
    }
    #entity {
          width: inherit;
    }
</style>
@stop
