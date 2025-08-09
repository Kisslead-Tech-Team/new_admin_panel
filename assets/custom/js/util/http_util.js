const ERROR_STATUS = JSON.stringify({
    code : 500,
    msg: "Something went wrong!"
});

// function GET({module}){
//     return $.ajax({
//         type: "GET",
//         url: base_url + 'get' + module,
//         dataType: "json",
//         success: function (response) {

//             if(response.code == 200){
//                 return JSON.parse(response.data)
//             }
//             return ERROR_STATUS;
//         },
//         error: function (err) {
//           console.log("Error :" + err);
//         },
//     });
// }

function GET({module}) {

    
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "GET",
            url: base_url + 'get' + module,
            dataType: "json",
            success: function (response) {
                if(response.code == 200){
                    resolve(JSON.parse(response.data));
                } else {
                    resolve(ERROR_STATUS);
                }
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
                    if(response.code == 200){
                        resolve(JSON.parse(response.data));
                    } else {
                        resolve(ERROR_STATUS);
                    }
                },
                error: function (err) {
                    reject(err);
                },
            });
        });


    }
    return $.ajax({
        type: "POST",
        url: base_url + 'insert' + module,
        data: data,
        dataType: "json",
        cache : false,
        processData: false,
        contentType: false,
        success: function (response) {
            if(response){
                return $.parseJSON(response)
            }
            return ERROR_STATUS;
        },
        error: function (err) {
        console.log(err);
        },
    });

}

function PUT({module, data}){
    return $.ajax({
        type: "POST",
        url: base_url + 'update' + module,
        data: data,
        dataType: "json",
        cache : false,
        processData: false,
        contentType: false,
        success: function (response) {
            if(response){

                
                return $.parseJSON(response)
            }
            return ERROR_STATUS;
        },
        error: function (err) {
            console.log(err);
        },
    });
}

function DELETE({module, data}){

    console.log(data);
    
    return $.ajax({
        type: "POST",
        url: base_url + 'delete' + module,
        data: data,
        dataType: "json",
        success: function (response) {
            if(response){
                return $.parseJSON(response)
            }
            return ERROR_STATUS;
        },
        error: function (err) {
            console.log(err);
        },
    });
}