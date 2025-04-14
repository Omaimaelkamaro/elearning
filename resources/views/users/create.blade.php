// same as the previous file. Add the following after the nav tag and before the closing body tag.
<div class="container h-100 mt-5">
  <div class="row h-100 justify-content-center align-items-center">
    <div class="col-10 col-md-8 col-lg-6">
      <h3>Add a user</h3>
      <form action="{{ route('users.store') }}" method="post">
        @csrf
        <div class="form-group">
          <label for="title">nom</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
          <label for="body">email</label>
          <textarea class="form-control" id="email" name="email" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="role">role</label>
            <textarea class="form-control" id="role" name="role" rows="3" required></textarea>
          </div>
        <div class="form-group">
            <label for="body">password</label>
            <textarea class="form-control" id="password" name="password" rows="3" required></textarea>
          </div>
        <br>
        <button type="submit" class="btn btn-primary">Create user</button>
      </form>
    </div>
  </div>
</div>