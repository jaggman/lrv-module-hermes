<?php foreach($state as $k=>$sts){?>
<tr>
    <td><?= $k ?></td>
    <td><?= @$point[$k]['name'] ?></td>
    <td><i class="fa fa-circle" style="color:<?= ($sts['state'] == 200 && $sts['diff'] < 300) ? 'green' : 'orange' ?>;"></i> <?= $sts['state'] ?></td>
    <td><?= $sts['banknotes'] ?></td>
    <td><?= $sts['created'] ?></td>
</tr>
<?php } ?>
