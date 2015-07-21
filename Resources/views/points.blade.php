<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
@extends('hermes::layouts.master')

@section('title', 'Терминалы')

@section('sidebar')
    @parent
@stop

@section('content')
<h2>Терминалы</h2>
<table class="table paytable" id="points">
    <thead>
        <tr>
            <th>№ точки</th>
            <th>Название</th>
            <th>Сумма</th>
            <th>Банкнот</th>
        </tr>
    </thead>
    <tbody>
        @foreach($points as $point)
        <tr>
            <td>{{ $point['id'] }}</td>
            <td>{{ $point['name'] }}</td>
            <td><?php $sum = 0;
            if(isset($point->state)){
                $var = json_decode($point->state['variables'],1);
                foreach($var as $k=>$v) 
                    $sum += $k*$v;
            }
            echo $sum;
            ?></td>
            <td>{{ isset($point->state) ? $point->state['banknotes'] : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop

@section('script')
<style type="text/css">
    .table tbody tr:hover {
        background-color: #ececec;
    }
    #points {
        width: inherit;
    }
</style>
@stop

