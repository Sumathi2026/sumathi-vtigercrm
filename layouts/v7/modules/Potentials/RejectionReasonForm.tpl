<!-- Rejection Reason Modal Form -->
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="AddRejectionReason" method="post" action="">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <span class="fa fa-ban"></span>&nbsp;&nbsp;
                    {vtranslate('LBL_REJECTION_REASON', $MODULE)}
                </h4>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="control-label">
                                {vtranslate('LBL_REASON_FOR_REJECTION', $MODULE)}
                                <span class="redColor">*</span>
                            </label>
                            <textarea 
                                name="rejectionReason" 
                                id="rejectionReason" 
                                class="form-control" 
                                rows="4" 
                                placeholder="{vtranslate('LBL_ENTER_REJECTION_REASON', $MODULE)}"
                                required="required">
                            </textarea>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            {vtranslate('LBL_REJECTION_REASON_INFO', $MODULE)}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i>&nbsp;&nbsp;{vtranslate('LBL_CANCEL', $MODULE)}
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fa fa-ban"></i>&nbsp;&nbsp;{vtranslate('LBL_REJECT', $MODULE)}
                </button>
            </div>
        </form>
    </div>
</div>