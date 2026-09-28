<?php
//تضمين قاعدة البيانات
include "./db.php";
//عملية اضافة منتج
if (isset($_POST['add'])) {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    //التحقق من البيانات اذا موجودة و صحيحة
    if (empty($name) || empty($price) || empty($quantity)) {
        echo "please fill the fields";
    } elseif (!is_numeric($price) || !is_numeric($quantity) || $price <= 0 || $quantity <= 0) {
        echo "price and quantity should be numbers and greater than 0";
    } else {
        $sql = "INSERT INTO product(name, price, quantity)
            VALUES(:name, :price, :quantity)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":name" => $name,
            ":price" => $price,
            ":quantity" => $quantity,
        ]);
    }
};
//اختيار منتج للتعديل
if (isset($_POST['edit'])) {
    $ids = $_POST['ids'] ?? [];
    if (count($ids) != 1) {
        echo "select one product just to edit";
    } else {
        $id = $ids[0];
        $sql = "SELECT * FROM product WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":id" => $id]);
        $selectProduct = $stmt->fetch();
    };
};
//عملية التعديل
if (isset($_POST['save_change'])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    //التحقق من البيانات اذا موجودة و صحيحة
    if (empty($name) || empty($price) || empty($quantity)) {
        echo "please fill the fields";
    } elseif (!is_numeric($price) || !is_numeric($quantity) || $price <= 0 || $quantity <= 0) {
        echo "price and quantity should be numbers and greater than 0";
    } else {
        $sql = "UPDATE product SET name=:name, price=:price, quantity=:quantity WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":id" => $id,
            ":name" => $name,
            ":price" => $price,
            ":quantity" => $quantity,
        ]);
        echo "edit successfully";
    }
};
//عملية الحذف
if (isset($_POST['delete'])) {
    $ids = $_POST['ids'] ?? [];
    if (count($ids) === 0) {
        echo "you should select product to delete";
    } else {
        $sql = "DELETE FROM product WHERE id IN (" . implode(",", array_fill(0, count($ids), "?")) . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);
        echo "delete successfully";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="./style.css" rel="stylesheet">
</head>

<body>
    <h1>product management system</h1>
    <form action="index.php" method="post">
        <!-- ما يظهر ال id الا عند التعديل لانه عند الانشاء ينضاف له تلقائيا -->
        <?php
        if (isset($selectProduct)) {
            echo "<input type='hidden' name='id' value='" . $selectProduct['id'] . "'><br>";
        }
        ?>
        <label for="name">Product name:</label><br>
        <input type="text" name="name" value="<?= $selectProduct['name'] ?? "" ?>"><br>
        <label for="price">Product price</label><br>
        <input type="text" name="price" value="<?= $selectProduct['price'] ?? ""  ?>"><br>
        <label for="quantity">product Quantity:</label><br>
        <input type="text" name="quantity" value="<?= $selectProduct['quantity'] ?? ""  ?>"><br>
        <?php
        // يتغير ال button حسب العملية
        if (isset($selectProduct)) {
            echo "<input type='submit' name='save_change' value='save changes' class='button' >";
        } else {
            echo "<input type='submit' name='add' value='add product'  class='button'>";
        }
        ?>
    </form>
    <form action="index.php" method="post">
        <table>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Product Quantity</th>
            </tr>
            <!-- عرض المنتجات في جدول -->
            <?php
            $sql = "SELECT * FROM product";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $items = $stmt->fetchAll();
            foreach ($items as $item) {
                echo "<tr>
                    <td>
                        <input type='checkbox' name='ids[]'' value='" . $item['id'] . "'/>
                    </td>
                    <td>" . $item['name'] . "</td>
                    <td>" . $item['price'] . "</td>
                    <td>" . $item['quantity'] . "</td>
                </tr>";
            }
            ?>
        </table>
        <input type="submit" name="edit" value="edit product" class='button'>
        <input type="submit" name="delete" value="delete product" class='button red_button'>
    </form>

</body>

</html>