<div class="container h-100 mt-5">
    <div class="row h-100 justify-content-center align-items-center">
      <div class="col-10 col-md-8 col-lg-6">
        <h3>Update user</h3>
        <form action="{{ route('users.update', $user->id) }}" method="post">
          @csrf
          @method('PUT')
          <div class="form-group">
            <label for="title">name</label>
            <input type="text" class="form-control" id="name" name="name"
              value="{{ $user->name }}" required>
          </div>
          <div class="form-group">
            <label for="body">email</label>
            <textarea class="form-control" id="email" name="email" rows="3" required>{{ $user->email }}</textarea>
          </div>
          <div class="form-group">
            <label for="body">role</label>
            <textarea class="form-control" id="role" name="role" rows="3" required>{{ $user->role}}</textarea>
          </div>
          
          <button type="submit" class="btn mt-3 btn-primary">Update user</button>
        </form>
      </div>
    </div>
  </div>