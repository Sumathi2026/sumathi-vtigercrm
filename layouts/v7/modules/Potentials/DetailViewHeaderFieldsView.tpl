{* Approval Buttons for Potentials *}
{if $RECORD->get('approval_status') eq 'Pending'}
    <div class="btn-group">
        <button class="btn btn-success"
            onclick="Potentials_Detail_Js.prototype.approveOrReject('index.php?module=Potentials&action=ApproveOrReject&record={$RECORD->getId()}', 'Approved')">
            Approve
        </button>
        <button class="btn btn-danger"
            onclick="Potentials_Detail_Js.prototype.approveOrReject('index.php?module=Potentials&action=ApproveOrReject&record={$RECORD->getId()}', 'Rejected')">
            Reject
        </button>
    </div>
{/if}
