var mode, masterData, brandData, categoryData = [], tools_id, tools_url, currentToolIndex;
const module = 'tools';

$(document).ready(function () {

  refreshDetails();

  //===[ Prevent modal form closing ]===
  $("#popup-modal").modal({
    backdrop: "static",
    keyboard: false,
  });

});

$('#add_tools').on('click', function () {
  mode = 'new';
  cleanPoup();

  $('#brand-select').children().remove();
  $('#brand-select').append('<option value="">Select a Brand</option>');
  $.each(brandData, function (key, value) {
    $('#brand-select').append($('<option>', { value: value.brand_id }).text(value.brand_name));
  });



  $('#category-select').children().remove();
  $('#category-select').append('<option value="">Select a Category</option>');
  $.each(categoryData, function (key, value) {
    $('#category-select').append($('<option>', { value: value.category_id }).text(value.category_name));
  });


  $('#toolsModelTitle').html('Add Tools');
  $("#popup-modal").modal("show");
});




$("#btn-submit").on('click', function () {
  $(".error").hide();
  tools_url = $('#tools_name').val().toLowerCase().trim().replace(/\s+/g, '-').replace(/-+/g, '-');

  let formObjectNew = [
    {
      value: $("#brand-select").val(),
      error: "Please select Brand Name"
    },
    {
      value: $("#category-select").val(),
      error: "Please select Category Name"
    },

    {
      value: $("#tools_name").val(),
      error: "Please enter Tools Name"
    },
    {
      value: $("#tools_name").val(),
      error: "Special characters not allowed"
    },

    {
      value: $("#tools_description").val(),
      error: "Please enter Description"
    },


    {
      value: $("#tools_img").val(),
      error: "Please Upload Tools Images"
    },

  ]

  let formObjectUpdate = [


    {
      value: $("#brand-select").val(),
      error: "Please select Brand Name"
    },
    {
      value: $("#category-select").val(),
      error: "Please select Category Name"
    },

    {
      value: $("#tools_name").val(),
      error: "Please enter Tools Name"
    },
    {
      value: $("#tools_name").val(),
      error: "Special characters not allowed"
    },

    {
      value: $("#tools_description").val(),
      error: "Please enter Description"
    },



  ]

  if (mode == "new") {
    if (FORM_VALIDATION(formObjectNew)) {
      insertToolsData();
    }
  }

  if (mode == "edit") {
    if (FORM_VALIDATION(formObjectUpdate)) {
      updateToolsData();
    }
  }

});

//====[ Edit Tools Data ]===
$(document).on("click", ".btnEdit", function () {

  mode = 'edit';
  let index = $(this).attr("id");
  currentToolIndex = index
  tools_id = masterData[index][module + '_id']

  cleanPoup();


  $('#brand-select').children().remove();
  $('#brand-select').append($('<option>', { value: masterData[index].brand_id }).text(masterData[index].brand_name));
  $.each(brandData, function (key, value) {
    $('#brand-select')
      .append($('<option>', { value: value.brand_id }).text(value.brand_name));
  });


  $('#category-select').children().remove();
  $('#category-select').append($('<option>', { value: masterData[index].category_id }).text(masterData[index].category_name));
  $.each(categoryData, function (key, value) {
    $('#category-select')
      .append($('<option>', { value: value.category_id }).text(value.category_name));
  });


  if (masterData[index].tools_brochure) {
    $('#pdfwrapper').html(
      `<a href="${baseUrl}${masterData[index].tools_brochure}" target="_blank" class="btn btn-sm btn-primary m-2">View PDF</a>`
    );
  }

  $('#toolsModelTitle').html('Edit Tools');
  $("#tools_name").val(masterData[index].tools_name);
  $("#tools_description").val(masterData[index].tools_description);
  $('#tools_ex_image_container').empty(); // Clear old images
  populateToolImages(masterData[index].images);
  $("#popup-modal").modal("show");
});


$('#tools_ex_image_container').on('click', '.delete-image', function () {
  let imgId = $(this).data('id');
  let images = masterData[currentToolIndex].images;
  // Prevent deletion if only 1 image left
  if (images.length <= 1) {
    showToast(300, "You must have at least one image.");
    return;
  }

  let imgData = images.find(img => img.tools_img_id == imgId);

  if (imgData) {
    DELETE({
      module,
      data: {
        tools_id: masterData[currentToolIndex].tools_id,
        tools_img_id: imgData.tools_img_id,
        image_path: imgData.image_path
      },
      type: 'imageDelete'
    }).then((response) => {
      if (response.code == 200) {
        showToast(response.code, response.msg);
        populateToolImages(response.data); // Populate fresh images from API
        // Also update masterData to keep it in sync
        masterData[currentToolIndex].images = response.data;
      } else {
        showToast(500, 'Something went wrong!');
      }
    });
  }
});



function populateToolImages(images) {
  $('#tools_ex_image_container').empty();
  images.forEach((img) => {
    $('#tools_ex_image_container').append(`
            <div class="img-wrapper" style="display:inline-block; position:relative; margin:5px;">
                <img src="${baseUrl}${img.image_path}" alt="Tool Image" style="width:100px; height:auto; border:1px solid #ccc; border-radius:4px;">
                <button type="button" class="btn btn-sm btn-danger delete-image" 
                    data-id="${img.tools_img_id}" style="position:absolute; top:2px; right:2px;">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        `);
  });
}
//===[ Insert Tools Data ]===
function insertToolsData() {
  let data = getFormData();
  data.append("tools_url", tools_url);

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
  });
}

//===[ Update Tools Data ]===
function updateToolsData() {


  let data = getFormData();
  data.append("tools_id", tools_id);
  data.append("tools_url", tools_url);
  PUT({ module, data }).then((response) => {
    SWAL_HANDLER(response);

    refreshDetails();
  });
}

function getOptions() {
  return Promise.all([
    GET({ module: 'brand', type: 'option' }),
    GET({ module: 'category', type: 'option' })
  ]).then(([brandRes, categoryRes]) => {
    brandData = brandRes.data;
    categoryData = categoryRes.data;
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

const BASE_URL = "<?= base_url() ?>";
console.log(BASE_URL);


function getToolsDetails() {
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
            console.log(masterData);

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
      { data: "tools_name" },
      { data: "tools_url" },
      { data: "brand_name" },
      { data: "category_name" },
      {
        data: "tools_description",
        render: function (data) {
          if (!data) return '<span class="text-muted">No Description</span>';
          return `<div style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${data}">
      ${data}
    </div>`;
        }
      },

      {
        data: "images",
        render: function (data, type, row) {
          if (Array.isArray(data) && data.length > 0) {
            return data
              .map(img => `<img src="${baseUrl + img.image_path}" alt="" style="width:100px;height:100px;object-fit:cover;margin:2px;border-radius:4px;">`)
              .join("");
          }
          return `<span class="text-muted">No Images</span>`;
        }
      },
     {
  data: "tools_brochure",
  render: function(data) {
    console.log(data);
    
    if (data && data.trim() !== "") {
      return `<a href="${baseUrl + data}" target="_blank" class="btn btn-sm btn-primary">View PDF</a>`;
    }
    return `<span class="text-muted">No PDF</span>`;
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


  Swal.fire({
    title: "Alert!",
    text: `You want to delete the ${masterData[index].tools_name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      DELETE({
        module, data: {
          tools_id: masterData[index].tools_id,
          category_id: masterData[index].category_id,
          brand_id: masterData[index].brand_id,
          tools_url: masterData[index].tools_url
        },
      }).then((response) => {
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
  return new FormData($("#tools-form")[0]);
}

function cleanPoup() {
  $('#tools-form')[0].reset();
  $('#tools_image_container').empty();
  $('#pdfwrapper').html('');
  $('#tools_ex_image_container').empty(); // Clear old images


}


$(document).on("change", "#tools_img", function () {
  DISPLAY_IMAGES(this, "tools_image_container");
});


function refreshDetails() {
  getOptions()
  getToolsDetails();
  $("#popup-modal").modal("hide");


}