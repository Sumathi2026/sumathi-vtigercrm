/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
 
Vtiger_Edit_Js("Guard_Edit_Js", {}, {
   
    /**
     * Function to register image change event for guard_img field
     * Similar to how Products module handles image uploads
     */
    registerImageChangeEvent: function() {
        var thisInstance = this;
        var container = this.getForm();
       
        // Register change event for Image file input (change field name to match your field)
        container.on('change', 'input[type="file"]', function(e) {
            var fileInput = jQuery(e.currentTarget);
            var file = fileInput[0].files[0];
           
            // Find the parent element where we'll show the filename
            var parentElement = fileInput.closest('.fieldValue, .controls');
            var filenameDisplay = parentElement.find('.uploaded-guard-filename');
           
            if (file) {
                // Create filename display if it doesn't exist
                if (filenameDisplay.length === 0) {
                    var filenameHtml = '<div class="uploaded-guard-filename" style="margin-top: 10px; padding: 5px 0; color: #666; font-size: 12px; clear: both;"></div>';
                    // Put it at the END of the parent container, not after the file input
                    parentElement.append(filenameHtml);
                    filenameDisplay = parentElement.find('.uploaded-guard-filename');
                }
               
                // Display the filename with remove link (similar to Products module)
                var removeLink = '<a href="javascript:void(0);" class="remove-guard-file" style="margin-left: 10px; color: #d9534f; text-decoration: none; cursor: pointer;">✕</a>';
                filenameDisplay.html('<span class="guard-filename">' + file.name + '</span>' + removeLink);
               
                // Register click event for remove link
                filenameDisplay.find('.remove-guard-file').off('click').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                   
                    // Clear the file input
                    fileInput.val('');
                    fileInput.trigger('change');
                   
                    // Clear the filename display
                    filenameDisplay.empty();
                });
               
            } else {
                // No file selected, clear the display
                if (filenameDisplay.length > 0) {
                    filenameDisplay.empty();
                }
            }
        });
    },
   
    /**
     * Function to register all basic events
     * This follows the same pattern as Products module
     */
    registerBasicEvents: function(container) {
        // Call parent class events first (important!)
        this._super(container);
       
        // Register our custom image upload event
        this.registerImageChangeEvent();
    }
});