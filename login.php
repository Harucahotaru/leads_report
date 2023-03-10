<?php


require_once('assets.php');
require_once('vendor/autoload.php');

?>
<div class="container py-4">
    <div class="border border-warning rounded p-4 mx-auto" style="width: 450px">
        <h3>Login</h3>
        <form action="router.php?controller=Login&action=login" method="post">
            <div class="py-2">
                <label>Username</label>
                <input type="text" name="username">
            </div>
            <div class="py-2">
                <label>Password</label>
                <input type="text" name="password">
            </div>
            <div class="pt-4">
                <button class="btn btn-success w-100" type="submit">login</button>
            </div>
        </form>
    </div>
</div>
