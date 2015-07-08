            <?php foreach($point as $poin){?>
            <tr data-id="<?= $poin->id ?>">
                <td><?= $poin->id ?></td>
                <td>
                    <?= $poin->name ?> <br />
                </td>
                <td><i class="fa fa-circle" style="color:<?= ($poin->state['state'] == 200 && $poin->state['diff'] < 300) ? 'green' : 'orange' ?>;"></i> <?= $poin->state['state'] ?></td>
                <td><?= $poin->state['banknotes'] ?></td>
                <td><?= $poin->state['created'] ?></td>
            </tr>
            <?php }  ?>
