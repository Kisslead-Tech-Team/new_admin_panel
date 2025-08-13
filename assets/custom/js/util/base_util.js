function FORM_VALIDATION(formData) {

  if (formData.length == 0) {
    return false;
  }

  for (let item of formData) {
    // Check for empty, null, or undefined values
    if (item.value === "" || item.value === null || item.value === undefined) {
      validateError(item.error);
      return false;
    }

    // Additional check for pincode
    if (item.error === "Please enter the pincode") {
      if (!/^\d{6}$/.test(item.value)) { // Adjust regex as per your requirements
        validateError("Please enter a valid 6-digit pincode");
        return false;
      }
    }

    if (item.error === "Special characters not allowed") {
      // ✅ Allow only letters and spaces
    if (!/^[a-zA-Z0-9\s]+$/.test(item.value)) {
        validateError("Only letters and spaces are allowed");
        return false;
      }
    }

    if (item.error === "Enter a valid url") {
    // ✅ URL validation regex
    const urlPattern = /^(https?:\/\/)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(\S*)?$/;

    if (!urlPattern.test(item.value)) {
        validateError("Enter a valid URL");
        return false;
    }
}
  }

  return true;
}

function DISPLAY_IMAGE(input, element_id) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $("#" + element_id).attr("src", e.target.result).show();
    };
    reader.readAsDataURL(input.files[0]);
  }
}


function DISPLAY_IMAGES(input, container_id) {
  if (input.files && input.files.length > 0) {
    $("#" + container_id).empty(); // Clear previous images

    Array.from(input.files).forEach(file => {
      if (file.type.startsWith("image/")) { // Only images
        let reader = new FileReader();
        reader.onload = function (e) {
          let img = $("<img>")
            .attr("src", e.target.result)
            .attr("width", "130px")
            .css("padding-top", "15px");
          $("#" + container_id).append(img);
        };
        reader.readAsDataURL(file);
      }
    });
  }
}


function validateError(message) {
  $.toast({
    icon: "error",
    heading: "Warning",
    text: message,
    position: "top-right",
    bgColor: "#red",
    loader: true,
    hideAfter: 2000,
    stack: false,
    showHideTransition: "fade",
  });
}


function showToast(code, message) {
  let icon, heading, bgColor;

  switch (code) {
    case 200: // Success
      icon = "success";
      heading = "Success";
      bgColor = "#28a745"; // green
      break;
    case 300: // Warning
      icon = "warning";
      heading = "Warning";
      bgColor = "#ffc107"; // yellow
      break;
    case 400: // Error
    default:
      icon = "error";
      heading = "Error";
      bgColor = "#dc3545"; // red
      break;
  }

  $.toast({
    icon: icon,
    heading: heading,
    text: message,
    position: "top-right",
    bgColor: bgColor,
    loader: true,
    hideAfter: 2000,
    stack: false,
    showHideTransition: "fade",
  });
}

function SWAL_HANDLER({ code, msg }) {

  let _swal = {
    '200': {
      icon: 'success',
      title: 'Congratulations!'
    },
    '400': {
      icon: 'warning',
      title: 'Warning!'
    },
    '500': {
      icon: 'error',
      title: 'Error!'
    }
  }

  Swal.fire({
    title: _swal[code].title || 'Error!',
    text: msg || 'something went wrong!',
    icon: _swal[code].icon || 'error',
  });
}

function CAPITALIZE(string) {
  return string[0].toUpperCase() + string.slice(1)
}