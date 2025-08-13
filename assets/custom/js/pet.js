var mode, masterData = [], pet_id;
const module = 'pet';

$(document).ready(function () {

  refreshDetails();

  //===[ Prevent modal form closing ]===
  $("#popup-modal").modal({
    backdrop: "static",
    keyboard: false,
  });

});

$('#add_pet').on('click', function () {
  mode = 'new';
  cleanPoup();

  $('#petModelTitle').html('Add Pet');
  $("#popup-modal").modal("show");
});



$("#btn-submit").on('click', function () {

  $(".error").hide();

  let formObjectNew = [
    {
      value: $("#pet_name").val(),
      error: "Please entrer the pet name"
    },
    {
      value: $("#pet_img").val(),
      error: "Please upload the pet image"
    }
  ]

  let formObjectUpdate = [
    {
      value: $("#pet_name").val(),
      error: "Please entrer the pet name"
    }
  ]

  if (mode == "new") {
    if (FORM_VALIDATION(formObjectNew)) {
      insertPetData();
    }
  }

  if (mode == "edit") {
    if (FORM_VALIDATION(formObjectUpdate)) {
      updatePetData();
    }
  }

});

//====[ Edit Pet Data ]===
$(document).on("click", ".btnEdit", function () {

  mode = 'edit';
  let index = $(this).attr("id");

  pet_id = masterData[index][module + '_id']
  cleanPoup();

  $('#petModelTitle').html('Edit Pet');
  $("#pet_name").val(masterData[index].pet_name);

  if (masterData[index].pet_img) {
    $("#pet_image_url").attr('src', masterData[index].pet_img);
    $("#pet_image_url").show();
  }

  $("#popup-modal").modal("show");
});

//===[ Insert Pet Data ]===
function insertPetData() {

  let data = getFormData();
  POST({ module, data }).then((response) => {
    SWAL_HANDLER(response);
    refreshDetails();
  });

}

//===[ Update Pet Data ]===
function updatePetData() {


  let data = getFormData();
  data.append("pet_id", pet_id);

  PUT({ module, data }).then((response) => {
    SWAL_HANDLER(response);

    refreshDetails();
  });
}

//===[Display image on modal]===
$(document).on("change", "#pet_img", function () {
  DISPLAY_IMAGE(this, "pet_image_url");
});

//====[ Get All Pet Data ]===
function getpetDetails() {
  GET({ module }).then((data) => {
    masterData = (data);

    displayPetDetails(masterData);
  });
}

//===[ Display Pet details]===
function displayPetDetails(tableData) {


  //===[Destroy Data Table]===
  if ($.fn.DataTable.isDataTable('#datatable')) {
    $('#datatable').DataTable().destroy();
  }

  $('#datatable tbody').empty();


  if (typeof tableData === 'string') {

    $('#datatable').dataTable({
      "oLanguage": {
        "sEmptyTable": "My Custom Message On Empty Table"
      }
    });

  }
  else {

    $("#datatable").DataTable({
      destroy: true,
      aaSorting: [],
      aaData: tableData,
      aoColumns: [
        {
          mDataProp: null,
          render: function (data, type, row, meta) {
            return meta.row + 1;
          },
        },
        {
          mDataProp: "pet_name",
        },
        {
          mDataProp: function (data, type, full, meta) {
            if (data.pet_img && data.pet_img !== "") {
              return (`<div class="text-center">
                <img src='${data.pet_img}' alt='not-image' width='100'>
              </div>`);
            } else {
              return "<div class='d-flex align-items-center justify-content-center'>No Image</div>";
            }
          },
        },
        {
          mDataProp: function (data, type, full, meta) {
            return (
              `<a id="${meta.row}" class="btn btnEdit text-info fs-14 lh-1"> <i class="ri-edit-line"></i></a>
            <a id="${meta.row}" class="btn BtnDelete text-danger fs-14 lh-1"> <i class="ri-delete-bin-5-line"></i></a>`
            );
          },
        },
      ],
    });

  }
}

//===[Delete Data]===
$(document).on("click", ".BtnDelete", function () {

  let index = $(this).attr("id");
  mode = "delete";
  pet_id = masterData[index][module + '_id'];
  pet_img = masterData[index][module + '_img'];

  Swal.fire({
    title: "Alert!",
    text: `You want to delete the ${masterData[index].pet_name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {

      DELETE({ module, data: { pet_id, pet_img } }).then((response) => {
        SWAL_HANDLER(response);
        refreshDetails();
      });
    }
  });
});

$(document).on('change', '#navbar_title_id', function () {

  let id = $(this).val();

  $.ajax({
    type: "POST",
    url: base_url + "getsubmenu",
    data: {
      id: id
    },
    dataType: "json",
    success: function (data) {
      $('#navbar_page_id').html('<option value="">Select</option>' + data);
    },
    error: function () {
      console.log("Error");
    },
  });
})

function getFormData() {
  return new FormData($("#pet-form")[0]);
}

function cleanPoup() {
  $('#pet-form')[0].reset();
  $('#pet_image_url').hide();
}

function refreshDetails() {
  getpetDetails();
  $("#popup-modal").modal("hide");
}