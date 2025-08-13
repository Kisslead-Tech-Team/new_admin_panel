const ERROR_STATUS = JSON.stringify({
    code : 500,
    msg: "Something went wrong!"
});


function GET({module, type=null}) {

    if(type){
         return new Promise((resolve, reject) => {
        $.ajax({
            type: "GET",
            url: base_url + 'getoption' + module,
            dataType: "json",
            success: function (response) {
           
                    resolve((response));
                
            },
            error: function (err) {
                reject(err);
            },
        });
    }); 
    }

    
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "GET",
            url: base_url + 'get' + module,
            dataType: "json",
            success: function (response) {
           
                    resolve((response));
                
            },
            error: function (err) {
                reject(err);
            },
        });
    });
}


function POST({module, module_id, data}){


    if(module_id){
    
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: base_url + 'getspecific' + module,
                data: { id: module_id },
                dataType: "json",
                success: function (response) {
                    resolve(response); 
                },
                error: function (err) {
                    reject(err);
                },
            });
        });


    }


       return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: base_url + 'insert' + module,
             data: data,
        dataType: "json",
        cache : false,
        processData: false,
        contentType: false,
            success: function (response) {
                resolve(response); 
            },
            error: function (err) {
                reject(err);
            },
        });
    });


}

function PUT({module, data}){


         return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: base_url + 'update' + module,
     data: data,
        dataType: "json",
        cache : false,
        processData: false,
        contentType: false,
            success: function (response) {
              resolve(response); 
            },
            error: function (err) {
                reject(err);
            },
        });
    });


}

function DELETE({module, data, type=null}){

    if(type == 'imageDelete'){
          return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: base_url + 'deleteimage' + module,
             data: data,
            dataType: "json",
            success: function (response) {
                resolve(response); 
            },
            error: function (err) {
                reject(err);
            },
        });
    });
    }

   return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: base_url + 'delete' + module,
             data: data,
            dataType: "json",
            success: function (response) {
                resolve(response); 
            },
            error: function (err) {
                reject(err);
            },
        });
    });
}
   
