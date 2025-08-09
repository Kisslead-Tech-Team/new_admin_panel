var mode, masterData = [], brand_id, url;
const module = 'brand';

$(document).ready(function () {

  refreshDetails();

  //===[ Prevent modal form closing ]===
  $("#popup-modal").modal({
    backdrop: "static",
    keyboard: false,
  });

});

$('#add_brand').on('click', function () {
  mode = 'new';
  cleanPoup();

  $('#brandModelTitle').html('Add Brand');
  $("#popup-modal").modal("show");
});




$("#btn-submit").on('click', function () {
  $(".error").hide();
  url = $('#brand_name').val().toLowerCase().trim().replace(/\s+/g, '-').replace(/-+/g, '-');

  let formObjectNew = [


    {
      value: $("#brand_name").val(),
      error: "Special characters not allowed"
    }

  ]

  let formObjectUpdate = [
    {
      value: $("#brand_name").val(),
      error: "Special characters not allowed"
    }
  ]

  if (mode == "new") {
    if (FORM_VALIDATION(formObjectNew)) {
      insertBrandData();
    }
  }

  if (mode == "edit") {
    if (FORM_VALIDATION(formObjectUpdate)) {
      updateBrandData();
    }
  }

});

//====[ Edit Brand Data ]===
$(document).on("click", ".btnEdit", function () {

  mode = 'edit';
  let index = $(this).attr("id");

  brand_id = masterData[index][module + '_id']
  cleanPoup();

  $('#brandModelTitle').html('Edit Brand');
  $("#brand_name").val(masterData[index].brand_name);
  $("#url").val(masterData[index].url);


  $("#popup-modal").modal("show");
});

//===[ Insert Brand Data ]===
function insertBrandData() {

  let data = getFormData();
  data.append("url", url);
  POST({ module, data }).then((response) => {
    SWAL_HANDLER(response);
    refreshDetails();
  });

}

//===[ Update Brand Data ]===
function updateBrandData() {


  let data = getFormData();
  data.append("brand_id", brand_id);
  data.append("url", url);
  PUT({ module, data }).then((response) => {
    SWAL_HANDLER(response);

    refreshDetails();
  });
}

//====[ Get All Brand Data ]===
function getBrandDetails() {
  GET({ module }).then((data) => {
    masterData = (data);

    displayBrandDetails(masterData);
  });
}

//===[ Display Brand details]===
function displayBrandDetails(tableData) {

  console.log(tableData);
  


  //===[Destroy Data Table]===
  if ($.fn.DataTable.isDataTable('#datatable')) {
    $('#datatable').DataTable().destroy();
  }

  $('#datatable tbody').empty();


  if (typeof tableData === 'string') {

    $('#datatable').dataTable({
      "oLanguage": {
        "sEmptyTable": "Brand table is empty"
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
          mDataProp: "brand_name",
        },
        {
          mDataProp: "url",
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
  brand_id = masterData[index][module + '_id'];

  Swal.fire({
    title: "Alert!",
    text: `You want to delete the ${masterData[index].brand_name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {

      DELETE({ module, data: { brand_id } }).then((response) => {
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
  return new FormData($("#brand-form")[0]);
}

function cleanPoup() {
  $('#brand-form')[0].reset();
}

function refreshDetails() {
  getBrandDetails();
  $("#popup-modal").modal("hide");
}