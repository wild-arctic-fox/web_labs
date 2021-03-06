<?php
///////////////////////////////////////////////////////////////////////
// Global initialization
include 'init.php';

///////////////////////////////////////////////////////////////////////
// Check is user have access to this page
if ($u->checkUserAuth() == 0) {
    header('Location: ' . SITE_HOST . 'cms/index.php');
    exit();
}

///////////////////////////////////////////////////////////////////////
// Global variables
$action = filter_input_("action", "");
$viewMode = "";
$error_message = null;

$name = filter_input_("name", "");
$url = filter_input_("url", "");
$content = filter_input_("content", "");


///////////////////////////////////////////////////////////////////////
// Get data
$model = new PagesModel($mysqli);

switch ($action) {
    case "edit":
        $id = filter_input_("id", 0);
        if ($id != 0) {
            $viewMode = "edit";
            $info = $model->getPage($id);
        } else
            $error_message = "Can not edit page, incorrect id";
        break;

    case "delete":
        $id = filter_input_("id", 0);
        ($id != 0) ?
            $model->deletePage($id) :
            $error_message = "Can not delete page, incorrect id";
        break;

    case "update":
        $id = filter_input_("id", 0);
        $name = filter_input_("name", "");
        $url = filter_input_("url", "");
        $content = filter_input_("content", "");
        if ($id != 0 && !empty($name) && !empty($content) && !empty($url)) {
            $model->updatePage($id, $name, $content, $url);
            $name = "";
            $url = "";
            $content = "";
        } else {
            $info['id'] = $id;
            $info['name'] = $name;
            $info['url'] = $url;
            $info['content'] = $content;
            $viewMode = "edit";
            $error_message = "Can not update page, incorrect input data";
        }
        break;

    case "add":
        $name = filter_input_("name", "");
        $url = filter_input_("url", "");
        $content = filter_input_("content", "");
        if (!empty($name) && !empty($content) && !empty($url)) {
            $model->addPage($name, $content, $url);
            $name = "";
            $url = "";
            $content = "";
        } else
            $error_message = "Can not add page, incorrect input data";
        break;
}

//////////////////////////////////////////////////////////////
///
if ($viewMode == "")
    $list = $model->getPages();

///////////////////////////////////////// MAKE PAGE LAYOUT ////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

include "inc/header.php";

//////////////////////////////////////////////////////////////

if ($viewMode == "edit")
{ ?>
    <div class="m-auto"><h4><?= $error_message ?></h4></div>
    <div class="form-inside">
        <form class="f1" action="pages.php" method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $info['id'] ?>">
            Title
            <input required class="fadeIn second" type="text" name="name" value="<?= $info['name'] ?>">
            Content
            <textarea required name="content" class="edit"><?= $info['content'] ?></textarea>
            Url
            <input required name="url" class="fadeIn second" type="text" value="<?= $info['url'] ?>">
            <input type="submit" class="buy-item" value="Update">
        </form>
    </div>
<?php
}
else
{
?>
    <table id="customers">
        <tr>
            <td>Id</td>
            <td>Name</td>
            <td>Published Date</td>
            <td>Edit</td>
            <td>Delete</td>
        </tr>
        <?php foreach ($list as $key => $value) { ?>
            <tr>
                <td> <?= $value['id'] ?></td>
                <td> <?= $value['name'] ?></td>
                <td> <?= $value['published_date'] ?> </td>
                <td><a href="pages.php?action=edit&id=<?= $value['id'] ?>" class="buy-item2">Edit</a></td>
                <td><a href="pages.php?action=delete&id=<?= $value['id'] ?>" class="buy-item2">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
    <div class="m-auto"><h4><?= $error_message ?></h4></div>
    <div class="form-inside">
        <form class="f1" action="pages.php" method="post">
            <input type="hidden" name="action" value="add">
            Title
            <input required type="text" class="fadeIn second" name="name" placeholder="" value="<?=$name?>">
            Content
            <textarea required name="content" class="edit"><?=$content?></textarea>
            Url
            <input required type="text" class="fadeIn second" name="url" placeholder="" value="<?=$url?>">
            <input type="submit" class="buy-item" value="Add Page">
        </form>
    </div>
    <?php
}

//////////////////////////////////////////////////////////////

include "inc/footer.php";

$mysqli->close();