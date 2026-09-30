<form action='/login' method='POST'>
  <input type='hidden' name='csrf_token' value='<?= \App\Security\Csrf::token() ?>'>
  <?php if (isset($error)): ?>
    <div class="alert alert-danger" role="alert">
      <?= $error ?>
    </div>
  <?php endif; ?>
  <div class='mb-3'>
    <label for='email' class='form-label'>Adresse email</label>
    <input type='email' class='form-control' id='email' name='email' required>
  </div>
  <div class='mb-3'>
    <label for='pass' class='form-label'>Mot de passe</label>
    <input type='password' class='form-control' id='pass' name='pass' required>
  </div>
  <button type='submit' class='btn btn-primary'>Connexion</button>
</form>