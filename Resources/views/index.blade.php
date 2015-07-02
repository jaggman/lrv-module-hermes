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

@section('title', 'Front')


@section('content')
<span style="color:red;">* <i>выведен только один терминал, т.к. данные приходили только по нему</i></span>
    <table id="terminals">
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
            <?php foreach($state as $k=>$sts){?>
            <tr data-id="{{ $k }}">
                <td>{{ $k }}</td>
                <td>
                    <?= @$point[$k]['name'] /*?> <br />
                    <?= var_dump($sts) /*?> <br />
                    <?= /*Config::set('app.timezone', 'America/Chicago'); ?>
                    <?= date_format(new DateTime('now'),'Y-m-d H:i:s') ?> <br />
                    <?= config('app.timezone'); ?> <br />
                    <?= date_default_timezone_get() */ ?> <br />
                </td>
                <td><i class="fa fa-circle" style="color:<?= ($sts['state'] == 200 && $sts['diff'] < 300) ? 'green' : 'orange' ?>;"></i> <?= $sts['state'] ?></td>
                <td><?= $sts['banknotes'] ?></td>
                <td><?= $sts['created'] ?></td>
            </tr>
            <?php }  ?>
        </tbody>
    </table>
<style type="text/css">
    #terminals tbody tr:hover {
        cursor: pointer;
        background: #eee;
    }
    #terminals {
        border: none;
        border-spacing: 0px;
    }

</style>
@stop
@section('script')
    $('#terminals tbody').on('click', 'tr', function(){
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
@stop
