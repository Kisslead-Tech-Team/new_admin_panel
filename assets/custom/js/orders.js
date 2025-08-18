


var mode, masterData = [], order_id;
const module = 'orders';
$(document).ready(function () {

  refreshDetails();

});

$('#export_orders').on('click', function () {

    GET({ module: module })
        .then((res) => {
          
            if (res.code === 200 && res.data.data.length > 0) {

                let exportData = res.data.data;

                console.log(exportData);
                

                // Create worksheet from array of objects
                let worksheet = XLSX.utils.json_to_sheet(exportData);

                // Create workbook and append sheet
                let workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, "Orderss");

                // Trigger Excel file download
                XLSX.writeFile(workbook, 'orders_export.xlsx');
                showToast(200, "Orders data downloaded!");

            } else {
                    showToast(300, "No order data available for export.");

            }
        })
       .catch(() => {
              showToast(400, "Error fetching orders data.");

        });
        
});

$("#btn-submit").on('click', function () {
  $(".error").hide();



  let formObjectUpdate = [


    {
      value: $("#status").val(),
      error: "Please select status"
    }
    



  ]



  if (mode == "edit") {
    if (FORM_VALIDATION(formObjectUpdate)) {
      updateOrdersData();
    }
  }

});

$(document).on("click", ".btnEdit", function () {

  mode = 'edit';
  let index = $(this).attr("id");


  order_id = masterData[index]['order_id']


  
  cleanPoup();

  $('#ordersModelTitle').html('Edit Status');
  $("#status").val(masterData[index].flag);


  $("#popup-modal").modal("show");
});


function updateOrdersData() {
  let data = getFormData();
  data.append("order_id", order_id);
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

function getOrdersDetails() {
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
      { data: "name" },
      { data: "email" },
      { data: "contact" },
      { data: "tools_name" },
       { data: "quantity" },
{ 
    data: "flag",
    render: function (data, type, row) {
        if (data == 1) {
            return '<span class="badge bg-primary">New</span>';
        } else if (data == 2) {
            return '<span class="badge bg-success">Completed</span>';
        } else {
            return '<span class="badge bg-secondary">Unknown</span>';
        }
    }
},         {
        data: null,
        render: function (data, type, row, meta) {
          return `   <a id="${meta.row}" class="btn btnEdit text-info fs-14 lh-1"><i class="ri-edit-line"></i></a>
          <a id="${meta.row}" class="btn BtnDelete text-danger fs-14 lh-1"><i class="ri-delete-bin-5-line"></i></a>`;
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
  let order_id = masterData[index]['order_id'];

  

  Swal.fire({
    title: "Alert!",
    text: `You want to delete the ${masterData[index].name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      DELETE({ module, data: { order_id } }).then((response) => {
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
  return new FormData($("#orders-form")[0]);
}

function cleanPoup() {
  $('#orders-form')[0].reset();



}


function refreshDetails() {
  getOrdersDetails();
    $("#popup-modal").modal("hide");

}