<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script>
    function editTab(tabId) {
        zcJS.ajax({
            url: 'ajax.php?act=ajaxAdminCittinsProductLayoutEditor&method=editTabInfo',
            data: {
                'tabId': tabId
            }
        }).done(function (resultArray) {
            const nameArray = resultArray.tabName;
            console.log(nameArray);
            for (let i = 0; i < nameArray.length; i++) {
                console.log(nameArray[i]);
            }
        });
    }
    function deleteTab(tabId) {

    }
    function deleteTabConfirm() {

    }
    function saveTab(tabId) {
        $('#editTabInfo').off('submit').on('submit', (function (e) {
            e.preventDefault();
            const formData = $('#editTabInfo').serializeArray();
            zcJS.ajax({
                url: 'ajax.php?act=ajaxAdminCittinsProductLayoutEditor&method=saveTabInfo',
                data: {
                    'tabId': tabId,
                    'formdata': formData
                }
            }).done(function (resultArray) {
                $('#TabEditModal').modal('hide');
            });
        }));
    }
    function tabInfo(tabId) {
        let content;
        zcJS.ajax({
            url: 'ajax.php?act=ajaxAdminCittinsProductLayoutEditor&method=getTabInfo',
            data: {
                'tabId': tabId
            }
        }).done(function (resultArray) {
            console.log(resultArray);
            if (resultArray['tabIsCore'] == '1') {
                $('#TabInfoBody').html('<p>This tab is core, and can not be removed.</p>');
            } else {
                content = '<p>This tab is used, in the following product types:</p>';
                content += '<ul>';
                content += resultArray.productTypes;
                content += '</ul>';
                $('.modal-body').html(content);
            }
        });
    }
    function updateTabSortOrder() {
        const SortOrder = document.getElementById('tabs');
        $('#tabs').off('submit').on('submit', (function (e) {
            e.preventDefault();
            console.log(SortOrder);
            zcJS.ajax({
                url: 'ajax.php?act=ajaxAdminCittinsProductLayoutEditor&method=updateTabSortOrder',
                data: new FormData(SortOrder)
            }).done(function (resultArray) {
                console.log(resultArray);
            });
        }));
    }

    if (typeof jQuery.fn.sortable !== 'undefined') {
        $(function () {
            $('#sortableTabRows').sortable({
                placeholder: 'ui-state-highlight',
                start: function (event, ui) {
                    const start_pos = ui.item.index();
                    ui.item.data('start_pos', start_pos);
                },
                update: function (event, ui) {
                    const index = ui.item.index();
                    const start_pos = ui.item.data('start_pos');

                    //update the html of the moved item to the current index
                    $('#sortableTabRows tr:nth-child(' + (index + 1) + ') .sortOrder').html(index);
                    $('#sortableTabRows tr:nth-child(' + (index + 1) + ') .sortOrderValue').val(index);

                    if (start_pos < index) {
                        //update the items before the re-ordered item
                        for (let i = index; i > 0; i--) {
                            $('#sortableTabRows tr:nth-child(' + i + ') .sortOrder').html(i - 1);
                            $('#sortableTabRows tr:nth-child(' + i + ') .sortOrderValue').val(i - 1);
                        }
                    } else {
                        //update the items after the re-ordered item
                        for (let i = index + 2; i <= $('#sortableTabRows tr .sortOrder').length; i++) {
                            $('#sortableTabRows tr:nth-child(' + i + ') .sortOrder').html(i - 1);
                            $('#sortableTabRows tr:nth-child(' + i + ') .sortOrderValue').val(i - 1);
                        }
                    }
                    updateTabSortOrder();
                },
                axis: 'y'
            });
            $('#sortableTabRows').disableSelection();
        });
    }
<?php if ($action == '') { ?>
        $(function () {
            $('#available_fields, [id^=tab-]').sortable({
                connectWith: '.connectedSortable',
                placeholder: 'ui-state-highlight'
            }).disableSelection();
            $('#tabs').sortable({
                placeholder: 'ui-state-highlight',
                items: 'div:not(.ui-state-disabled)'
            }).disableSelection();
            $('#tabs div').disableSelection();
            $('[id^=tab-]').sortable({
                receive: function (event, ui) {
                    const dropElemTxt = $(ui.item).find('span').text();
                    const dropElemId = $(ui.item).attr('id');
                    const dropTabId = $(ui.item).parent().attr('name');
                    let replacement = '';
                    replacement += '<div id="' + dropElemId + '" class="ui-state-default" role="button">\n';
                    replacement += '<input type="checkbox" name="tab[' + dropTabId + '][layout][' + dropElemId + '][show_in_frontend]" value="1" checked="checked">&nbsp;|&nbsp;\n';
                    replacement += '<span>' + dropElemTxt + '</span>\n';
                    replacement += '<input type="hidden" name="tab[' + dropTabId + '][layout][' + dropElemId + '][field_name]" value="' + dropElemId + '">\n';
                    replacement += '<input type="hidden" name="tab[' + dropTabId + '][layout][' + dropElemId + '][tab_id]" value="' + dropTabId + '">\n';
                    replacement += '</div>\n';
                    $(ui.item).replaceWith(replacement);
                }
            });
            $('#available_fields').sortable({
                receive: function (event, ui) {
                    const dropElemTxt = $(ui.item).find('span').text();
                    const dropElemId = $(ui.item).attr('id');
                    let replacement = '';
                    replacement += '<div id="' + dropElemId + '" class="ui-state-default" role="button">\n';
                    replacement += '<span>' + dropElemTxt + '</span>\n';
                    replacement += '</div>\n';
                    $(ui.item).replaceWith(replacement);
                }
            });
        });
<?php } else if ($action == 'add_field') { ?>
        let row = 3;
        $(function () {
            $('#select_values').hide();
            $('#field_type select').change(function () {
                if ($('#field_type option:selected').text() == 'string') {
                    $('#language_string').show();
                    $('#select_values').hide();
                    $('#field_length').show();
                    $('#select_values').find('input:text').val('');
                } else if ($('#field_type option:selected').text() == 'text') {
                    $('#language_string').show();
                    $('#select_values').hide();
                    $('#field_length').show();
                    $('#select_values').find('input:text').val('');
                } else if ($('#field_type option:selected').text() == 'integer') {
                    $('#select_values').hide();
                    $('#language_string').hide();
                    $('#field_length').show();
                    $('#select_values').find('input:text').val('');
                } else if ($('#field_type option:selected').text() == 'decimal') {
                    $('#select_values').hide();
                    $('#language_string').hide();
                    $('#field_length').show();
                    $('#select_values').find('input:text').val('');
                } else if ($('#field_type option:selected').text() == 'float') {
                    $('#select_values').hide();
                    $('#language_string').hide();
                    $('#field_length').show();
                    $('#select_values').find('input:text').val('');
                } else if ($('#field_type option:selected').text() == 'dropdown') {
                    $('#select_values').show();
                    $('#language_string').hide();
                    $('#field_length').hide();
                    $('#field_length').find('input:text').val('');
                } else if ($('#field_type option:selected').text() == 'radio') {
                    $('#select_values').show();
                    $('#language_string').hide();
                    $('#field_length').hide();
                    $('#field_length').find('input:text').val('');
                } else if ($('#field_type option:selected').text() == 'checkbox') {
                    $('#select_values').show();
                    $('#language_string').hide();
                    $('#field_length').hide();
                    $('#field_length').find('input:text').val('');
                } else if ($('#field_type option:selected').text() == 'datetime') {
                    $('#select_values').hide();
                    $('#language_string').hide();
                    $('#field_length').hide();
                    $('#select_values').find('input:text').val('');
                    $('#field_length').find('input:text').val('');
                }
            });
            $('#add_value').click(function () {
                let html;
                html = '<div class="col-sm-4">';
                html += '<input type="text" name="select_value_id[' + row + ']" class="form-control" />';
                html += '</div>';
                html += '<div class="col-sm-8">';
                html += '<input type="text" name="select_value_text[' + row + ']" class="form-control" />';
                html += '</div>';
                html += '<div class="col-sm-12"><hr></div>';
                $('#selection_fields').append(html);
                row++;
            });
        });
<?php } ?>
</script>
