<?php
include __VIEW__ . 'partials/header.php'; ?>


<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Status</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($users as $user): ?>
    <tr>
        <th scope="row"><?= $user['id'] ?></th>
        <td><?= $user['name'] ?></td>
        <td><?= $user['email'] ?></td>
        <td>@<?= $user['status'] ?></td>
        <td>
            <a href="" class="btn btn-primary">Show</a>
            <a href="" class="btn btn-secondary">Update</a>
            <form action="/users" method="post">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
              <button type="submit" class="btn btn-danger">Delete</button>
            </form>
            <?php if($user['status'] == 'new'): ?>
                <form action="/verify" method="post">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <button type="submit" class="btn btn-primary">Verify</button>
                </form>
             <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php
include __VIEW__ . 'partials/footer.php'; ?>


