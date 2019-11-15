var CSRF_TOKEN = $('input[name="_token"]').val();
$(document).ready(function() {
    // for sidebar menu entirely but not cover treeview
    $('ul.sidebar-menu a').filter(function() {
        return this.href == url;
    }).parent().addClass('active');

    $('ul.treeview-menu a').click(function() {
        sessionStorage.setItem('url', $(this).attr('href'));
    });
    // for treeview
    $('ul.treeview-menu a').filter(function() {
        var _url = sessionStorage.getItem('url');
        if (_url === getSegmentUrl()) {
            $('[href ="' + _url + '"]').parentsUntil().addClass('active');
        }

        return this.href == url
    }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');

    $('.additional').css('display', 'none');
    $('.budgetitem').css('display', 'none');
    $('.sup').css('display', 'none');

    //generate email or phone fields
    var max_fields = 12,
        i = 1;
    $(document).on('click', '.add', function(e) {
        e.preventDefault();
        $input = $(this).next();
        $counter = $(this).parent().children().length;
        if ($counter < max_fields) {
            $(this).parent().append("<i class='fa fa-minus pull-right remove text-red' style='cursor:pointer;'></i><br>");
            $(this).parent().append($input.clone().val(" "));
            $child = $input.children().children('.form-control');
            $child.each(function(e, index) {
                $name = $(this).prop('name');
                $(this).attr('name', $name.replace(/\[[0-9]\]+/, '[' + i + ']'))
            })
        }
        i++;
    });
    $(document).on('click', '.remove', function(e) {
        e.preventDefault();
        $(this).prev().remove();
        $(this).next().remove();
        $(this).remove();
    });
    //get city
    $(document).on("change", '.addr', function() {
        $city_code = $(this).val();

        $.ajax({
            url: '/getcity',
            type: 'POST',
            context: { number: $(this).attr('name').match(/\[[0-9]\]+/) },
            data: { _token: CSRF_TOKEN, cc_fips: $city_code },
            dataType: 'JSON',
            success: function(data) {
                $city = $('[name="city' + this.number[0] + '"]');

                if ($city.find('option').length > 1)
                    $city.find('option').remove();
                for (var i in data) {
                    if (data.hasOwnProperty(i)) {
                        var j_data = $.parseJSON(data[i]);
                        for (var j in j_data) {
                            if (j_data.hasOwnProperty(j)) {
                                $city.append(' <option class="text-capitalize" data-value="' + j + '" value="' + j_data[j] + '"></option>')
                            }
                        }
                        // $('input[name=country_code]').val(i)
                    }
                }
            },
            error: function(data) {
                console.error(data);
            }
        });
    });
    $(document).on("change", '#_city', function() {
        $list = $(this).attr('list');
        $options = $('#' + $list).find('option');
        $dataValues = [];
        $options.each(function(e, t) {
            $dataValues[($(this).data('value'))] = t.value;

        });
        console.log($dataValues)
        $pos = $.inArray($(this).val(), $dataValues);

        $('#city_id').val($pos);
    });
    //create editable rows
    $(document).on('click', '.edit', function() {

        $(this).parent().siblings('td').children().attr('disabled', false);
        $(this).nextAll().css('display', 'none');
        $(this).siblings('.save').css('display', 'inline-block');

        $(this).siblings('.save_app').css('display', 'inline-block');
        $(this).siblings('.save_prop').css('display', 'inline-block');

        $(this).siblings('.cancel').css('display', 'inline-block');
        $(this).css('display', 'none');
    });
    $(document).on('click', '.save', function() {
        $id = $(this).siblings('.id').val();
        $url = $(this).siblings('.url').val();
        $form = {};
        $form.id = $id;
        $siblings = $(this).parent().siblings().children();
        $siblings.each(function() {
            var name = $(this).attr('name');
            if (name !== undefined)
                $form[name] = $(this).val();
        });
        $form = JSON.stringify($form);
        console.log($form);
        $.ajax({
            url: $url,
            type: 'POST',
            context: { element: $(this) },
            data: { _token: CSRF_TOKEN, form: $form },
            dataType: 'JSON',
            success: function(data) {
                this.element.parent().siblings('td').children().attr('disabled', true);
                this.element.siblings('').css('display', 'inline-block');
                this.element.siblings('.cancel').css('display', 'none');
                this.element.css('display', 'none');
                location.reload();
            },
            error: function(data) {
                console.log(data)
            }
        });
    });
    $(document).on('click', '.cancel', function() {
        $(this).parent().siblings('td').children().attr('disabled', true);
        $(this).siblings('').css('display', 'inline-block');
        $(this).siblings('.save').css('display', 'none');

        $(this).siblings('.save_app').css('display', 'none');
        $(this).siblings('.save_prop').css('display', 'none');

        $(this).css('display', 'none');
    });
    /*For applicant accounts */
    $(document).on('click', '.save_app', function() {
        $id = $(this).siblings('.id').val();
        $form = {};
        $form.id = $id;
        $siblings = $(this).parent().siblings().children();
        $siblings.each(function() {
            var name = $(this).attr('name');
            $form[name] = $(this).val();
        });
        $form = JSON.stringify($form);
        $.ajax({
            url: '/updateAccount',
            type: 'POST',
            context: { element: $(this) },
            data: { _token: CSRF_TOKEN, form: $form },
            dataType: 'JSON',
            success: function(data) {
                this.element.parent().siblings('td').children().attr('disabled', true);
                this.element.siblings('').css('display', 'inline-block');
                this.element.siblings('.cancel').css('display', 'none');
                this.element.css('display', 'none');
            },
            error: function(data) {
                console.log(data)
            }
        });
    });

    /*Change State of proposals*/
    $('.save_prop').click(function() {
        $id = $(this).siblings('.id').val();
        $form = {};
        $form.id = $id;
        $siblings = $(this).parent().siblings().children();
        $siblings.each(function() {
            var name = $(this).attr('name');
            $form[name] = $(this).val();
        });
        $form = JSON.stringify($form);
        $.ajax({
            url: '/updateProposalState',
            type: 'POST',
            context: { element: $(this) },
            data: { _token: CSRF_TOKEN, form: $form },
            dataType: 'JSON',
            success: function(data) {
                this.element.parent().siblings('td').children().attr('disabled', true);
                this.element.siblings('').css('display', 'inline-block');
                this.element.siblings('.cancel').css('display', 'none');
                this.element.css('display', 'none');
            },
            error: function(data) {
                console.log(data)
            }
        });
    });

    /*Change State of proposals*/
    $('.sign-in').click(function() {
        $id = $(this).parent().next('.id').val();

        $url = '/' + $(this).parent().next('.id').attr('name');
        $.ajax({
            url: $url,
            type: 'GET',
            context: { element: $(this) },
            data: { _token: CSRF_TOKEN, id: $id },
            dataType: 'JSON',
            success: function(data) {
                if (!isNaN(data)) {
                    window.open('http://' + window.location.hostname + $url);
                }
            },
            error: function(data) {
                console.log(data)
            }
        });
    });
    $('#off, #on').click(function(e) {
        e.preventDefault();
        $url = '/lock';
        $look = $(this).attr('id');
        $.ajax({
            url: $url,
            type: 'POST',
            context: { element: $(this) },
            data: { _token: CSRF_TOKEN, lock: $look },
            success: function(data) {
                this.element.siblings().css('display', 'inline-block');
                this.element.css('display', 'none');
            },
            error: function(data) {
                console.log(data)
            }
        });
    });
    //get subcategories
    $(document).on("change", '.cat', function() {
        $category = $(this).val();

        $.ajax({
            url: '/getsub',
            type: 'POST',
            context: { element: $(this) },
            data: { _token: CSRF_TOKEN, id: $category },
            dataType: 'JSON',
            success: function(data) {
                $sub = this.element.parent().next().children('select');

                if ($sub.find('option').length > 1)
                    $sub.find('option').remove();
                // var j_data = $.parseJSON(data);

                for (var i in data) {
                    if (data.hasOwnProperty(i)) {
                        $sub.append(' <option class="text-capitalize" value="' + i + '">' + data[i] + '</option>')
                    }
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    $(document).on("change", '#competition', function() {
        $category = $(this).val();

        $.ajax({
            url: '/getSTypeCount',
            type: 'POST',
            context: { element: $(this) },
            data: { _token: CSRF_TOKEN, id: $category },
            dataType: 'JSON',
            success: function(data) {
                if (data.count >= 7) {
                    $('#add_score').attr('disabled', true)
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
    //get ranging rule by competition

    $(document).on("change", '#rule_com', function() {
        $id = $(this).val();
        $.ajax({
            url: '/getRR',
            type: 'POST',
            context: { element: $(this) },
            data: { _token: CSRF_TOKEN, id: $id },
            dataType: 'JSON',
            success: function(data) {
                var d = JSON.parse(data.rs);
                $('#rule').find("option:gt(0)").remove();
                if (d.length > 0) {
                    for (var i in d) {
                        if (d.hasOwnProperty(i)) {
                            $('#rule').append("<option value='" + d[i].id + "'>" + d[i].sql + "</option>");
                        }
                    }
                }
            },
            error: function(data) {
                console.error(data);
            }
        });
    });

    // $(document).on("change", '#budget', function () {
    //     $ids = $(this).val();
    //
    //     $.ajax({
    //         url: '/createSQL',
    //         type: 'POST',
    //         context: {element: $(this)},
    //         data: {_token: CSRF_TOKEN, ids: $ids},
    //         dataType: 'JSON',
    //         success: function (data) {
    //             console.log(data);
    //         },
    //         error: function (data) {
    //             console.error(data);
    //         }
    //     });
    // });
    //get proposal form competitions
    $(document).on("change", '.comp_prop', function() {
        $comp_prop = $(this).val();
        $('#category0').find('option').remove();
        $('#category1').find('option').remove();
        $('#budget_categories').find('input').remove();
        $('#budget_categories_description').find('input').remove();
        $('#amount').find('input').remove();
        $('#score_type').find('tr').remove();
        $('#additional_charge_name').find('input').remove();
        $('#additional_charge').find('input').remove();
        $('#additional_persentage_name').find('input').remove();
        $('#additional_persentage').find('input').remove();
        $('.additional').css('display', 'none');
        $('.budgetitem').css('display', 'none');
        $('.sup').hide();
        $('.chooseperson').removeAttr('disabled');
        $.ajax({
            url: '/gccbi',
            type: 'POST',
            context: { element: $(this) },
            data: { _token: CSRF_TOKEN, id: $comp_prop },
            dataType: 'JSON',
            success: function(data) {
                for (var i in data) {
                    if (data.hasOwnProperty(i)) {
                        if (i === 'cats') {
                            $step = 0;
                            for (var j in data[i]) {
                                if (data[i].hasOwnProperty(j)) {
                                    $('#category0').append("<option>Select Category</option><option value='" + j + "'>" + data[i][j].parent + "</option>");
                                    $('#category1').append("<option>Select Secondary Category</option><option value='" + j + "'>" + data[i][j].parent + "</option>");
                                    if (data[i][j].parent.length > 0)
                                        $('#sub_category' + $step).val(data[i][j].sub);
                                    $step++;
                                }
                            }
                        }
                        if (i === 'bc') {

                            $('.budgetitem').css('display', 'block');
                            $step = 0;
                            for (var bj in data[i]) {
                                if (data[i].hasOwnProperty(bj)) {
                                    var k = parseInt(bj) + 1;
                                    //$('#budget_categories').append("<option value='" + k + "'>" + data[i][bj].name + "</option>");
                                    $('#budget_categories').append("<input class='form-control' type ='text' name='budget_item_categories[]' value='" + data[i][bj].name + "' />");
                                    $('#budget_categories').append("<input class='form-control' type ='hidden' name='budget_item_categories_hidden[]' value='" + data[i][bj].id + "' />");
                                    $('#budget_categories_description').append("<input type='text' class='form-control' name='budget_categories_description[]' value='' />");
                                    $('#amount').append("<input type='number' class='form-control' name='amount[]' min='" + data[i][bj].min + "' max='" + data[i][bj].max + "' placeholder='" + data[i][bj].min + " - " + data[i][bj].max + "'/>");
                                    $step++;
                                }
                            }
                        }
                        //Score type//
                        if (i === 'st') {
                            $step = 0;
                            for (var k in data[i]) {
                                if (data[i].hasOwnProperty(k)) {
                                    $('#score_type').append("<tr><td>" + data[i][k].name + "</td><td>" + data[i][k].description + "</td></tr>");
                                    $step++;
                                }
                            }
                        }

                        //Recomendition//
                        if (i === 'recommendition') {
                            if (data[i] == 1) {
                                $('.recommendation').val(data[i]);
                                $('.sup').css('display', 'block');
                            } else {
                                var matches = 0;
                                $(".tt").each(function(i, val) {
                                    if ($(this).val() == 'support') {
                                        $('#choose_person_name' + matches).remove();
                                    }
                                    matches++;
                                });
                            }
                        }

                        //Allow Foreign//
                        if (i === 'allowforeign') {
                            if (data[i] != 1) {
                                $('#comp_container').append('<i class="fas fa-question-circle text-red all"> Only registered in Armenia can take part in this competition </i> <input type="hidden" name="allowforegin" value="' + data[i] + '" class="all" id="allowforeign">');
                                $('.domesticorforeign').val(data[i]);
                                var matches = 0;
                                $(".al").each(function(i, val) {
                                    if ($(this).val() != 'Armenia') {
                                        $('#choose_person_name' + matches).remove();
                                    }
                                    matches++;
                                });
                            } else {
                                $('.domesticorforeign').val(data[i]);
                                $('#comp_container').find('.all').remove();
                            }

                        }
                        //Additional//
                        if (i === 'additional') {
                            $('.additional').css('display', 'block');
                            $('#additional_charge_name').append("<input class='form-control' type ='text' name='additional_charge_name' disabled value='" + data[i].additional_charge_name + "' />");
                            $('#additional_charge').append("<input class='form-control' type ='text' name='additional_charge' disabled value='" + data[i].additional_charge + "' />");
                            $('#additional_persentage_name').append("<input class='form-control' type ='text' name='additional_percentage_name' disabled value='" + data[i].additional_percentage_name + "' />");
                            $('#additional_persentage').append("<input class='form-control' type ='text' name='additional_percentage' disabled value='" + data[i].additional_percentage + "' />");


                        }

                    }
                }

            },
            error: function(data) {
                console.error(data);
            }
        });
    });


    $(document).on('click', '.check_all', function() {
        var checkedStatus = this.checked;
        $('#example tr').find('.checkbox').each(function() {
            $(this).prop('checked', checkedStatus);
        });
    });

    $(document).on('click', '.check_all,.checkbox', function() {
        $('.btn_add').find('button').attr('disabled', false);
    });

    $(document).on("click", ".approve", function() {
        $id = $(this).prev().val();
        $approve = $(this).val();

        $.ajax({
            url: '/approve',
            type: 'POST',
            context: { element: $(this) },
            data: {
                _token: CSRF_TOKEN,
                id: $id,
                approve: $approve
            },
            dataType: 'JSON',
            success: function(data) {
                console.log(data);
                // if (data.success())
                //     location.reload();
            },
            error: function(data) {
                console.log(data);
            }
        });

    });

    $(document).on("click", ".delete", function() {
        $conf = confirm("Are you sure?");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($conf) {
            $title = $(this).attr('data-title');
            $_id = $(this).prev('[name=_id]').val();
            switch ($title) {
                case 'competition':
                    $.ajax({
                        url: '/getProposal',
                        type: 'POST',
                        context: { element: $(this) },
                        data: { _token: CSRF_TOKEN, id: $_id },
                        dataType: 'JSON',
                        success: function(data) {
                            if (data.success) {
                                $conf2 = alert("Deletion is not possible. ");
                            } else {
                                this.element.parent().submit();
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                    break;
                case 'applicant':
                    $.ajax({
                        url: '/getProposalByApplicant',
                        type: 'POST',
                        context: { element: $(this) },
                        data: { _token: CSRF_TOKEN, id: $_id, type: $title },
                        dataType: 'JSON',
                        success: function(data) {
                            if (data.success) {
                                $conf2 = confirm("Account has created proposals. Are you sure?");
                                if ($conf)
                                    this.element.parent().submit();
                            } else {
                                this.element.parent().submit();
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                    break;
                case "referee":
                    $.ajax({
                        url: '/getProposalByReferee',
                        type: 'POST',
                        context: { element: $(this) },
                        data: { _token: CSRF_TOKEN, id: $_id, type: $title },
                        dataType: 'JSON',
                        success: function(data) {
                            if (data.success) {
                                $conf2 = confirm("Referee has created report. Are you sure?");
                                if ($conf2)
                                    this.element.parent().submit();
                            } else {
                                this.element.parent().submit();
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                    break;
                case "budget":
                    $.ajax({
                        url: '/getBudgetByCategory',
                        type: 'POST',
                        context: { element: $(this) },
                        data: { _token: CSRF_TOKEN, id: $_id, type: $title },
                        dataType: 'JSON',
                        success: function(data) {
                            if (data.success) {
                                $conf2 = confirm("Budget has created by Category. Are you sure?");
                                if ($conf2)
                                    this.element.parent().submit();
                            } else {
                                this.element.parent().submit();
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                    break;
                case "score":
                case "rule":
                case "institution":
                    $title = $title.charAt(0).toUpperCase() + $title.slice(1);
                    $conf2 = confirm($title + " type has competition. Are you sure?");
                    if ($conf2)
                        $(this).parent().submit();

                    break;
                case "category":
                    $.ajax({
                        url: '/getProposalByCategory',
                        type: 'POST',
                        context: { element: $(this) },
                        data: { _token: CSRF_TOKEN, id: $_id, type: $title },
                        dataType: 'JSON',
                        success: function(data) {
                            console.log(data.success);
                            if (data.success) {
                                alert("You cannot remove a category because it is used in competitions!");
                                // if ($conf2)
                                //     this.element.parent().submit();
                            } else {
                                this.element.parent().submit();
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                    break;
                case "report":
                    $action = $(this).parent().attr('action');
                    $url = $action + "/" + $(this).prev().val();
                    $(this).parent().attr('action', $url);
                    $title = $title.charAt(0).toUpperCase() + $title.slice(1);
                    $conf2 = confirm($title + " type has competition. Are you sure?");
                    if ($conf2)
                        $(this).parent().submit();

                    break;
                default:
                    $(this).parent().submit();
            }

        }

    });
    //get subcategories
    $(document).on("change", '#create_budget_categories', function() {
        $category = $(this).val();

        $.ajax({
            url: '/getbudgetcategoriesamount',
            type: 'POST',
            context: { element: $(this) },
            data: { _token: CSRF_TOKEN, id: $category },
            dataType: 'JSON',
            success: function(data) {
                $sub = this.element.parent().next().children('select');

                if ($sub.find('option').length > 1)
                    $sub.find('option').remove();
                // var j_data = $.parseJSON(data);

                for (var i in data) {
                    if (data.hasOwnProperty(i)) {
                        $sub.append(' <option class="text-capitalize" value="' + i + '">' + data[i] + '</option>')
                    }
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    $(document).on("click", '#deleteProposal,' +
        ' #deleteCats, #duplicateCats,' +
        ' #deleteBudgets, #deleteScores, #deleteRule, #deleteReport',
        function() {
            var checked = $('.checkbox:checked');
            $url = $(this).attr('id');
            if (checked.length > 0) {
                $pattern = $url.match(/delete/);
                if (!$pattern)
                    $pattern = 'duplicate';
                if (confirm('Are you want ' + $pattern + " " + checked.length + ' categories?')) {
                    var checkedIDss = [];
                    $(checked).each(function() {
                        checkedIDss.push($(this).val());
                    });
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '/' + $url,
                        type: 'POST',
                        context: { element: $pattern },
                        data: {
                            token: CSRF_TOKEN,
                            id: checkedIDss
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            console.log(data);
                            if (data.success === -1)
                                alert('do not allow deletion of ' + $pattern +
                                    ' that are already assigned to any competition ');
                            else
                                location.reload();
                        },
                        error: function(data) {
                            console.log('msg' + data);
                        }
                    });
                }
            }
        });

    //get proposalsbycompetition id for viewer
    $(document).on("change", '.comp_list', function() {
        $compid = $(this).val();

        $.ajax({
            url: '/getProposalByCompByID',
            type: 'POST',
            context: { element: $(this) },
            data: { _token: CSRF_TOKEN, id: $compid },
            //dataType: 'JSON',
            success: function(data) {
                $(".prop").html(data);
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    $(document).on("change", "#state", function() {
        if ($('#state').val() == 'domestic') {
            $('#nationality option[value="Armenia"]').attr('selected', 'selected');
            // $('#nationality').attr('readonly', 'readonly');
        } else if ($('#state').val() == 'foreign') {
            $('#nationality').val();
            $('#nationality').removeAttr('disabled');
        }
    });
    /*VIEWER AJAX REQUEST*/
    //get proposalsbycompetition id for viewer
    $(document).on("change", '.statistic', function() {
        statisticval = $(this).val();
        var comp_y = ['number of proposals', 'average overall score', 'number of awards', 'total amount of funds given', 'average age of PI', 'number of female PIs'];
        var pi_y = ['number of submitted proposal', 'number of awards received'];
        $('.statistic_x').find('option').remove();
        $('.statistic_y').find('option').remove();
        $.ajax({
            url: '/gclfs',
            type: 'POST',
            context: { element: $(this) },
            data: { _token: CSRF_TOKEN, value: statisticval },
            dataType: 'JSON',
            success: function(data) {
                console.log(data);
                for (var i in data) {
                    if (i == 'comp') {
                        for (var j in data[i]) {
                            if (data[i].hasOwnProperty(j)) {
                                $('.statistic_x').append(' <option class="text-capitalize" value="' + data[i][j].id + ' " id = "' + data[i][j].title + '">' + data[i][j].title + '</option>');
                                $('.statistic_y').find('option').remove();
                                $('.statistic_y').append('<option class="text-capitalize"> Choose Y axis Value </option>');
                                for (var c in comp_y) {
                                    $('.statistic_y').append(' <option class="text-capitalize" value="' + comp_y[c].replace(/ /g, '') + '">' + comp_y[c] + '</option>')
                                }
                            }
                        }
                    } else if (i == 'pi') {
                        for (var j in data[i]) {
                            if (data[i].hasOwnProperty(j)) {
                                $('.statistic_x').append(' <option class="text-capitalize" value="' + data[i][j].id + '">' + data[i][j].first_name + " " + data[i][j].last_name + '</option>');
                                $('.statistic_y').find('option').remove();
                                $('.statistic_y').append('<option class="text-capitalize"> Choose Y axis Value </option>');
                                for (var p in pi_y) {
                                    $('.statistic_y').append(' <option class="text-capitalize" value="' + pi_y[p].replace(/ /g, '') + '">' + pi_y[p] + '</option>')
                                }
                            }
                        }
                    }
                }


            },
            error: function(data) {
                console.log(data);
            }
        });
    });

});

var url = window.location;


function send_email(checkedIDss) {

    var selected = $('[name=message]').val();
    if (selected)
        $.ajax({
            // headers: {
            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            // },
            url: '/sendEmail',
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                t_id: selected,
                ids: JSON.stringify(checkedIDss)
            },
            dataType: 'JSON',
            success: function(data) {
                console.log("d" + data);
                location.reload();
            },
            error: function(data) {
                console.log(data);
            }
        });
    else
        alert('Please Choose Proposal!')

}

function change_state(checkedIDss) {

    var selected = $('[name=change_proposal_state]').val();
    if (selected)
        $.ajax({
            // headers: {
            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            // },
            url: '/changeState',
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                state: selected,
                ids: JSON.stringify(checkedIDss)
            },
            dataType: 'JSON',
            success: function(data) {
                console.log("d" + data);
                location.reload();
            },
            error: function(data) {
                console.log(data);
            }
        });
    else
        alert('Please Choose Proposal!')

}

function getSegmentUrl(_url) {
    var segment = window.location.pathname.split('/');
    var newURL = window.location.protocol + "//" + window.location.host + "/";
    return newURL + segment[1] + "/" + segment[2];
}