<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Modules\Hermes\Http\Controllers\HermesController;

//@extends('hermes::layouts.layout')
?>
@extends('hermes::layouts.master')

@section('title', 'Статусы терминалов')


@section('content')
<h2>Статусы терминалов</h2>
    <table class="table paytable" id="terminals">
        <thead>
            <tr>
                <th>№</th>
                <th>Терминал</th>
                <th>Статус</th>
                <th>Банкнот</th>
                <th>Время</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($point as $poin){?>
            <tr data-id="{{ $poin->id }}">
                <td>{{ $poin->id }}</td>
                <td>
                    <?= $poin->name ?> <br />
                </td>
                <td><i class="fa fa-circle" style="color:<?= ($poin->state['state'] == 200 && $poin->state['diff'] < 300) ? 'green' : 'orange' ?>;"></i> <?= $poin->state['state'] ?></td>
                <td><?= $poin->state['banknotes'] ?></td>
                <td><?= $poin->state['created'] ?></td>
            </tr>
            <?php }  ?>
        </tbody>
    </table>
<style type="text/css">
    #terminals tbody tr:hover {
        //cursor: pointer;
        background: #eee;
    }
    #terminals {
        width: inherit;
    }

</style>
@stop
@section('script')
<script type="text/javascript">
    $('#terminals tbody').on('click', 'trt', function(){
        //alert($(this).attr('data-href'));
        document.location = '{{ action('\Modules\Hermes\Http\Controllers\HermesController@getTerminal').'?id=' }}' + $(this).attr('data-id');
    })
    setInterval(function(){
        $.post(
            null,
            function(data){
                //$('#terminals tbody').children().remove();
                $('#terminals tbody').empty().append(data);
                //$('#terminals tbody').append(data);
                //alert(data);
            }
        );
    },60000);
</script>
@stop
