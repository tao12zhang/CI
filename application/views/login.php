
/**
 * Created by PhpStorm.
 * User: fengchu
 * Date: 17/9/30
 * Time: 下午2:02
 */

<html>
<?php //echo validation_errors(); ?>
<?php //echo form_open('login/formsubmit'); ?>
<form method="post" action="login/formsubmit">
<table>
    <tr>
        <td>用户名</td>
        <td><input type="text" name="username"></td>
    </tr>
    <tr>
        <td>密码</td>
        <td><input type="password" name="password"></td>
    </tr>
    <tr>
        <td>
            <input type="submit" name="submit" value="login">
        </td>
        <td>
            <input type="submit" name="submit" value="register">
        </td>
    </tr>
</table>
</form>
</html>