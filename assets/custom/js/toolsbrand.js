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
      error: "Please enter Brand Name !"
    },

    {
      value: $("#brand_name").val(),
      error: "Special characters not allowed"
    },
    {
      value: $("#logo_path").val(),
      error: "Please Upload Logo !"
    }

  ]

  let formObjectUpdate = [
    
    {
      value: $("#brand_name").val(),
      error: "Please enter Brand Name !"
    },
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
   if (masterData[index].logo_path) {
    $("#logo_path_url").attr('src', masterData[index].logo_path);
    $("#logo_path_url").show();
  }


  $("#popup-modal").modal("show");
});

//===[ Insert Brand Data ]===
function insertBrandData() {
  let data = getFormData();
  data.append("url", url);
  showLoader();
  POST({ module, data }).then((response) => {
    SWAL_HANDLER(response);

    // Clear 'start' and 'page' from URL
    const params = new URLSearchParams(window.location.search);
    params.delete('start');
    const newQuery = params.toString();
    const newUrl = newQuery ? `${location.pathname}?${newQuery}` : location.pathname;
    window.history.replaceState({}, '', newUrl);

    // Reset DataTable to first page & reload
    table.page(0).draw(false);

    // Hide modal if open
    $("#popup-modal").modal("hide");
  }).finally(() => {
        hideLoader(); // 🔹 hide loader after response (success or error)
    });
}

//===[ Update Brand Data ]===
function updateBrandData() {


  let data = getFormData();
  data.append("brand_id", brand_id);
  data.append("url", url);
  showLoader();
  PUT({ module, data }).then((response) => {
    SWAL_HANDLER(response);

    refreshDetails();
  }).finally(() => {
        hideLoader(); // 🔹 hide loader after response (success or error)
    });
}


let table; // global

function getPageStartFromURL() {
  const params = new URLSearchParams(window.location.search);
  return parseInt(params.get('start') || 0, 10);
}

function getSearchFromURL() {
  const params = new URLSearchParams(window.location.search);
  return params.get('search') || '';
}

function getBrandDetails() {
  if ($.fn.DataTable.isDataTable('#datatable')) {
    table.ajax.reload(null, false);
    return;
  }

  table = $('#datatable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    ordering: false,
    displayStart: getPageStartFromURL(),
    search: { search: getSearchFromURL() },

    ajax: function (data, callback) {
      const params = new URLSearchParams();
      params.set('start', data.start);
      params.set('length', data.length);
      params.set('draw', data.draw);
      params.set('search', data.search.value);

      const moduleWithParams = module + '?' + params.toString();

      GET({ module: moduleWithParams })
        .then((res) => {
          if (res.code === 200) {
            masterData = res.data.data;
            
            callback({
              draw: data.draw,
              recordsTotal: res.data.recordsTotal,
              recordsFiltered: res.data.recordsFiltered,
              data: res.data.data
            });
          } else {
            callback({
              draw: data.draw,
              recordsTotal: 0,
              recordsFiltered: 0,
              data: []
            });
          }
        })
        .catch(() => {
          callback({
            draw: data.draw,
            recordsTotal: 0,
            recordsFiltered: 0,
            data: []
          });
        });
    },

 columns: [
  {
    data: null,
    render: function (data, type, row, meta) {
      return meta.row + meta.settings._iDisplayStart + 1;
    }
  },
  { data: "brand_name" },
  { data: "url" },
    {
        data: "logo_path",
        render: function (data, type, row) {
          if (!data) {
            return '<span class="text-muted">No image</span>';
          }
          return `<img src="${baseUrl + data}" alt="Gallery Image" style="width:100px;height:auto;border-radius:4px;">`;
        }
      },
  {
    data: null,
    render: function (data, type, row, meta) {
      return `
        <a id="${meta.row}" class="btn btnEdit text-info fs-14 lh-1"><i class="ri-edit-line"></i></a>
        <a id="${meta.row}" class="btn BtnDelete text-danger fs-14 lh-1"><i class="ri-delete-bin-5-line"></i></a>
      `;
    }
  }
]

  });

  // Update URL on page or search change
  table.on('page.dt search.dt', function () {
    const info = table.page.info();
    const searchTerm = table.search();
    const params = new URLSearchParams(window.location.search);

    params.set('start', info.start);

    if (searchTerm) {
      params.set('search', searchTerm);
    } else {
      params.delete('search');
    }

    const newUrl = `${location.pathname}?${params.toString()}`;
    window.history.replaceState({}, '', newUrl);
  });
}

//===[Delete Data]===
$(document).on("click", ".BtnDelete", function () {
  let index = $(this).attr("id");
  let brand_id = masterData[index][module + '_id'];

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

        // After deletion, reload the table and check page state
        const info = table.page.info();

        // If current page has only one record (which will be deleted),
        // and it's not the first page, move to previous page
        if (info.end - info.start === 1 && info.page > 0) {
          table.page(info.page - 1).draw(false);

          // Update URL: remove or adjust 'start' param accordingly
          const params = new URLSearchParams(window.location.search);
          let newStart = (info.start - info.length);
          if (newStart <= 0) {
            params.delete('start');
          } else {
            params.set('start', newStart);
          }
          window.history.replaceState({}, '', `${location.pathname}?${params.toString()}`);

        } else {
          // Otherwise, just reload current page
          table.ajax.reload(null, false);
        }
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
      console.error("Error");
    },
  });
});

$(document).on("change", "#logo_path", function () {
  DISPLAY_IMAGE(this, "logo_path_url");
});

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