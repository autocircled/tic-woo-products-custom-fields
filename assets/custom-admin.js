jQuery(document).ready(function ($) {
    // Overview repeater add    
    $("#add_overview_tab_content").on("click", "#add-overview-repeater", function () {
        let parentEL = $(this).closest('.eazyproo-repeater');
        let overviews = EAZYPROO_OBJ.overview || [];
        let overviewOptionsHtml = '';
        for(const [key, value] of Object.entries(overviews)){
            if(value !== ''){
                overviewOptionsHtml += `<option value="${key}">${value}</option>`;
            }
        }
        $(parentEL).find("tbody").append(`
            <tr class='options_group dynamic'>
                <td>
                    <select class="overview_title_select">
                        <option>Select</option>
                        ${overviewOptionsHtml}
                    </select>
                </td>
                <td>
                    <input type="text" class="overview_content_input full"  placeholder="Example: Adobe">
                </td>
                <td>
                    <button type="button" class="ez_remover_overview button button-danger">Remove</button>
                </td>
            </tr>
        `);

        $(parentEL).find('tbody tr.dynamic').each(function(index){
            $(this)
                .find('.overview_title_select')
                .attr('name', `eazyproo_overview[${index}][title]`);
            $(this)
                .find('.overview_content_input')
                .attr('name', `eazyproo_overview[${index}][content]`);
        });
    });

    // Overview remover
    $('#add_overview_tab_content').on('click', '.ez_remover_overview', function () {
        let parentEL = $(this).closest('.options_group');
        $(parentEL).find('input.overview_content_input').val('');

    });

    // System requirement repeater add
    // $("#system_requirement_tab_content").on("click", '#add-system-repeater', function (e) {
    //     // console.log(e.target.attributes('data-action'));
    //     const action = $(this).attr('data-action');
    //     let parentEL = $(this).closest('.eazyproo-repeater');
    //     let sys_reqs = EAZYPROO_OBJ.reqs || [];
    //     let reqsOptionsHtml = '';
    //     for(const [key, value] of Object.entries(sys_reqs)){
    //         if(value !== ''){
    //             reqsOptionsHtml += `<option value="${key}">${value}</option>`;
    //         }
    //     }

    //     $(parentEL).find("tbody").append(`
    //         <tr class='options_group'>
    //             <td>
    //                 <select class="title_select">
    //                     <option>Select</option>
    //                     ${reqsOptionsHtml}
    //                 </select>
    //             </td>
    //             <td>
    //                 <input type="text" class="content_input full"  placeholder="Example: 2GB of RAM">
    //             </td>
    //             <td>
    //                 <button type="button" class="ez_remover_reqs button button-danger">Remove</button>
    //             </td>
    //         </tr>
    //     `);

    //     $(parentEL).find('tbody tr').each(function(index){
    //         $(this)
    //             .find('.title_select')
    //             .attr('name', `system_${action}_info[${index}][title]`);
    //         $(this)
    //             .find('.content_input')
    //             .attr('name', `system_${action}_info[${index}][info]`);
    //     });
    // });

    // System requirement remover
    $('#system_requirement_tab_content').on('click', '.ez_clear_reqs', function () {
        let parentEL = $(this).closest('tr');
        $(parentEL).find('input[type=text]').each(function(index, el){
            $(el).val('');
        });

    });
    $('#system_requirement_tab_content').on('click', '.ez_remover_reqs', function () {
        const action = $(this).attr('data-action');
        let parentEL = $(this).closest('.inner-wrapper');
        $(this).closest('.options_group').remove();
        $(parentEL).find('tbody tr').each(function(index){
            $(this)
                .find('.title_select')
                .attr('name', `system_${action}_info[${index}][title]`);
            $(this)
                .find('.content_input')
                .attr('name', `system_${action}_info[${index}][info]`);
        });

    });

    // Custom meta field repeater add
    $('#eazyproo_meta_contents_product_data').on('click', '#add-meta-repeater-field', function () {
        let parentEL = $(this).closest('.eazyproo-repeater');
        let meta_fields = EAZYPROO_OBJ.meta_fields || [];
        let optionHtml = '';
        for(const [key, value] of Object.entries(meta_fields)){
            optionHtml += `<option value="${key}">${value.title}</option>`;
            
        }

        let hooks_data = EAZYPROO_OBJ.meta_hooks;
        let hook_options = '';
        if(hooks_data){
            for (const [key, value] of Object.entries(hooks_data)) {
                hook_options += `<option value="${key}">${value}</option>`;
            }
        }

        $(parentEL).find("tbody").append(`
            <tr class="options_group">
                <td>
                    <select class="custom_meta_changer">
                        <option>Select</option>
                        ${optionHtml}
                    </select>
                </td>
                <td>
                    <input class="full content-input" type="text" placeholder="Adobe" />
                </td>
                <td>
                    <div class="img-wrapper">
                        <div class="image_preview"></div>
                    </div>
                </td>
                <td>
                    <select id="custom_field_hook_list" class="meta_hook">
                        <option value="">Select Location</option>
                        ${hook_options}
                    </select>
                </td>
                <td>
                    <button type="button" class="ez_remover_meta button btn-outline-danger">Remove</button>
                </td>
            </tr>
        `);

        $(parentEL).find('tbody tr').each(function(index){
            $(this)
                .find('.custom_meta_changer')
                .attr('name', `custom_repeater_field[${index}][key]`);
            $(this)
                .find('.content-input')
                .attr('name', `custom_repeater_field[${index}][value]`);
            $(this)
                .find('.meta_hook')
                .attr('name', `custom_repeater_field[${index}][hook_name]`);
        });

    });

    // Custom meta field repeater remover
    $('#eazyproo_meta_contents_product_data').on('click', '.ez_remover_meta', function () {
        let parentEL = $(this).closest('tr');
        $(parentEL).find('input[type=text], select').each(function(index, el){
            $(el).val('');
        });

    });

    // Custom meta field select changer
    $("#ezproo-meta tbody").on("change", "select.custom_meta_changer", function () {

        const self = $(this);

        const selectedValue = $(self).val();
        const metaFields = EAZYPROO_OBJ.meta_fields || [];
        const selectedMetaObj = metaFields[selectedValue] || {};
        if (selectedMetaObj) {
            const trEl = $(self).closest('.options_group');

            // Title
            const titleInput = $(trEl).find('.title-hidden');
            $(titleInput).val(selectedMetaObj.title);

            // Image 
            const imageWrapper = $(trEl).find('.img-wrapper');
            if (undefined !== selectedMetaObj.image ) {
                $(imageWrapper).children('.image_preview').html(`<img src="${selectedMetaObj.image}" width="50" height="50" />`);
            }else{
                $(imageWrapper).children('.image_preview').html('');
            }
        }
    });

    // include apps repeater add
    $("#include_apps_meta_panel").on("change", "#ez_all_apps", function () {
        let parentEL = $(this).closest('.eazyproo-repeater');
        const selectedValue = $(this).val();
        const appList = EAZYPROO_OBJ.apps || [];
        const selectedObj = appList[selectedValue] || {};
        let appHtml = '';
        
        if (selectedObj) {
            let app_wrapper = $(parentEL).find('#selected_apps_wrapper');
            if (app_wrapper.length === 0) {
                console.error("App wrapper not found!");
                return;
            }
            // const list = document.getElementById('selected_apps_wrapper');

            // Check if the item is already in the list
            const exists = Array.from(app_wrapper[0].children).some(
                // check if data-attr has selectedValue
                (li) => $(li).attr('data-title') === selectedObj.title
            );

            if (!exists) {
                
                // Append the selected item to the list
                let imgPreview = '';
                if ( selectedObj.image !== '' ){
                    imgPreview = `<img src="${selectedObj.image}" width="150" height="150" />`;
                }
                let titleHtml = '';
                if ( selectedObj.title !== '' ){
                    titleHtml = `<span class="title">${selectedObj.title}</span>`;
                }
                appHtml = `
                    <li data-title="${selectedObj.title}" class="options_group">
                        <div class="item-inner">
                            <input type="hidden" class="title_hidden" name="include_apps_info[]" value="${selectedValue}">
                            ${imgPreview}
                            ${titleHtml}                            
                            <span class="remove_item_button">X</span>
                        </div>
                    </li>
                `;
                
                // list.append(reqsOptionsHtml);
                $(app_wrapper).append(appHtml)               
            }

            // Reset dropdown selection
            $(this).prop("selectedIndex", 0);
        }


    }); 
    
    // System requirement remover
    $('#include_apps_meta_panel').on('click', '.remove_item_button', function () {
        $(this).closest('.options_group').remove();
    });

    // notice repeater add
    $("#notice_requirement_tab_content").on("click", "#add-notice-system-repeater", function () {
        let parentEL = $(this).closest('#notice_system_wrapper');
        $(parentEL).find("tbody").append(`
            <tr class="options_group">
                <td><input type="text" class="notice-input full" placeholder="Type your notice here. e.g: This is a digital downloadable software not a license key."></td>
                <td><button type="button" class="notice_system_repeater_remove_btn button">Remove</button></td>
            </tr>
        `);
        $(parentEL).find('tbody tr').each(function(index){
            $(this)
                .find('.notice-input')
                .attr('name', `notice_system_requirement_info[${index}][title]`)
        });
    });

    // notice repeater remover
    $(document).on('click', '.notice_system_repeater_remove_btn', function () {
        let parentEL = $(this).closest('#notice_system_wrapper');
        $(this).closest('.options_group').remove();
        $(parentEL).find('tbody tr').each(function(index){
            $(this)
                .find('.notice-input')
                .attr('name', `notice_system_requirement_info[${index}][title]`);
        });
    });
    
    // faq repeater add
    $("#faq_tab_content").on("click", "#add-new-faq", function () {
        let parentEL = $(this).closest('.eazyproo-repeater');
        $(parentEL).find("tbody").append(`
            <tr class="options_group">
                <td><textarea class="faq-title-input full" placeholder="Write FAQ title here"></textarea></td>
                <td><textarea class="faq-answer-input full" placeholder="Write FAQ answer here"></textarea></td>
                <td><button type="button" class="faq_remover_btn button">Remove</button></td>
            </tr>
        `);
        $(parentEL).find('tbody tr').each(function(index){
            $(this)
                .find('.faq-title-input')
                .attr('name', `product_faqs[${index}][title]`);
            $(this)
                .find('.faq-answer-input')
                .attr('name', `product_faqs[${index}][answer]`);
        });
    });

    // faq repeater remover
    $('#faq_tab_content').on('click', '.faq_remover_btn', function () {
        let parentEL = $(this).closest('.eazyproo-repeater');
        $(this).closest('.options_group').remove();
        $(parentEL).find('tbody tr').each(function(index){
            $(this)
                .find('.faq-title-input')
                .attr('name', `product_faqs[${index}][title]`);
            $(this)
                .find('.faq-answer-input')
                .attr('name', `product_faqs[${index}][answer]`);
        });
    });
    
    // Uploading files
    $(document).on('click', '.upload_image_button', function (event) {
        event.preventDefault();
        var button = $(this);
        var input = button.prev();
        var imagePreview = button.nextAll('.image_preview');

        var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select an image to upload',
            button: {
                text: 'Use this image',
            },
            multiple: false
        });

        file_frame.on('select', function () {
            var attachment = file_frame.state().get('selection').first().toJSON();
            // console.log(attachment.url);
            input.val(attachment.id);
            // imagePreview.html('<img src="' + attachment.url + '" style="max-width:100%;"/>');
            imagePreview.html('<img width="150" height="113" src="' + attachment.url + '" class="attachment-thumbnail size-thumbnail" alt="">');
        });

        // console.log(file_frame);

        file_frame.open();
    });

    $(document).on('click', '.remove_include_apps_button', function () {
        $(this).closest('.include-apps-field-wrapper').remove();

    });
    // select 2 section here

    function formatCountry(country) {
        if (!country.id) { return country.text; }
        var $country = $(
            '<span class="flag-icon flag-icon-' + country.id.toLowerCase() + ' flag-icon-squared"></span>' +
            '<span class="flag-text">' + country.text + "</span>"
        );
        return $country;
    };

    $('#ez_all_countries').select2({
        placeholder: "Select a country",
        templateResult: formatCountry,
        templateSelection: formatCountry,
        data: EAZYPROO_OBJ.countries,
        escapeMarkup: function (markup) { return markup; }
    });

    $('#ez_all_tags').select2({
        placeholder: "Select Tags",
    });

    /*tooltip section*/
    $('.tooltip-container').hover(function () {
        var tooltip = $(this).find('.tooltip-text');
        tooltip.stop().fadeIn(300); // Fade in with a duration
    }, function () {
        var tooltip = $(this).find('.tooltip-text');
        tooltip.stop().fadeOut(300); // Fade out with a duration
    });
    /*tooltip section end*/
});
