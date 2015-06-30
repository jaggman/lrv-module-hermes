<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
@extends('hermes::layouts.layout')

@section('title', 'Front')

@section('sidebar')
    @parent
@stop

@section('content')
<h3>Инкасация</h3>
    <table id="incass">
        <thead>
            <tr>
                <th>Номер</th>
                <th>Терминал</th>
                <th>Дата</th>
                <th>Предыдущая</th>
                <th>Сумма</th>
                <th>Банкнот</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($incass as $k=>$inca){?>
            <tr>
                <td><?= $k ?></td>
                <!--<td><?= $inca['created'] ?></td>-->
                <td><?= $point[$inca['point']] ?></td>
                <td><?= @$inca['params']['currentDate'] ?></td>
                <td><?= isset($inca['params']['previousDate']) ? $inca['params']['previousDate'] : '' ?></td>
                <td><?= @$inca['params']['sum'] ?></td>
                <td><?= @$inca['params']['banknotes'] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
<h3>Операции</h3>
    <table id="entity">
        <thead>
            <tr>
                <th>Терминал</th>
                <th>Сумма</th>
                <th>Order</th>
                <th>TXN</th>
                <th>Время</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($state as $k=>$sts){?>
            <tr>
                <td><?= $point[$sts['point']] ?></td>
                <td><?= @$sts['sum'] ?></td>
                <td><?= @$sts['order'] ?></td>
                <td><?= @$sts['txn'] ?></td>
                <td><?= @$sts['date'] ?></td>
            </tr>
            <?php }  ?>
        </tbody>
    </table>

@stop
