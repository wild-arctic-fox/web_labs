<?php

$list = $this->list;
$error_message = $this->error_message;

?>

<table id="customers">
    <tr>
        <td>Id</td>
        <td>Name</td>
        <td>Serial number</td>
        <td>Edit</td>
        <td>Delete</td>
    </tr>
    <?php foreach ($list as $key => $value) { ?>
        <tr>
            <td> <?= $value['id'] ?></td>
            <td> <?= $value['name'] ?></td>
            <td> <?= $value['s_num'] ?> </td>
            <td><a href="<?= Controller::formatUrl('ProductsController', 'edit',array('id'=>$value['id']))?>" class="buy-item2">Edit</a></td>
            <td><a href="<?= Controller::formatUrl('ProductsController', 'delete',array('id'=>$value['id']))?>" class="buy-item2">Delete</a></td>
        </tr>
    <?php } ?>
</table>

<?php if (!empty($error_message)) { ?>
    <div class="m-auto"><h4><?= $error_message ?></h4></div>
<?php } ?>
