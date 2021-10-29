/******/
(function (modules) { // webpackBootstrap
    /******/ 	// The module cache
    /******/
    var installedModules = {};
    /******/
    /******/ 	// The require function
    /******/
    function __webpack_require__(moduleId) {
        /******/
        /******/ 		// Check if module is in cache
        /******/
        if (installedModules[moduleId]) {
            /******/
            return installedModules[moduleId].exports;
            /******/
        }
        /******/ 		// Create a new module (and put it into the cache)
        /******/
        var module = installedModules[moduleId] = {
            /******/            i: moduleId,
            /******/            l: false,
            /******/            exports: {}
            /******/
        };
        /******/
        /******/ 		// Execute the module function
        /******/
        modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/
        /******/ 		// Flag the module as loaded
        /******/
        module.l = true;
        /******/
        /******/ 		// Return the exports of the module
        /******/
        return module.exports;
        /******/
    }

    /******/
    /******/
    /******/ 	// expose the modules object (__webpack_modules__)
    /******/
    __webpack_require__.m = modules;
    /******/
    /******/ 	// expose the module cache
    /******/
    __webpack_require__.c = installedModules;
    /******/
    /******/ 	// define getter function for harmony exports
    /******/
    __webpack_require__.d = function (exports, name, getter) {
        /******/
        if (!__webpack_require__.o(exports, name)) {
            /******/
            Object.defineProperty(exports, name, {enumerable: true, get: getter});
            /******/
        }
        /******/
    };
    /******/
    /******/ 	// define __esModule on exports
    /******/
    __webpack_require__.r = function (exports) {
        /******/
        if (typeof Symbol !== 'undefined' && Symbol.toStringTag) {
            /******/
            Object.defineProperty(exports, Symbol.toStringTag, {value: 'Module'});
            /******/
        }
        /******/
        Object.defineProperty(exports, '__esModule', {value: true});
        /******/
    };
    /******/
    /******/ 	// create a fake namespace object
    /******/ 	// mode & 1: value is a module id, require it
    /******/ 	// mode & 2: merge all properties of value into the ns
    /******/ 	// mode & 4: return value when already ns object
    /******/ 	// mode & 8|1: behave like require
    /******/
    __webpack_require__.t = function (value, mode) {
        /******/
        if (mode & 1) value = __webpack_require__(value);
        /******/
        if (mode & 8) return value;
        /******/
        if ((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
        /******/
        var ns = Object.create(null);
        /******/
        __webpack_require__.r(ns);
        /******/
        Object.defineProperty(ns, 'default', {enumerable: true, value: value});
        /******/
        if (mode & 2 && typeof value != 'string') for (var key in value) __webpack_require__.d(ns, key, function (key) {
            return value[key];
        }.bind(null, key));
        /******/
        return ns;
        /******/
    };
    /******/
    /******/ 	// getDefaultExport function for compatibility with non-harmony modules
    /******/
    __webpack_require__.n = function (module) {
        /******/
        var getter = module && module.__esModule ?
            /******/            function getDefault() {
                return module['default'];
            } :
            /******/            function getModuleExports() {
                return module;
            };
        /******/
        __webpack_require__.d(getter, 'a', getter);
        /******/
        return getter;
        /******/
    };
    /******/
    /******/ 	// Object.prototype.hasOwnProperty.call
    /******/
    __webpack_require__.o = function (object, property) {
        return Object.prototype.hasOwnProperty.call(object, property);
    };
    /******/
    /******/ 	// __webpack_public_path__
    /******/
    __webpack_require__.p = "/";
    /******/
    /******/
    /******/ 	// Load entry module and return exports
    /******/
    return __webpack_require__(__webpack_require__.s = 1);
    /******/
})
    /************************************************************************/
    /******/ ({

    /***/ "./resources/js/panel.js":
    /*!*******************************!*\
      !*** ./resources/js/panel.js ***!
      \*******************************/
    /*! no static exports found */
    /***/ (function (module, exports) {
        function insertUsersForm(area, select_name, label, option_label) {
            selected_id = $(area).attr('data-selected-id');
            selected_email = $(area).attr('data-selected-email');
            var i = 1;
            $('.form-receivers-part').empty();
            // var select_name = "user";
            label = '';
            if (label) {
                label = ' <label for="determiners" class="optional">' + label + '</label>';
            }
            option_inner = 'Empty';
            if (option_label) {
                option_inner = option_label
            }

            selected_option = ' <option selected disabled>' + option_inner + '</option>'
            if (selected_id) {
                selected_option = '  <option selected value="'+selected_id+'"  >' + selected_email + '</option>';
            }

            $(area).append(' <div class="">' +
                label +
                '<select id="" name="' + select_name + '" required="true" class="form-space select22 custom-select select2"  ></select>'
                + '</div>');
            $('select[name="' + select_name + '" ]').append(selected_option)
            i++;
            // $('.select22').prop('required', true);
            $('.select22').select2({
                ajax: {
                    url: "/panel/requisitions/staff",
                    dataType: 'json',
                    templateResult: function (item) {

                        return format(item, false);
                    },
                    matcher: matchStart,
                    /*  delay: 250,
                      placeholder: 'Search in users',
                      minimumInputLength: 1,*/


                    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                }
            });


        }

        $(document).ready(function () {


            if ($("#form-radio-hiring_type [name='hiring_type']:checked").val() == 0) {
                $('#form-input-replacement').removeClass('d-none')
            } else {
                $('#form-input-replacement').addClass('d-none')
            }

            $("#form-radio-hiring_type [name='hiring_type'] ").on('change', function () {
                var radio_val = $(this).val();
                if (radio_val == 0) {
                    $('#form-input-replacement').removeClass('d-none')
                } else {
                    $('#form-input-replacement').addClass('d-none')
                    $('#form-input-replacement').val('')

                }
            })

            $('select[name="department"]').on('change', function () {
                $('.form-receivers-part').empty();
                var department_val = $(this).val()
                $.ajax({
                    url: '/panel/requisitions/customizeReceiver',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'department': department_val,
                    },
                    success: function (response) {

                        $('select[name="level"]').empty()
                        $('select[name="level"]').append("  <option selected disabled> Empty</option>")
                        $.each(response.levels, function (key, val) {
                            var o = new Option(val, key.substr(1));
                            $('select[name="level"]').append(o)
                        })

                    }
                })

            });

            $('select[name="level"]').on('change', function () {
                var level_val = $(this).val()
                var department_val = $('select[name="department"]').val();

                $.ajax({
                    url: '/panel/requisitions/customizeReceiver',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'department': department_val,
                        'level': level_val
                    },
                    success: function (response) {

                        var i = 1;
                        $('.form-receivers-part').empty();
                        $.each(response.approver, function (key, value) {

                            var select_name = "determiners[" + key + "]";

                            $('.form-receivers-part').append(' <div class="col-md-4">' +
                                ' <label for="determiners" class="optional">' + value + '</label>' +
                                '<select id="" name="' + select_name + '" class="form-space approver form-control select2"></select>'
                                + '</div>');


                            //    var o = new Option(val, key);
                            $('select[name="' + select_name + '" ]').append('   <option selected disabled>Empty</option>')

                            /*      $.each(response.users, function (key, val) {
                                      var o = new Option(val, key);
                                      $('select[name="' + select_name + '" ]').append(o)
                                  })*/


                            i++;


                        });
                        //    $('.approver').select2() ;

                        $('.approver').select2({
                            ajax: {
                                url: "/panel/requisitions/staff",
                                dataType: 'json',
                                templateResult: function (item) {

                                    return format(item, false);
                                },
                                matcher: matchStart,
                                /*  delay: 250,
                                  placeholder: 'Search in users',
                                  minimumInputLength: 1,*/


                                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                            }
                        });


                    }
                })

            });

            if ($('select[name="department"]').val() != null) {


                $('.form-receivers-part').empty();
                var department_val = $('select[name="department"]').val()
                $.ajax({
                    url: '/panel/requisitions/customizeReceiver',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'department': department_val,
                    },
                    success: function (response) {

                        $('select[name="level"]').empty()
                        $('select[name="level"]').append("  <option selected disabled> Empty</option>")
                        $.each(response.levels, function (key, val) {
                            var o = new Option(val, key.substr(1));
                            $('select[name="level"]').append(o)
                        })

                    }
                })

            }
            ;

            if ($('select[name="level"]').val() != null) {
                var level_val = $('select[name="level"]').val();
                var department_val = $('select[name="department"]').val();

                $.ajax({
                    url: '/panel/requisitions/customizeReceiver',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'department': department_val,
                        'level': level_val
                    },
                    success: function (response) {

                        var i = 1;
                        $('.form-receivers-part').empty();
                        $.each(response.approver, function (key, value) {

                            var select_name = "determiners[" + key + "]";

                            $('.form-receivers-part').append(' <div class="col-md-4">' +
                                ' <label for="determiners" class="optional">' + value + '</label>' +
                                '<select id="" name="' + select_name + '" class="form-space approver form-control select2"></select>'
                                + '</div>');


                            //    var o = new Option(val, key);
                            $('select[name="' + select_name + '" ]').append('   <option selected disabled>Empty</option>')

                            /* $.each(response.users, function (key, val) {
                                 var o = new Option(val, key);
                                 $('select[name="' + select_name + '" ]').append(o)
                             }) ;*/


                            i++;


                        });
                        $('.approver').select2({
                            ajax: {
                                url: '/panel/requisitions/customizeReceiver/ldapUsers',
                                dataType: 'json',
                                delay: 250,
                                cache: true,
                                //matcher: matchStart,

                                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                            }
                        });


                    }
                })

            }
            ;


            insertUsersForm('.select-user', 'user_id', null, 'select user');

            /*$("#accept[name='accept']").on('change', function () {

             if( $(this).is(":checked"))
             {// alert('a') ;
                $('#submit-requisition').removeAttr('disabled')
             }else{
               //  alert('b') ;
                  $('#submit-requisition').attr('disabled', 'disabled')
             }

            })

            if ($("#accept[name='accept']").is(':checked') ) {
                 $('#submit-requisition').removeAttr('disabled')
            } else {
                  $('#submit-requisition').attr('disabled', 'disabled')
            }*/

        });

        /***/
    }),

    /***/ 1:
    /*!*************************************!*\
      !*** multi ./resources/js/panel.js ***!
      \*************************************/
    /*! no static exports found */
    /***/ (function (module, exports, __webpack_require__) {

        module.exports = __webpack_require__(/*! /Applications/MAMP/htdocs/HR-system/resources/js/panel.js */"./resources/js/panel.js");


        /***/
    })

    /******/
});
