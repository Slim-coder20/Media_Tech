<div class="header">
  <div class="header-left">
      <a href="index.html" class="logo">
          <img src="{{asset('back_auth/assets/profile/'.Auth::user()->image) }}" width="30" height="30" alt="logo" class="rounded-circle object-fit-cover"/>
          <span class="logoclass">{{Auth::user()->name}}</span>
      </a>
      <a href="index.html" class="logo logo-small">
          <img src="{{ asset('back_auth/assets/profile/'.Auth::user()->image) }}" alt="Logo" width="30" height="30" class="rounded-circle object-fit-cover" />
      </a>
  </div>
  <a href="javascript:void(0);" id="toggle_btn">
      <i class="fe fe-text-align-left"></i>
  </a>
  <a class="mobile_btn" id="mobile_btn"> <i class="fas fa-bars"></i> </a>
  <ul class="nav user-menu">

      <li class="nav-item dropdown has-arrow">
          <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
              <span class="user-img"><img class="rounded-circle object-fit-cover" src="{{ asset('back_auth/assets/profile/'.Auth::user()->image) }}"
                      width="31" height="31" alt="{{ Auth::user()->name }}" /></span>
          </a>
          <div class="dropdown-menu">
              <div class="user-header">
                  <div class="avatar avatar-sm">
                      <img src="{{ asset('back_auth/assets/profile/'.Auth::user()->image) }}" alt="{{ Auth::user()->name }}"
                          class="avatar-img rounded-circle object-fit-cover" width="40" height="40" />
                  </div>
                  <div class="user-text">
                      <h6></h6>
                      <p class="text-muted mb-0">Administrateur</p>
                  </div>
              </div>
              <a class="dropdown-item" href="{{ route('profile.edit')}}">Profile</a>
              <a class="dropdown-item" href="{{ route('profile.update')}}">Paramettre</a>
              <a class="dropdown-item" href="{{ route('logout')}}">Deconnexion</a>
          </div>
      </li>
  </ul>
  <div class="top-nav-search">
      <form>
          <input type="text" class="form-control" placeholder="Search here" />
          <button class="btn" type="submit">
              <i class="fas fa-search"></i>
          </button>
      </form>
  </div>
</div>