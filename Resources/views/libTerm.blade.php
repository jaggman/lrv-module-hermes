<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//use Illuminate\Html\Form;
?>
@extends('hermes::layouts.layout')

@section('title', 'Lib-Term')

@section('content')
    <table id="points">
        <thead>
            <tr>
                <th>Терминалы</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($point as $poin){?>
            <tr>
                <td><?= $poin['name'] ?></td>
            </tr>
            <?php }  ?>
        </tbody>
    </table>
<?= Form::model(new \App\Models\Point) ?>
<h3>Создать</h3>
<?= Form::label('id', 'Идентификатор:').Form::text('id', \Input::old('id')); ?>
<?php if ($errors->has('id')) {
  echo '<br />'.$errors->first('id');
}  ?> <br />
<?= Form::label('name', 'Название:').Form::text('name', \Input::old('name')); ?>
<?php if ($errors->has('name')) {
  echo '<br />'.$errors->first('name');
}  ?> <br />
<?= Form::submit('Добавить!') ?>
<?= Form::close() ?>
@stop
