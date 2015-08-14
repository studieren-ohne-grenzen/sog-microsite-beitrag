function hideAlerts() {
  $(".alert").addClass("hidden");
}

function hideLogin() {
  $("#login").addClass("hidden");
}

function setCurrent() {
  $.post("php/read.php")
  .done(function(data) {
    $("#currentUsername").text(data["username"]);
    $("#currentAmount").text(data["amount"].toFixed(2) + " €");
    $("#currentInterval").text(data["interval"]);
  })
  .fail(function() {
    $("#currentUsername").text("???");
    $("#currentAmount").text("??? €");
    $("#currentInterval").text("???");
  });
}

function showChange() {
  $("#change").removeClass("hidden");
}

function showError() {
  $("#error").removeClass("hidden");
}

function showLogin() {
  $("#login").removeClass("hidden");
}

function showSuccess() {
  $("#success").removeClass("hidden");
}

$.post("php/auth.php")
.done(function(data) {
  if (data["username"]) {
    setCurrent();
    showChange();
  } else {
    showLogin();
  }
})
.fail(function() {
  showLogin();
});

$("#loginButton").click(function(e) {
  e.preventDefault();

  hideAlerts();

  $.post("php/auth.php", $("#loginForm").serialize())
  .done(function(data) {
    if (data["error"]) {
      showError();
    } else {
      setCurrent();
      hideLogin();
      showChange();
    }
  })
  .fail(function() {
    showError();
  });
});

$("#changeButton").click(function(e) {
  e.preventDefault();

  hideAlerts();

  $.post("php/update.php", $("#changeForm").serialize())
  .done(function(data) {
    if (data["error"]) {
      showError();
      setCurrent();
    } else {
      showSuccess();
      setCurrent();
    }
  })
  .fail(function() {
    showError();
    setCurrent();
  });
});
