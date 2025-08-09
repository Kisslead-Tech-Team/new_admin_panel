const module = 'user';
let userCount = 0;

$(document).ready(function(){
    setUserCount()
})

function setUserCount(){
    $.ajax({
        url: base_url + 'getusercount',
        type: 'GET',
        success: function(response){
            let res = JSON.parse(response)
            userCount = res.data
            $('.user-count').html(userCount)
        },
        error: function(error){
            console.log(error)
        }
    }) 
}