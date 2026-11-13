<div class="card card-dark shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-list-ul mr-2"></i> {{ $title ?? 'All AO File Link Requests' }}
        </h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table id="{{ $tableId ?? 'FileSendTable' }}" class="table table-striped text-nowrap table-hover">
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Sender Name</th>
                    <th>Sender Email</th>
                    <th>Recipient Email</th>
                    <th>Shared File/Link</th>
                    <th>Date Sent</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data populated via DataTables -->
            </tbody>
        </table>
    </div>
</div>