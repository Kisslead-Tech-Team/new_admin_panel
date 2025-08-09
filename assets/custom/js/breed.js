var mode, masterData, petData = [], breed_id;
const module = 'breed';

$(document).ready(function () {

  refreshDetails();

  //===[ Prevent modal form closing ]===
  $("#popup-modal").modal({
    backdrop: "static",
    keyboard: false,
  });

});

$('#add_breed').on('click', function () {
  mode = 'new';
  cleanPoup();

  $('#breed-pet-select').children().remove();
  $('#breed-pet-select').append('<option value="">Select a pet</option>');
  $.each(petData, function (key, value) {
    $('#breed-pet-select')

      .append($('<option>', { value: value.pet_id })
        .text(value.pet_name));

  });

  $('#breedModelTitle').html('Add Pet');
  $("#popup-modal").modal("show");
});



$("#btn-submit").on('click', function () {

  $(".error").hide();

  let formObjectNew = [
    {
      value: $("#breed-pet-select").val(),
      error: "Please select the pet name"
    },
    {
      value: $("#breed_name").val(),
      error: "Please enter breed name"
    }
  ]

  let formObjectUpdate = [
    {
      value: $("#breed_name").val(),
      error: "Please enter breed name"
    }
  ]

  if (mode == "new") {
    if (FORM_VALIDATION(formObjectNew)) {
      insertBreedData();
    }
  }

  if (mode == "edit") {
    if (FORM_VALIDATION(formObjectUpdate)) {
      updateBreedData();
    }
  }

});

//====[ Edit Pet Data ]===
$(document).on("click", ".btnEdit", function () {

  mode = 'edit';
  let index = $(this).attr("id");

  breed_id = masterData[index][module + '_id']
  cleanPoup();

  $('#breedModelTitle').html('Edit breed');


  $('#breed-pet-select').children().remove();
  $('#breed-pet-select').append($('<option>', { value: masterData[index].pet_id }).text(masterData[index].pet_name));
  $.each(petData, function (key, value) {
    $('#breed-pet-select')
      .append($('<option>', { value: value.pet_id }).text(value.pet_name));
  });

  $("#breed_name").val(masterData[index].breed_name);

  $("#popup-modal").modal("show");
});

//===[ Insert Pet Data ]===
function insertBreedData() {

  let data = getFormData();
  POST({ module, data }).then((response) => {
    SWAL_HANDLER(response);
    refreshDetails();
  });

}

//===[ Update Pet Data ]===
function updateBreedData() {


  let data = getFormData();
  data.append("breed_id", breed_id);

  PUT({ module, data }).then((response) => {
    SWAL_HANDLER(response);

    refreshDetails();
  });
}

//===[Display image on modal]===
// $(document).on("change", "#pet_img", function () {
//   DISPLAY_IMAGE(this, "pet_image_url");
// });

//====[ Get All Pet Data ]===
function getBreedDetails() {
  GET({ module }).then((data) => {
    masterData = (data);

    displayPetDetails(masterData);
  });
}

function getpetDetails() {
  GET({ module: 'pet' }).then((data) => {
    petData = data;
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
          mDataProp: "breed_name",
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
  breed_id = masterData[index][module + '_id'];

  Swal.fire({
    title: "Alert!",
    text: `You want to delete the ${masterData[index].breed_name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {

      DELETE({ module, data: { breed_id } }).then((response) => {
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
  return new FormData($("#breed-form")[0]);
}

function cleanPoup() {
  $('#breed-form')[0].reset();
  // $('#pet_image_url').hide();
}

function refreshDetails() {
  getpetDetails();
  getBreedDetails();
  $("#popup-modal").modal("hide");
}