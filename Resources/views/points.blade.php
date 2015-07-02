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
<span style="color:red;">* <i>Не отображается в state репортах</i></span>
<table>
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
            <td> -* </td>
            <td>{{ @$states[$point['id']]['banknotes'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop
