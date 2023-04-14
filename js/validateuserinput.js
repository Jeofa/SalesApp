
$(function(){




$("#students").validate({
    rules:{
        regno:{
            required:true
            
        },
        name1:{
            required:true
            
        },
        email:{
            required:true,
            email:true
        },
        phone:{
            required:true,
            number:true
        },
        startyear:{
            required:true
        },
        school:{
            required:true
        },
        Department:{
            required:true
        },
        course:{
            required:true
        },
        semester:{
            required:true,
            number:true
        },
    }
});


});