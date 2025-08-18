var mode, masterData = [], gallery_id;
const module = 'gallery';

$(document).ready(function () {

  refreshDetails();

  //===[ Prevent modal form closing ]===
  $("#popup-modal").modal({
    backdrop: "static",
    keyboard: false,
  });

});



$('#add_gallery').on('click', function () {
  mode = 'new';
  cleanPoup();

  $('#galleryModelTitle').html('Add Gallery');
  $("#popup-modal").modal("show");
});




$("#btn-submit").on('click', function () {
  $(".error").hide();

  let formObjectNew = [

    {
      value: $("#gallery_name").val(),
      error: "Please enter Gallery Name !"
    },

    {
      value: $("#gallery_img").val(),
      error: "Please Upload Image !"
    }



  ]

  let formObjectUpdate = [

    {
      value: $("#gallery_name").val(),
      error: "Please enter Gallery Name !"
    }
  ]

  if (mode == "new") {
    if (FORM_VALIDATION(formObjectNew)) {
      insertGalleryData();
    }
  }

  if (mode == "edit") {
    if (FORM_VALIDATION(formObjectUpdate)) {
      updateGalleryData();
    }
  }

});

//====[ Edit Gallery Data ]===
$(document).on("click", ".btnEdit", function () {

  mode = 'edit';
  let index = $(this).attr("id");


  gallery_id = masterData[index][module + '_id']
  cleanPoup();

  $('#galleryModelTitle').html('Edit Gallery');
  $("#gallery_name").val(masterData[index].gallery_name);
  if (masterData[index].image_path) {
    $("#gallery_image_url").attr('src', masterData[index].image_path);
    $("#gallery_image_url").show();
  }
  $("#popup-modal").modal("show");
});

//===[ Insert Gallery Data ]===
function insertGalleryData() {
  let data = getFormData();

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

//===[ Update Gallery Data ]===
function updateGalleryData() {


  let data = getFormData();
  data.append("gallery_id", gallery_id);

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

function getGalleryDetails() {
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
      { data: "gallery_name" },
      {
        data: "image_path",
        render: function (data, type, row) {
          if (!data) {
            return '<span class="text-muted">No image</span>';
          }
          return `<img src="${baseUrl + data}" alt="Gallery Image" style="width:50px;height:auto;border-radius:4px;">`;
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
  let gallery_data = masterData[index];


  Swal.fire({
    title: "Alert!",
    text: `You want to delete the ${masterData[index].gallery_name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      DELETE({ module, data: { image_path:gallery_data.image_path, gallery_id:gallery_data.gallery_id } }).then((response) => {
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
})

function getFormData() {
  return new FormData($("#gallery-form")[0]);
}
$(document).on("change", "#gallery_img", function () {
  DISPLAY_IMAGE(this, "gallery_image_url");
});

function cleanPoup() {
  $('#gallery-form')[0].reset();
  $('#gallery_image_url').hide();

}

function refreshDetails() {
  getGalleryDetails();
  $("#popup-modal").modal("hide");
}