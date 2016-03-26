<form action="/?a=authenticate" method="post">
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" name="password" placeholder="Password" />
  </div>
  <button type="submit" class="btn btn-primary"><?php if($password_set) echo 'Login'; else echo 'Save password'; ?></button>
</form>
