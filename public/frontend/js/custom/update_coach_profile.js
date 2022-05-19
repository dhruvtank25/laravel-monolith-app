var UpdateCoachModule = (function() {
    var myDropzone;
    var $validator;

    var home_autocomplete, work_autocomplete;
    function initAutoComplete() {
        var input = document.getElementById("autocomplete_loc");
        var diff_input = document.getElementById("diff_autocomplete_loc");
        var options = {
            //types: ['geocode'], //this should work !
            //region:'EU',
            //componentRestrictions: {country: "AU"}
        };
        home_autocomplete = new google.maps.places.Autocomplete(input, options);
        home_autocomplete.addListener("place_changed", fillInAddress);

        // Different work address
        work_autocomplete = new google.maps.places.Autocomplete(
            diff_input,
            options
        );
        work_autocomplete.addListener("place_changed", fillInDiffAddress);

        // Prevent on select enter
        google.maps.event.addDomListener(input, "keydown", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });
        google.maps.event.addDomListener(diff_input, "keydown", function(
            event
        ) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });
    }

    function fillInDiffAddress(event) {
        fillAddress("work");
    }

    function fillInAddress(event) {
        fillAddress("home");
    }

    function fillAddress(type) {
        // Get the place details from the autocomplete object.
        var place =
            type == "work"
                ? work_autocomplete.getPlace()
                : home_autocomplete.getPlace();
        console.log(place);
        var address_obj = {
            lat: "",
            lng: "",
            street_no: "",
            route: "",
            city: "",
            city1: "",
            state: "",
            post_code: "",
            country: "",
            country_code: "",
        };

        // Address co-ordinates
        address_obj.lat = place.geometry.location.lat();
        address_obj.lng = place.geometry.location.lng();

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            switch (addressType) {
                case "administrative_area_level_1":
                    address_obj.state =
                        place.address_components[i]["short_name"];
                    break;
                case "administrative_area_level_2":
                    address_obj.city1 =
                        place.address_components[i]["long_name"];
                    break;
                case "postal_code":
                    address_obj.post_code =
                        place.address_components[i]["short_name"];
                    break;
                case "locality":
                    address_obj.place =
                        place.address_components[i]["long_name"];
                    break;
                case "street_number":
                    address_obj.street_no =
                        place.address_components[i]["short_name"];
                    break;
                case "route":
                    address_obj.route =
                        place.address_components[i]["long_name"];
                    break;
                case "country":
                    address_obj.country =
                        place.address_components[i]["long_name"];
                    address_obj.country_code =
                        place.address_components[i]["short_name"];
                    break;
                default:
                    console.log(addressType);
                    break;
            }
        }

        var place = "";
        if (address_obj.place != "") place = address_obj.place;
        else if (address_obj.city1 != "") place = address_obj.city1;

        if (type == "work") {
            document.getElementById("work_latitude").value = address_obj.lat;
            document.getElementById("work_longitude").value = address_obj.lng;
            document.getElementById("work_post_code").value =
                address_obj.post_code;
            document.getElementById("work_place").value = place;
            document.getElementById("work_country").value = address_obj.country;
            document.getElementById("work_country_code").value =
                address_obj.country_code;
            document.getElementById("work_street").value =
                address_obj.route + " " + address_obj.street_no;
        } else {
            document.getElementById("latitude").value = address_obj.lat;
            document.getElementById("longitude").value = address_obj.lng;
            document.getElementById("post_code").value = address_obj.post_code;
            document.getElementById("place").value = place;
            document.getElementById("country").value = address_obj.country;
            document.getElementById("country_code").value =
                address_obj.country_code;
            document.getElementById("street").value =
                address_obj.route + " " + address_obj.street_no;
        }
    }

    // Tooltip
    $('[data-toggle="tooltip"]').tooltip({
        position: { at: "right+5 top-18" },
        tooltipClass: "customtooltip",
    });

    //select2
    $(".multi-select").select2({
        placeholder: "Hier auswählen oder eingeben",
        tags: true,
    });

    // Set existing tags
    $(".tagselect_val").each(function(index, el) {
        var this_val = JSON.parse($(this).val());
        var $select = $(this)
            .closest(".lang_multi_select")
            .find(".multi-select");
        var option_html = "";
        for (let i = 0; i < this_val.length; i++) {
            if (this_val[i] == "") continue;
            console.log(this_val[i]);
            /*if($select.find('option[value="'+this_val[i]+'"]').length>0)
                $select.find('option[value="'+this_val[i]+'"]').attr('selected', 'selected');
            else
                option_html += '<option selected>'+this_val[i]+'</option>';*/
            if ($select.find('option[value="' + this_val[i] + '"]').length > 0)
                $select.find('option[value="' + this_val[i] + '"]').remove();
            option_html += "<option selected>" + this_val[i] + "</option>";
        }
        if (option_html != "") $select.append(option_html).change();
    });

    $("select[name='language[]'],select[name='priorities[]']")
        .parent()
        .find(".select2-search__field")
        .css("width", "260px");

    // jQuery Validator

    // Custom IBAN validation
    jQuery.validator.addMethod(
        "iban",
        function(value, element) {
            return (
                this.optional(element) ||
                /^[a-zA-Z]{2}\d{2}\s*(\w{4}\s*){2,7}\w{1,4}\s*$/.test(value)
            );
        },
        "Invalid IBAN"
    );

    // Custom BIC validation
    jQuery.validator.addMethod(
        "bic",
        function(value, element) {
            return (
                this.optional(element) ||
                /^[a-zA-Z]{6}\w{2}(\w{3})?$/.test(value)
            );
        },
        "Invalid BIC"
    );

    function initializeValidation(frmdId) {
        $validator = $("#" + frmdId).validate({
            //ignore: [],
            //ignore: ':hidden:not(.do-not-ignore)',
            ignore: function(index, el) {
                var $el = $(el);
                if (
                    $el.hasClass("do-not-ignore") &&
                    $el.closest(".tab-pane").hasClass("active")
                ) {
                    return false;
                }
                // Default behavior
                return $el.is(":hidden");
            },
            errorClass: "is-invalid",
            rules: {
                first_name: "required",
                last_name: "required",
                email: {
                    required: true,
                    remote: {
                        url: publicUrl + "/user/unique-email-check/",
                    },
                },
                email_confirmation: {
                    equalTo: "#email",
                },
                password: {
                    required: function(element) {
                        if (
                            frmdId == "coachRegFrm" ||
                            $("#old_password").val() != ""
                        )
                            return true;
                        return false;
                    },
                    minlength: 8,
                    pwcheck: true,
                },
                password_confirmation: {
                    equalTo: "#password",
                },
                old_password: {
                    required: function(element) {
                        return $("#password").val() != "";
                    },
                },
                phone_number: "required",
                birth_date: {
                    required: true,
                    birthdate: true,
                },
                nationality: "required",
                id_doc: "required",
                // Address validation
                latitude: "required",
                longitude: "required",
                street: "required",
                house_no: "required",
                post_code: "required",
                place: "required",
                country: "required",
                country_code: "required",
                work_latitude: {
                    required: function(element) {
                        return $("#different_work").prop("checked");
                    },
                },
                work_longitude: {
                    required: function(element) {
                        return $("#different_work").prop("checked");
                    },
                },
                work_street: {
                    required: function(element) {
                        return $("#different_work").prop("checked");
                    },
                },
                work_house_no: {
                    required: function(element) {
                        return $("#different_work").prop("checked");
                    },
                },
                work_post_code: {
                    required: function(element) {
                        return $("#different_work").prop("checked");
                    },
                },
                work_place: {
                    required: function(element) {
                        return $("#different_work").prop("checked");
                    },
                },
                work_country: {
                    required: function(element) {
                        return $("#different_work").prop("checked");
                    },
                },
                work_country_code: {
                    required: function(element) {
                        return $("#different_work").prop("checked");
                    },
                },
                // Address validation End
                // Company Validation Start
                coach_company: "required",
                person_type: "required",
                tax_number: {
                    required: function(element) {
                        return $("#ust_id").val() == "";
                        /*if($("input[name=person_type]:checked").val()=='soletrader')
                                        return $("#ust_id_soletrader").val()=='';
                                    else
                                        return $("#ust_id_business").val()=='';*/
                    },
                },
                ust_id: {
                    required: function(element) {
                        return $("#tax_number").val() == "";
                        /*if($("input[name=person_type]:checked").val()=='soletrader')
                                    else
                                        return $("#tax_number_business").val()=='';*/
                    },
                },
                company_type: {
                    required: function(element) {
                        return (
                            $("input[name=person_type]:checked").val() ==
                            "business"
                        );
                    },
                },
                company_number: {
                    required: function(element) {
                        return (
                            $("input[name=person_type]:checked").val() ==
                            "business"
                        );
                    },
                    alphanumeric: true,
                },
                //ustid_doc: "required",
                ustid_doc: {
                    required: function(element) {
                        return (
                            $("input[name=is_commercial]").is(":checked") ||
                            $("input[name=person_type]:checked").val() ==
                                "business"
                        );
                    },
                },
                commercial_doc: {
                    required: function(element) {
                        return (
                            $("input[name=person_type]:checked").val() ==
                            "business"
                        );
                    },
                },
                impressum: "required",
                // Company Validation End
                price_per_hour: {
                    required: true,
                    number: true,
                    min: 20,
                },
                "categories[]": {
                    required: true,
                    minlength: 1,
                },
                "language[]": "required",
                "priorities[]": "required",
                description: "required",
                agree_copyright: "required",
                owner_name: "required",
                iban: {
                    required: true,
                    iban: true,
                },
                bic: {
                    required: true,
                    bic: true,
                },
                agree_ustid: "required",
                terms_condition: "required",
                privacy_policy: "required",
                agree_credentials: "required",
                existing_password: "required",
                delete_profile: "required",
            },
            messages: {
                email: {
                    remote: "Email is already taken",
                },
                email_confirmation: {
                    equalTo: "Email confirmation does not match",
                },
                password: {
                    pwcheck:
                        "Password must include at least one capital letter and at least one number",
                },
                password_confirmation: {
                    equalTo: "Password confirmation does not match",
                },
                ustid_doc: {
                    required:
                        "You need provide valid business registration proff",
                },
                id_doc: {
                    required: "Please provide valid Id card document",
                },
                latitude: {
                    required: "Please select a valid Address",
                },
                longitude: {
                    required: "Please select a valid Address",
                },
                work_latitude: {
                    required: "Please select a valid Work Address",
                },
                work_longitude: {
                    required: "Please select a valid Work Address",
                },
                tax_number: {
                    required: "Either tax number or UST Id is required",
                },
            },
            errorPlacement: function(error, element) {
                // Blank so that no error message is shown.
                // All Error will be displayed via toastr
                /*var element_name = element.attr('name');
                           if(element_name == 'language[]') {
                               element.parent().find('.select2').append(error);
                           }*/
            },
            highlight: function(element) {
                var element_name = $(element).attr("name");
                if (
                    element_name == "post_code" ||
                    element_name == "work_post_code" ||
                    element_name == "country" ||
                    element_name == "work_country" ||
                    element_name == "country_code" ||
                    element_name == "work_country_code"
                ) {
                    var $add_element = $(element)
                        .closest(".row")
                        .find(".addressautocomplete input[type=text]");
                    $add_element.addClass("border border-danger");
                    $add_element
                        .parent()
                        .find("label")
                        .addClass("text-danger");
                } else {
                    if ($(element).attr("type") == "checkbox") {
                        $('input[name="' + element_name + '"]')
                            .parent()
                            .addClass("text-danger");
                    } else if (
                        $(element).hasClass("basic-select2") ||
                        $(element).hasClass("multi-select")
                    ) {
                        $(element)
                            .parent()
                            .find(".select2")
                            .addClass("border border-danger");
                    } else {
                        $(element).addClass("border border-danger");
                    }
                    $(element)
                        .parent()
                        .find("label")
                        .addClass("text-danger");
                }
            },
            unhighlight: function(element) {
                var element_name = $(element).attr("name");
                if ($(element).attr("type") == "checkbox") {
                    $('input[name="' + element_name + '"]')
                        .parent()
                        .removeClass("text-danger");
                } else if (
                    $(element).hasClass("basic-select2") ||
                    $(element).hasClass("multi-select")
                ) {
                    $(element)
                        .parent()
                        .find(".select2")
                        .removeClass("border border-danger");
                } else {
                    $(element).removeClass("border border-danger");
                }
                $(element)
                    .parent()
                    .find("label")
                    .removeClass("text-danger");
            },
        });
    }

    $(document).on("change", "#ust_id, #tax_number", function(event) {
        event.preventDefault();
        $(this)
            .closest("form")
            .validate()
            .element("#ust_id");
        $(this)
            .closest("form")
            .validate()
            .element("#tax_number");
    });

    function checkValidation(frmId, callback) {
        var valid = $("#" + frmId).valid();
        if (!valid) {
            $validator.focusInvalid();
            var error_html = "";
            var required_err = false;
            $.each($validator.errorMap, function(key, data) {
                if (
                    data.includes("field is required") ||
                    data.includes("Feld ist ein Pflichtfeld.")
                ) {
                    if (required_err == false) {
                        error_html =
                            "Bitte füllen Sie alle erforderlichen Felder aus, um fortzufahren. <br>" +
                            error_html;
                        required_err = true;
                    }
                } else {
                    //error_html+=key+':'+data+'<br/>';
                    error_html += data + "<br/>";
                }
            });
            toastr.error(error_html);
            callback(false);
        } else {
            callback(true);
        }
    }

    // To validate select2 fields
    $(document).on("select2:close", "select", function(event) {
        event.preventDefault();
        /* Act on the event */
        $(this).valid();
    });

    // Jquery Ui datepicker
    $("#birth_date").datepicker({
        dateFormat: "yy-mm-dd",
        yearRange: "-100:-18",
        maxDate: "-18Y",
        changeYear: true,
        changeMonth: true,
        showMonthAfterYear: true,
    });

    $("input[name='person_type']").click(function(event) {
        if ($(this).val() == "soletrader") {
            $(".soletrader_container").removeClass("hidden");
            $(".business_container").addClass("hidden");
            $(".reg_proof_btn").text("Gewerbeanmeldung hochladen");

            if ($("#is_commercial").is(":checked")) $("#ust-div").show();
            else $("#ust-div").hide();
        } else {
            $(".soletrader_container").addClass("hidden");
            $(".business_container").removeClass("hidden");
            $(".reg_proof_btn").text("Handelsregisterauszug hochladen");

            // Show ust doc options
            $("#ust-div").show();

            // Shareholder
            if ($("#shareloder_div .nomines_section").length < 1) addNominee();
        }
    });

    $("#is_commercial").click(function(event) {
        if ($(this).is(":checked")) {
            $("#ust-div").show();
        } else {
            $("input[name=small_business]").prop('checked', true);
            $("#ust-div").hide();
        }
    });

    // Different Work address checkbox
    $("#different_work").click(function() {
        if ($(this).prop("checked") == true) {
            $(".different_work_div").css("display", "flex");
        } else if ($(this).prop("checked") == false) {
            $(".different_work_div").css("display", "none");
        }
    });

    // Online/offline coaching checkbox
    $("#online_coaching, #offline_coaching").change(function(event) {
        // One of online or offline should always be checked
        if (!$(this).is(":checked")) {
            if ($(this).attr("id") == "online_coaching")
                $("#offline_coaching").prop("checked", true);
            else $("#online_coaching").prop("checked", true);
        }
        // Change Different address for offline coaching appropriately
        if (!$("#offline_coaching").is(":checked")) {
            $("#different_work")
                .attr("disabled", "true")
                .prop("checked", false);
            $(".different_work_div").css("display", "none");
        } else {
            $("#different_work").removeAttr("disabled");
        }
    });

    // Shareholder/ Nominee
    $(".addnominesbtn").click(function(event) {
        event.preventDefault();
        addNominee();
    });

    function addNominee() {
        if ($("#shareloder_div .nomines_section").length >= 4) {
            toastr.error("Maximum of 4 Shareholder is allowed!");
        } else {
            $("#shareloder_div").append($("#shareholder_skeleton").html());
            setShareholderAttr();
        }
    }

    $("#shareloder_div").on("click", ".delete_nomine", function(event) {
        event.preventDefault();
        if ($("#shareloder_div .nomines_section").length > 1) {
            $(this)
                .closest(".nomines_section")
                .remove();
            setShareholderAttr();
        } else {
            toastr.error("Atleast 1 Shareholder is required!");
        }
    });

    function setShareholderAttr() {
        // Set unique name for each fields
        $("#shareloder_div .nomines_section").each(function(index, el) {
            var $this = $(this);
            $this.find(".nominee_no").text(index + 1);
            $this
                .find(".shareholder_firstname")
                .attr("name", "sh_first_name[" + index + "]");
            $this
                .find(".shareholder_lastname")
                .attr("name", "sh_last_name[" + index + "]");
            $this
                .find(".shareholder_address")
                .attr("name", "sh_street[" + index + "]");
            $this
                .find(".shareholder_postcode")
                .attr("name", "sh_post_code[" + index + "]");
            $this
                .find(".shareholder_place")
                .attr("name", "sh_place[" + index + "]");
            $this
                .find(".shareholder_country")
                .attr("name", "sh_country[" + index + "]");
            $this
                .find(".shareholder_nationality")
                .attr("name", "sh_nationality[" + index + "]");
            $this
                .find(".shareholder_birthdate")
                .attr("name", "sh_birth_date[" + index + "]");
            $this
                .find(".shareholder_birthplace")
                .attr("name", "sh_birth_place[" + index + "]");
            $this
                .find(".shareholder_birthcountry")
                .attr("name", "sh_birth_country[" + index + "]");
        });

        // Add birthdate datepicker
        $("#shareloder_div .nomines_section .shareholder_birthdate").datepicker(
            {
                dateFormat: "yy-mm-dd",
                yearRange: "-100:-18",
                maxDate: "-18Y",
                changeYear: true,
                changeMonth: true,
                showMonthAfterYear: true,
            }
        );

        // Add jQuery validator to validate fields
        $(
            "#shareloder_div .nomines_section input, #shareloder_div .nomines_section select"
        ).each(function() {
            var message_obj = {};
            $(this).rules("add", { required: true, messages: message_obj });
        });
    }

    // Commerical person
    $("#is_commercial_radio").change(function(event) {
        if ($(this).is(":checked")) $(".commercial_div").show();
        else $(".commercial_div").hide();
    });

    // Company Selection
    $(".add_company").click(function(event) {
        addCompany();
    });

    function addCompany() {
        $("#company_div").append($("#company_skeleton").html());
        setCompanyAttr();
    }

    function setCompanyAttr() {
        if ($("#company_div .company_content").length < 1) {
            addCompany();
            return false;
        }
        // Set unique name for each fields
        $("#company_div .company_content").each(function(index, el) {
            var $this = $(this);
            $this.find(".company_id").attr("name", "company_id[" + index + "]");
            $this
                .find(".company_name")
                .attr("name", "company_name[" + index + "]");
            $this
                .find(".company_month")
                .attr("name", "join_month[" + index + "]");
            $this
                .find(".company_year")
                .attr("name", "join_year[" + index + "]");
            $this
                .find(".company_designation")
                .attr("name", "designation[" + index + "]");
            $this
                .find(".company_doc")
                .attr("name", "company_doc[" + index + "]");
        });

        // Set select2 on all select elements
        $("#company_div .company_content select").select2({
            placeholder: "Hier auswählen oder eingeben",
        });

        // Add jQuery validator to validate fields
        $(
            "#company_div .company_content input, #company_div .company_content select"
        ).each(function() {
            var message_obj = {};
            if ($(this).hasClass("company_doc")) {
                message_obj = { required: "Company document required" };
            }
            $(this).rules("add", { required: true, messages: message_obj });
        });
    }

    $("#company_div").on("click", ".delete_company", function(event) {
        event.preventDefault();
        if ($("#company_div .company_content").length > 1) {
            $(this)
                .closest(".company_content")
                .remove();
            setCompanyAttr();
        } else {
            toastr.error("Atleast 1 company is required!");
        }
    });

    $("#company_div").on("change", ".company_id", function(event) {
        event.preventDefault();
        var company_name = $(this)
            .find("option:selected")
            .text()
            .toLowerCase();
        if (company_name == "other" || company_name == "sonstige")
            $(this)
                .closest(".row")
                .find(".company_name_div")
                .show();
        else
            $(this)
                .closest(".row")
                .find(".company_name_div")
                .hide();
    });

    // Company Selection End

    // File Upload (Dropzone.js)
    var $upload_elem,
        $hidden_field,
        file_upload_type,
        prev_file_names,
        prev_file_urls;
    $("#dropzone").dropzone({
        addRemoveLinks: true,
        previewsContainer: ".dropzone-previews",
        //maxFiles: 1,
        maxfilesexceeded: function(file) {
            this.removeAllFiles();
            this.addFile(file);
        },
        success: function(file, data) {
            console.log(data);
            console.log(file.previewElement);
            if (data.success == "true") {
                console.log(myDropzone.options.maxFiles);
                file.upload.filename = data.file_name;
                if (myDropzone.options.maxFiles == 1) {
                    var file_names = data.file_name;
                    var file_urls = data.file_url;
                } else {
                    var file_names = $hidden_field.val();
                    file_names +=
                        file_names == ""
                            ? data.file_name
                            : "," + data.file_name;

                    var file_urls = $hidden_field.data("url");
                    file_urls +=
                        file_urls == "" ? data.file_url : "," + data.file_url;
                }
                $hidden_field.val(file_names);
                $hidden_field.data("url", file_urls);
                if (file_names == "") $(".dz-message").addClass("w-100");
                else $(".dz-message").removeClass("w-100");
            } else {
                this.removeAllFiles();
                //file.previewElement.remove();
                toastr.error(data.message);
            }
        },
        error: function(file, errorMessage, XMLHttpRequest) {
            console.log(errorMessage);
            this.removeFile(file);
            if (XMLHttpRequest != undefined && XMLHttpRequest.status == 422) {
                ajaxValidationError(JSON.parse(XMLHttpRequest.response));
            } else if (
                XMLHttpRequest != undefined &&
                XMLHttpRequest.status == 401
            ) {
                toastr.error(errorMessage.message);
            } else if (errorMessage == "You can not upload any more files.") {
                return true;
            } /*else if(errorMessage.includes('File is too big')) {
                toastr.error(errorMessage);
            } else if(errorMessage.includes("You can't upload files of this type.")) {
                toastr.error("You can't upload files of this type.");
            }*/ else if (
                errorMessage != ""
            ) {
                toastr.error(errorMessage);
            } else {
                toastr.error("Something unexpected happended!");
            }
            //this.removeAllFiles();
        },
        removedfile: function(file) {
            var file_name = file.upload.filename;
            console.log(file_name);
            //$hidden_field.val('');
            file.previewElement.remove();

            var file_names = $hidden_field.val();
            console.log(file_names);
            var file_urls = $hidden_field.data("url");
            if (file_names != "" && myDropzone.options.maxFiles > 1) {
                var file_arr = file_names.split(",");
                var url_arr = file_urls.split(",");
                var new_file_names = (new_urls = "");
                for (let i = 0, length1 = file_arr.length; i < length1; i++) {
                    if (file_arr[i] != file_name) {
                        new_file_names +=
                            new_file_names != ""
                                ? "," + file_arr[i]
                                : file_arr[i];
                        new_urls +=
                            new_urls != "" ? "," + url_arr[i] : url_arr[i];
                    } else console.log("removing file " + file_name);
                }
                $hidden_field.val(new_file_names);
                $hidden_field.data("url", new_urls);
                console.log($hidden_field.val());

                if (new_file_names == "") $(".dz-message").addClass("w-100");
                else $(".dz-message").removeClass("w-100");
            } else {
                $hidden_field.val("");
                $hidden_field.data("url", "");
                $(".dz-message").addClass("w-100");
            }

            // Show preview container
            $(".dz-message").attr("style", "display:inline-block !important");
            /*$.ajax({
                url: baseUrl+'/delete-documents',
                type: 'POST',
                data: {
                        _token: $('input[name="_token"]').val(), 
                        file_name: file_name,
                        type: file_upload_type
                    },
            })
            .done(function(data) {
                console.log("success");
                $hidden_field.val('');
                file.previewElement.remove();
                //this.removeFile(file);
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });*/
        },
        init: function() {
            myDropzone = this;
            this.on("addedfile", function(file) {
                $("#dropzone .dropzone-previews").prepend(
                    $(file.previewElement)
                );

                // Check for Add file message
                if (myDropzone.files.length < myDropzone.options.maxFiles)
                    $(".dz-message").attr(
                        "style",
                        "display:inline-block !important"
                    );
                else $(".dz-message").attr("style", "display:none !important");
            });
        },
    });

    $(document).on("click", ".modal_file_upload", function(event) {
        event.preventDefault();
        $upload_elem = $(this);
        file_upload_type = $upload_elem.data("type");
        $hidden_field = $($upload_elem.parent().find("input[type=hidden]"));

        Dropzone.prototype.defaultOptions.dictDefaultMessage = "Dfdf";
        myDropzone.options.maxFilesize = 8;
        myDropzone.options.acceptedFiles = null;
        var file_type = "image";
        switch (file_upload_type) {
            case "avatar":
                $("#doc_title").html("Lade deine Profilbild hoch");
                $("#doc_type").html("Upload von JPEG und PNG möglich");
                $(".dz-message").html(
                    '<p>Bild hier hineinziehen</p><p class="uploadbtn">Auswählen</p>'
                );
                myDropzone.options.maxFiles = 1;
                myDropzone.options.acceptedFiles = "image/png, image/jpeg";
                break;
            case "video":
                $("#doc_title").html("Lade deine Vorstellungvideo hoch");
                $("#doc_type").html("Upload von mp4, mov möglich");
                $(".dz-message").html(
                    '<p>Video hier hineinziehen</p><p class="uploadbtn">Auswählen</p>'
                );
                myDropzone.options.maxFiles = 1;
                //myDropzone.options.maxFilesize = 60;
                myDropzone.options.acceptedFiles = ".mp4, .mov";
                break;
            case "banner":
                $("#doc_title").html("Lade deine Titelbild hoch");
                $("#doc_type").html("Upload von JPEG und PNG möglich");
                $(".dz-message").html(
                    '<p>Bild hier hineinziehen</p><p class="uploadbtn">Auswählen</p>'
                );
                myDropzone.options.maxFiles = 1;
                myDropzone.options.acceptedFiles = "image/png, image/jpeg";
                break;
            case "ustid_doc":
                if (
                    $("input[name='person_type']:checked").val() == "soletrader"
                )
                    $("#doc_title").html("Lade deine Gewerbeanmeldung hoch.");
                else
                    $("#doc_title").html(
                        "Lade deinen Handelsregisterauszug hoch."
                    );
                $("#doc_type").html("Upload von PDF, JPEG und PNG möglich");
                $(".dz-message").html(
                    '<p>Dokument hier hineinziehen</p><p class="uploadbtn">Auswählen</p>'
                );
                myDropzone.options.maxFiles = 5;
                myDropzone.options.acceptedFiles =
                    "image/png, image/jpeg, application/pdf";
                break;
            case "id_doc":
                $("#doc_title").html("Lade deine Ausweisdokument hoch.");
                $("#doc_type").html("Upload von PDF, JPEG und PNG möglich");
                $(".dz-message").html(
                    '<p>Dokument hier hineinziehen</p><p class="uploadbtn">Auswählen</p>'
                );
                myDropzone.options.maxFiles = 5;
                myDropzone.options.acceptedFiles =
                    "image/png, image/jpeg, application/pdf";
                break;
            case "commercial_doc":
                $("#doc_title").html("Lade deinen Gesellschaftsvertrag hoch.");
                $("#doc_type").html("Upload von PDF, JPEG und PNG möglich");
                $(".dz-message").html(
                    '<p>Dokument hier hineinziehen</p><p class="uploadbtn">Auswählen</p>'
                );
                myDropzone.options.maxFiles = 5;
                myDropzone.options.acceptedFiles =
                    "image/png, image/jpeg, application/pdf";
                break;
            case "company_doc":
            default:
                $("#doc_title").html(
                    "Lade deine Zertifikat oder <span>eine offizielle Bestätigung hoch.</span>"
                );
                $("#doc_type").html("Upload von PDF, JPEG und PNG möglich");
                $(".dz-message").html(
                    '<p>Dokument hier hineinziehen</p><p class="uploadbtn">Auswählen</p>'
                );
                myDropzone.options.maxFiles = 5;
                myDropzone.options.acceptedFiles =
                    "image/png, image/jpeg, application/pdf";
                break;
        }
        $(myDropzone.hiddenFileInput).attr(
            "accept",
            myDropzone.options.acceptedFiles
        );
        $("input[name='type']").val(file_upload_type);
        $("#certificate_modal").modal("show");

        var filename = $hidden_field.val();
        var file_url = $hidden_field.data("url");

        prev_file_names = filename;
        prev_file_urls = file_url;
        // Remove all existing files in container
        myDropzone.removeAllFiles();
        // Set already uploaded file preview
        if (filename != "") {
            $(".dz-message").removeClass("w-100");
            var file_arr = filename.split(",");
            var url_arr = file_url.split(",");
            for (let i = 0, length1 = file_arr.length; i < length1; i++) {
                var url = url_arr[i];
                var file_name = file_arr[i];
                var mockFile = {
                    name: file_name,
                    size: 12345,
                    status: Dropzone.ADDED,
                    accepted: true,
                    upload: { filename: file_name },
                };
                // Call the default addedfile event handler
                myDropzone.emit("addedfile", mockFile);
                // Show the thumbnail of the file:
                if (
                    file_name.indexOf("png") != -1 ||
                    file_name.indexOf("jpeg") != -1 ||
                    file_name.indexOf("jpg") != -1
                )
                    myDropzone.emit("thumbnail", mockFile, url);
                // Make sure that there is no progress bar, etc...
                myDropzone.emit("complete", mockFile);
                myDropzone.files.push(mockFile);
            }
            // Add file back to place
            $hidden_field.val(filename);
            $hidden_field.data("url", file_url);
            console.log(file_arr.length, myDropzone.options.maxFiles);
            if (file_arr.length < myDropzone.options.maxFiles)
                $(".dz-message").attr(
                    "style",
                    "display:inline-block !important"
                );
            else $(".dz-message").attr("style", "display:none !important");
        } else {
            $(".dz-message").addClass("w-100");
        }
    });

    $("#certificate_modal #cancel_upload_btn").click(function(event) {
        $hidden_field.val(prev_file_names);
        $hidden_field.data("url", prev_file_urls);
        $("#certificate_modal").modal("hide");
    });

    $("#certificate_modal #save_file_btn").click(function(event) {
        prev_file_names = "";
        prev_file_urls = "";
        $("#certificate_modal").modal("hide");
    });

    return {
        initValidation: function(frmId) {
            initializeValidation(frmId);
        },
        initAutoComplete: function() {
            initAutoComplete();
        },
        addNewCompany: function() {
            addCompany();
        },
        resetCompanyAttr: function() {
            setCompanyAttr();
        },
        resetShareholderAttr: function() {
            setShareholderAttr();
        },
        checkValidation: function(frmId, callback) {
            checkValidation(frmId, callback);
        },
    };
})();
