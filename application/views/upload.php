<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
/**
 * Created by PhpStorm.
 * User: fengchu
 * Date: 17/9/25
 * Time: 下午7:29
 */

<!DOCTYPE html>
<html>
<body>
<table border="1">
    <tr>
        <th><input type="checkbox" name="checkbox[]" value=""></th>
        <?php foreach ($goods_list[0] as $key => $val):?>
        <th><?php echo $val;?></th>
        <?php endforeach?>
    </tr>
    <?php
    if(isset($goods_list[0])){
        unset($goods_list[0]);
    }
    ?>
    <?php foreach ($goods_list as $key => $val):?>
    <tr>
        <td><input type="checkbox" name="checkbox[]" value=""></td>
        <?php foreach ($goods_list[1] as $key2 => $val2):?>
            <th><?php echo $val[$key2]?></th>
        <?php endforeach?>
    </tr>
    <?php //print_r($val);echo '<br>';?>
    <?php endforeach?>
</table>

</body>
</html>