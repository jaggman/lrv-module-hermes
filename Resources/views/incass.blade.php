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
<h2>Инкасация</h2>
    <table id="incass" class="table table-hover">
        <thead>
            <tr>
                <th>№ Инкассации</th>
                <th>№ Терминала</th>
                <th>Время (Сервер)</th>
                <th>Время (Терминал)</th>
                <th>Банкнот</th>
                <th>Сумма</th>
                <th>Купюры</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($incass as $k=>$inca){?>
            <tr>
                <td><?= $inca['number'] ?></td>
                <!--<td><?= $inca->point ?></td>-->
                <td><?= $inca->point ?></td>
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
<!--<link href="http://themes.tur8.ru/absadmin/vendor/plugins/datepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
<script src="http://themes.tur8.ru/absadmin/vendor/plugins/moment/moment.min.js"></script>
<script src="http://themes.tur8.ru/absadmin/vendor/plugins/datepicker/js/bootstrap-datetimepicker.min.js"></script>-->
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
    #incass {
        width: inherit;
    }
</style>
@stop
