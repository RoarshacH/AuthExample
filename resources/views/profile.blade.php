@yield('contents')

<h6> {{ session('data')['email'] }} </h6>
<h6> {{ session('data')['userID'] }} </h6>
<h6> {{ session('data')['token'] }} </h6>

