<div class="modal fade" id="confirmDeleteModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p>Are you sure you want to delete this entry?</p>

                <ul>
                    <li><strong>Date:</strong> <span id="confirmDate"></span></li>
                    <li><strong>Amount:</strong> <span id="confirmAmount"></span></li>
                </ul>

            </div>

            <div class="modal-footer">

                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button id="confirmDeleteBtn" class="btn btn-danger">
                    <span class="btn-text">Confirm Delete</span>
                    <span class="spinner-border spinner-border-sm d-none" id="deleteSpinner"></span>
                </button>

            </div>

        </div>
    </div>

</div>
