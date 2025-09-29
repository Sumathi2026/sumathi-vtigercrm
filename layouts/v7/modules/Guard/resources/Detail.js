Vtiger_Detail_Js("Guard_Detail_Js", {
    approveOrReject: function (url, acpStatus) {
        let recordId = this.getRecordId();

        if (acpStatus === 'Rejected') {
            let confMsg = "Do You Really Want To Reject this User ? ";
            app.helper.showConfirmationBox({ 'message': confMsg }).then(function (e) {
                var data = {
                    'module': 'Guard',
                    'view': 'RejectionReason',
                    'mode': 'showRejectionReasonForm'
                };
                app.request.post({ "data": data }).then(function (err, res) {
                    if (err === null) {
                        var cb = function (data) {
                            var form = jQuery(data).find('#AddRejectionReason');
                            var params = {
                                submitHandler: function (form) {
                                    var params = jQuery(form).serializeFormData();

                                    // ✅ FIXED: Use ApproveOrReject action and apStatus parameter
                                    app.request.post({
                                        data: {
                                            module: 'Guard',
                                            action: 'ApproveOrReject',  // Changed from SaveApprovalStatus
                                            record: recordId,
                                            apStatus: acpStatus,        // Changed from status to apStatus
                                            RejectionReason: params.rejectionReason
                                        }
                                    }).then(function (error, data) {
                                        if (!error && data.success) {
                                            app.helper.hideModal();
                                            app.helper.showSuccessNotification({ message: data.message });
                                            // ✅ FIXED: Reload page to hide buttons (like ServiceEngineer)
                                            location.reload();
                                        } else {
                                            app.helper.showErrorNotification({ message: error.message });
                                        }
                                    });
                                }
                            }
                            form.vtValidate(params);
                        }
                        app.helper.showModal(res, { "cb": cb });
                    }
                })
            });
        } else {
            // Approve
            let confMsg = "Do You Really Want To Accept this User ? </br>" +
                " * Please Make Sure User Has Correct Login Platforms </br>";
            app.helper.showConfirmationBox({ 'message': confMsg }).then(function (e) {

                // ✅ FIXED: Use ApproveOrReject action and apStatus parameter
                app.helper.showProgress();
                app.request.post({
                    data: {
                        module: 'Guard',
                        action: 'ApproveOrReject',  // Changed from SaveApprovalStatus
                        record: recordId,
                        apStatus: acpStatus         // Changed from status to apStatus
                    }
                }).then(function (error, data) {
                    app.helper.hideProgress();
                    if (!error && data.success) {
                        app.helper.showSuccessNotification({ message: data.message });
                        // ✅ FIXED: Reload page to hide buttons (like ServiceEngineer)
                        location.reload();
                    } else {
                        app.helper.showErrorNotification({ message: error.message });
                    }
                });
            });
        }
    },

    getRecordId: function () {
        return app.getRecordId();
    },

    triggerChangePassword: function (url, module) {
        var message = "Do You Really Want To Reset This User Password ?";
        app.helper.showConfirmationBox({ 'message': message }).then(function (e) {
            app.helper.showProgress();
            app.request.get({ 'url': url }).then(
                function (err, data) {
                    app.helper.hideProgress();
                    if (err == null) {
                        app.helper.hideModal();
                        var successMessage = app.vtranslate(data.message);
                        app.helper.showSuccessNotification({ "message": successMessage });
                    } else {
                        app.helper.showErrorNotification({ "message": err });
                        return false;
                    }
                }
            );
        });
    },
}, {});