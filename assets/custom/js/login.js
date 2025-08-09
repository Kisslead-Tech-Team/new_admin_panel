$(document).ready(function () {});

$("#signin-btn").click(function () {

  $username = $("#username").val();
  $password = $("#password").val();

  if ($username == "") {
    $.toast({
      icon: "warning",
      heading: "Warning",
      text: "Please Enter username!!",
      position: "top-left",
      bgColor: "#red",
      loader: true,
      hideAfter: 2000,
      stack: false,
      showHideTransition: "fade",
    });
    return

  } else if ($password == "") {
    $.toast({
      icon: "warning",
      heading: "Warning",
      text: "Please Enter Password!!",
      position: "top-left",
      bgColor: "#red",
      loader: true,
      hideAfter: 2000,
      stack: false,
      showHideTransition: "fade",
    });
    return
  }
    loginCheck($username, $password);
});

  function loginCheck(username, password) {
    
    $.ajax({
      type: "POST",
      url: base_url + "login-check",
      data: { username, password },
      dataType: "json",
      success: function (data) {
        
        if (data.code == 200) {
          window.location.replace(base_url + "dashboard");
        }
        
        if(data.code == 400){
          $.toast({
            icon: "warning",
            heading: "Warning",
            text: "Invalid credentials",
            position: "top-left",
            bgColor: "#red",
            loader: true,
            hideAfter: 2000,
            stack: false,
            showHideTransition: "fade",
          });
        }
      },
      error: function (textStatus) {
        console.error("Error:", textStatus);
      },
    });
    
  }
