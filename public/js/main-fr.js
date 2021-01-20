$(document).ready(function () {

    /* disable submit button if food input is empty*/
    $(".foods .food-input").on('keyup load', function () {

        if ($(this).val() == '') {
            $(".foods .food-sub").attr('disabled', 'disabled')
        } else {
            $(".foods .food-sub").removeAttr('disabled')
        }
    })

    if ($(".foods .food-input").val() == '') {
        $(".foods .food-sub").attr('disabled', 'disabled')
    } else {
        $(".foods .food-sub").removeAttr('disabled')
    }

    /* submit week date when calender changed*/
    $("#inputDate").on('change', function () {
        var section = $(this).parents('section').attr('class');
        switch (section) {
            case 'food-reserve':
                var url = '/panel/food-reserve/';
                break;
            case 'food-plan' :
                var url = '/panel/food-plan/';
                break;
            case 'food-report-weekly':
                var url = '/panel/food-report/weekly/';
                break;
            case 'food-report-daily':
                var url = '/panel/food-report/daily/';
                break;
        }
        var date = $(this).val();
        date = date.replace('-', '|').replaceAll('/', '-').replaceAll(/\s/g, "")
        var url = url + date;
        window.location.href = url;
    })


    /*sidebar category*/
    $(".sidebar-sticky .nav-item .top-link").on('click', function () {

        $(this).parent('.nav-item').children("ul").toggleClass('d-none')
    })

    $(".sidebar-sticky .nav-item  .bottom-link").each(function () {


        if (window.location.href.indexOf("%7C") != -1 || window.location.href.indexOf("|") != -1 || window.location.href.indexOf("daily") != -1  ) {
            
            var current_href = window.location.href.slice(0, window.location.href.lastIndexOf('/'));
            var link_href = $(this).attr('href').slice(0, $(this).attr('href').lastIndexOf('/')); 
            
            
           
        } else {
            current_href = window.location.href
            link_href = $(this).attr('href')
            
        }

        if (current_href == link_href) {
            
            
            $(this).parents('.nav-item').children("ul").removeClass('d-none')
        }
    })

    /* food plan select element*/
    $(".food-plan .submit-btn").on('click', function (e) {

        e.preventDefault()
        console.log($('.food-select'))

        $('.food-select').each(function () {
            var currentSelect = $(this)
            console.log(currentSelect)
            if (currentSelect.val().length == 0) {
                currentSelect.val([0]);

                /*  var o = new Option("option text", "0");
                  $(o).html("option text");
                  $(".food-select").append(o);*/
            }

        })
        $("#formPlan").submit()
    })


})

