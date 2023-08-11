<?php if(isset($_SESSION['msg'])): ?>
    <div class="alert alert-<?= $_SESSION['msg']['color'] ?>"><?= $_SESSION['msg']['message'] ?></div>
    <?php unset($_SESSION['msg']) ;endif; ?>