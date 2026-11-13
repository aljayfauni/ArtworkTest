<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Send File Link - AdminLTE Dashboard with Notifications</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Bootstrap 4.6 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- AdminLTE 3 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Source Sans Pro', sans-serif;
    }
    .brand-header {
      background-color: #dc3545;
      color: #ffffff;
      padding: 4px 12px;
      border-radius: 4px;
      font-weight: bold;
      font-size: 1rem;
      letter-spacing: 0.5px;
    }
    .top-navbar {
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
    }
    .card-ao-header {
      background-color: #007bff;
      color: #ffffff;
    }
    .table-v-align td, .table-v-align th {
      vertical-align: middle !important;
    }
    .small-box .icon {
      color: rgba(0,0,0,0.12);
    }
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1060;
    }
    .badge-status-pending {
      background-color: #ffc107;
      color: #1f2d3d;
    }
    .badge-status-opened {
      background-color: #28a745;
      color: #fff;
    }
    .badge-status-expired {
      background-color: #dc3545;
      color: #fff;
    }
    
    /* Activity Feed & Timeline Custom Styles */
    .activity-feed-box {
      max-height: 410px;
      overflow-y: auto;
    }
    .timeline-item-custom {
      border-left: 3px solid #007bff;
      padding-left: 12px;
      position: relative;
      margin-bottom: 15px;
    }
    .timeline-item-custom.type-message {
      border-left-color: #17a2b8;
    }
    .timeline-item-custom.type-alert {
      border-left-color: #28a745;
    }
  </style>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Navbar Header -->
  <nav class="main-header navbar navbar-expand-md navbar-light top-navbar">
    <div class="container-fluid px-4">
      <a href="#" class="navbar-brand d-flex align-items-center">
        <span class="brand-header mr-2">TEST ENV</span>
      </a>

      <div class="ml-auto d-flex align-items-center">
        <div class="text-right mr-3">
          <div class="font-weight-bold text-dark" style="font-size: 1.1rem;" id="liveClockTime">08:27:14</div>
          <small class="text-muted text-uppercase" id="liveClockDate">Tuesday, Oct 13, 2026</small>
        </div>
        <div class="border-left pl-3 text-secondary">
          <i class="fas fa-search fa-lg cursor-pointer" title="Search"></i>
        </div>
      </div>
    </div>
  </nav>

  <!-- Toast Notifications Container -->
  <div class="toast-container" id="toastContainer"></div>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    
    <!-- Page Header -->
    <div class="content-header">
      <div class="container-fluid px-4">
        <div class="row mb-2 align-items-center">
          <div class="col-sm-6">
            <h1 class="m-0 font-weight-normal text-dark">Send File Link Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home mr-1"></i> Home</a></li>
              <li class="breadcrumb-item active">Send File Link</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content">
      <div class="container-fluid px-4">

        <!-- 1. Top Section KPI Summary Cards -->
        <div class="row">
          <div class="col-xl-3 col-md-6 col-12">
            <div class="small-box bg-info shadow-sm">
              <div class="inner">
                <h3 id="statTotalSent">142</h3>
                <p>Total Links Sent</p>
              </div>
              <div class="icon">
                <i class="fas fa-paper-plane"></i>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 col-12">
            <div class="small-box bg-success shadow-sm">
              <div class="inner">
                <h3 id="statOpened">89</h3>
                <p>Links Opened</p>
              </div>
              <div class="icon">
                <i class="fas fa-envelope-open-text"></i>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 col-12">
            <div class="small-box bg-warning shadow-sm">
              <div class="inner">
                <h3 id="statPending">48</h3>
                <p>Pending Views</p>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 col-12">
            <div class="small-box bg-danger shadow-sm">
              <div class="inner">
                <h3 id="statExpired">5</h3>
                <p>Expired Links</p>
              </div>
              <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- 2. Main Content Split Layout -->
        <div class="row">
          
          <!-- LEFT SIDE / MAIN COLUMN (col-lg-8) -->
          <div class="col-lg-8">

            <!-- Submission Form Card -->
            <div class="card shadow-sm mb-4">
              <div class="card-header card-ao-header py-2">
                <h5 class="card-title font-weight-bold m-0" style="font-size: 1.05rem;">
                  <i class="fas fa-link mr-1"></i> AO Link Submission Form To GD
                </h5>
              </div>

              <form id="submissionForm">
                <div class="card-body bg-white">
                  <!-- Row 1 (Sender, Recipient, File URL) -->
                  <div class="row">
                    <div class="form-group col-md-4">
                      <label for="senderEmail" class="font-weight-bold text-secondary">
                        Sender Email <span class="text-muted">(GD)</span>
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                        </div>
                        <input type="email" id="senderEmail" class="form-control" value="AFRAMILE@UGECORP.COM" required>
                      </div>
                    </div>

                    <div class="form-group col-md-4">
                      <label for="recipientEmail" class="font-weight-bold text-secondary">
                        Recipient Email <span class="text-danger">*</span>
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                        </div>
                        <input type="email" id="recipientEmail" class="form-control" placeholder="Enter Recipient Email" required>
                      </div>
                    </div>

                    <div class="form-group col-md-4">
                      <label for="fileUrl" class="font-weight-bold text-secondary">
                        File URL / Link <span class="text-danger">*</span>
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-globe text-muted"></i></span>
                        </div>
                        <input type="url" id="fileUrl" class="form-control" placeholder="https://google.com" required>
                      </div>
                    </div>
                  </div>

                  <!-- Row 2 (Title, Expiration, Access) -->
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="fileTitle" class="font-weight-bold text-secondary">File Title / Description</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-file-alt text-muted"></i></span>
                        </div>
                        <input type="text" id="fileTitle" class="form-control" placeholder="e.g. Q4 Audit Report Document">
                      </div>
                    </div>

                    <div class="form-group col-md-3">
                      <label for="expirationDate" class="font-weight-bold text-secondary">Link Expiration Date</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-calendar-alt text-muted"></i></span>
                        </div>
                        <input type="date" id="expirationDate" class="form-control">
                      </div>
                    </div>

                    <div class="form-group col-md-3">
                      <label for="accessLevel" class="font-weight-bold text-secondary">Access Level</label>
                      <select id="accessLevel" class="form-control custom-select">
                        <option value="Restricted">Restricted (Internal)</option>
                        <option value="Public">Public (Anyone)</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="card-footer bg-light border-top text-left py-2">
                  <button type="submit" class="btn btn-success px-4 font-weight-bold mr-2">
                    <i class="fas fa-paper-plane mr-1"></i> Submit & Send Email
                  </button>
                  <button type="button" id="btnReset" class="btn btn-secondary px-3">
                    <i class="fas fa-undo mr-1"></i> Reset
                  </button>
                </div>
              </form>
            </div>

            <!-- Record Data Table Card -->
            <div class="card shadow-sm border-0 mb-4">
              <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="card-title font-weight-bold m-0 text-dark">
                  <i class="fas fa-users-cog text-primary mr-2"></i>Send Links Record
                </h5>
              </div>

              <div class="card-body p-3">
                <!-- Search & Filters -->
                <div class="row mb-3 align-items-center">
                  <div class="col-sm-6 d-flex align-items-center mb-2 mb-sm-0">
                    <span class="text-secondary mr-2">Show</span>
                    <select id="entriesPerPage" class="form-control form-control-sm w-auto mr-2">
                      <option value="10" selected>10</option>
                      <option value="25">25</option>
                    </select>
                    <span class="text-secondary">entries</span>
                  </div>
                  <div class="col-sm-6 d-flex justify-content-sm-end align-items-center">
                    <label for="tableSearch" class="text-secondary mr-2 mb-0">Search:</label>
                    <input type="search" id="tableSearch" class="form-control form-control-sm w-auto" placeholder="Search record...">
                  </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                  <table class="table table-bordered table-striped table-hover table-v-align mb-0">
                    <thead class="thead-light">
                      <tr>
                        <th>Sender Name</th>
                        <th>Sender Email</th>
                        <th>Recipient Email</th>
                        <th>Shared File / Link</th>
                        <th>Date Sent</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="min-width: 120px;">Actions</th>
                      </tr>
                    </thead>
                    <tbody id="tableBody">
                      <!-- Dynamically rendered via JS -->
                    </tbody>
                  </table>
                </div>

                <!-- Pagination Footer -->
                <div class="row mt-3 align-items-center">
                  <div class="col-sm-6 text-muted" id="tableInfo">
                    Showing 1 to 3 of 3 entries
                  </div>
                  <div class="col-sm-6 d-flex justify-content-sm-end mt-2 mt-sm-0">
                    <ul class="pagination pagination-sm m-0">
                      <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                      <li class="page-item active"><a class="page-link" href="#">1</a></li>
                      <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                    </ul>
                  </div>
                </div>

              </div>
            </div>

          </div><!-- /.col-lg-8 -->


          <!-- RIGHT SIDE COLUMN (col-lg-4): Notifications & Messaging Panel -->
          <div class="col-lg-4">

            <!-- 1. Live Notifications & Recent Activity Feed Card -->
            <div class="card card-outline card-primary shadow-sm mb-4">
              <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold text-dark m-0" style="font-size: 1rem;">
                  <i class="fas fa-bell text-primary mr-1"></i> Notifications & Activity
                </h3>
                <span class="badge badge-info font-weight-normal" id="feedBadgeCount">3 New</span>
              </div>

              <div class="card-body p-3 activity-feed-box" id="activityFeedList">
                
                <!-- Activity Item 1 -->
                <div class="timeline-item-custom type-alert">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">File Link Opened</span>
                    <small class="text-muted"><i class="far fa-clock mr-1"></i>2 mins ago</small>
                  </div>
                  <p class="mb-1 text-secondary" style="font-size: 0.85rem;">
                    Recipient <b class="text-dark">AFRAMILE@UGECORP.COM</b> opened <u>sample.pdf</u>
                  </p>
                  <span class="badge badge-light border text-muted" style="font-size: 0.75rem;">Status: Opened</span>
                </div>

                <!-- Activity Item 2 -->
                <div class="timeline-item-custom">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">New Submission Sent</span>
                    <small class="text-muted"><i class="far fa-clock mr-1"></i>14 mins ago</small>
                  </div>
                  <p class="mb-1 text-secondary" style="font-size: 0.85rem;">
                    Sent <b>yahoo.com</b> to <b class="text-dark">ALTEST@G.COM</b>
                  </p>
                  <span class="badge badge-light border text-muted" style="font-size: 0.75rem;">Sent by ALJAY F. RAMILE</span>
                </div>

                <!-- Activity Item 3 -->
                <div class="timeline-item-custom type-message">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">Internal Note Attached</span>
                    <small class="text-muted"><i class="far fa-clock mr-1"></i>1 hour ago</small>
                  </div>
                  <p class="mb-1 text-secondary" style="font-size: 0.85rem;">
                    <i>"Please review the attached spreadsheet before the GD meeting today."</i>
                  </p>
                  <small class="text-info font-weight-bold">~ GD Dispatch Team</small>
                </div>

              </div>
              
              <div class="card-footer bg-light py-2 text-center">
                <button type="button" class="btn btn-sm btn-link text-secondary" onclick="clearFeed()">
                  <i class="fas fa-check-double mr-1"></i> Mark all as read
                </button>
              </div>
            </div>

            <!-- 2. Quick Direct Message / Internal Note Widget -->
            <div class="card card-outline card-info shadow-sm mb-4">
              <div class="card-header py-2">
                <h3 class="card-title font-weight-bold text-dark m-0" style="font-size: 1rem;">
                  <i class="fas fa-comment-alt text-info mr-1"></i> Direct Message / Quick Note
                </h3>
              </div>

              <div class="card-body bg-white p-3">
                <div class="form-group mb-2">
                  <label for="msgRecipient" class="small font-weight-bold text-secondary mb-1">To Recipient</label>
                  <select id="msgRecipient" class="form-control form-control-sm">
                    <option value="ALTEST@G.COM">ALTEST@G.COM</option>
                    <option value="AFRAMILE@UGECORP.COM">AFRAMILE@UGECORP.COM (GD)</option>
                    <option value="ALL GD Team">ALL GD Team</option>
                  </select>
                </div>

                <div class="form-group mb-2">
                  <label for="msgContent" class="small font-weight-bold text-secondary mb-1">Message / Note</label>
                  <textarea id="msgContent" class="form-control form-control-sm" rows="3" placeholder="Type a quick message or note regarding the shared link..."></textarea>
                </div>

                <button type="button" id="btnSendMessage" class="btn btn-info btn-block btn-sm font-weight-bold">
                  <i class="fas fa-paper-plane mr-1"></i> Send Quick Message
                </button>
              </div>
            </div>

          </div><!-- /.col-lg-4 -->

        </div><!-- /.row -->

      </div><!-- /.container-fluid -->
    </div><!-- /.content -->
  </div><!-- /.content-wrapper -->

  <!-- Footer -->
  <footer class="main-footer text-sm py-2">
    <div class="float-right d-none d-sm-inline">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2026 <a href="#">UGE Corp</a>.</strong> All rights reserved.
  </footer>
</div>

<!-- JS Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
  // Initial Dataset
  let records = [
    {
      id: 1,
      senderName: "ALJAY F. RAMILE",
      senderEmail: "AFRAMILE@UGECORP.COM",
      recipientEmail: "ALTEST@G.COM",
      link: "yahoo.com",
      url: "http://yahoo.com",
      dateSent: "2026-10-12 14:21:06",
      status: "Pending"
    },
    {
      id: 2,
      senderName: "ALJAY F. RAMILE",
      senderEmail: "AFRAMILE@UGECORP.COM",
      recipientEmail: "AFRAMILE@UGECORP.COM",
      link: "sample.pdf",
      url: "https://pdfobject.com/pdf/sample.pdf",
      dateSent: "2026-10-12 16:17:37",
      status: "Opened"
    },
    {
      id: 3,
      senderName: "ALJAY F. RAMILE",
      senderEmail: "AFRAMILE@UGECORP.COM",
      recipientEmail: "AFRAMILE@UGECORP.COM",
      link: "google.com",
      url: "https://google.com",
      dateSent: "2026-10-12 10:49:39",
      status: "Opened"
    }
  ];

  let unreadNotificationsCount = 3;

  // Toast Notification System
  function showToast(message, bgClass = 'bg-success') {
    const toastId = 'toast-' + Date.now();
    const html = `
      <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
        <div class="d-flex p-2">
          <div class="toast-body">
            <i class="fas fa-info-circle mr-2"></i> ${message}
          </div>
          <button type="button" class="ml-auto mr-2 close text-white" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
    `;
    $('#toastContainer').append(html);
    $(`#${toastId}`).toast('show');
    setTimeout(() => { $(`#${toastId}`).remove(); }, 3500);
  }

  // Render Table Records
  function renderTable(data = records) {
    const tbody = $('#tableBody');
    tbody.empty();

    if (data.length === 0) {
      tbody.append(`
        <tr>
          <td colspan="7" class="text-center text-muted py-4">No send link records found.</td>
        </tr>
      `);
      $('#tableInfo').text('Showing 0 to 0 of 0 entries');
      return;
    }

    data.forEach(item => {
      let badgeClass = 'badge-status-pending';
      let statusIcon = 'fa-clock';
      if (item.status === 'Opened') {
        badgeClass = 'badge-status-opened';
        statusIcon = 'fa-check-circle';
      } else if (item.status === 'Expired') {
        badgeClass = 'badge-status-expired';
        statusIcon = 'fa-exclamation-circle';
      }

      const rowHtml = `
        <tr>
          <td class="font-weight-normal text-uppercase">${item.senderName}</td>
          <td>${item.senderEmail}</td>
          <td>${item.recipientEmail}</td>
          <td>
            <a href="${item.url}" target="_blank" class="text-primary font-weight-normal text-break" title="${item.url}">
              ${item.link}
            </a>
          </td>
          <td class="text-nowrap">${item.dateSent}</td>
          <td class="text-center">
            <span class="badge ${badgeClass} px-2 py-1 font-weight-normal">
              <i class="fas ${statusIcon} mr-1"></i>${item.status}
            </span>
          </td>
          <td class="text-center">
            <div class="btn-group btn-group-sm">
              <button type="button" class="btn btn-default" onclick="copyToClipboard('${item.url}')" title="Copy Link">
                <i class="fas fa-copy text-info"></i>
              </button>
              <button type="button" class="btn btn-default" onclick="resendEmail(${item.id})" title="Resend Notification">
                <i class="fas fa-paper-plane text-primary"></i>
              </button>
              <button type="button" class="btn btn-default" onclick="deleteRecord(${item.id})" title="Delete Link">
                <i class="fas fa-trash text-danger"></i>
              </button>
            </div>
          </td>
        </tr>
      `;
      tbody.append(rowHtml);
    });

    $('#tableInfo').text(`Showing 1 to ${data.length} of ${data.length} entries`);
  }

  // Update Counters
  function updateCounters() {
    $('#statTotalSent').text(records.length + 139);
    const opened = records.filter(r => r.status === 'Opened').length + 87;
    const pending = records.filter(r => r.status === 'Pending').length + 47;
    const expired = records.filter(r => r.status === 'Expired').length + 5;

    $('#statOpened').text(opened);
    $('#statPending').text(pending);
    $('#statExpired').text(expired);
  }

  // Copy Link Action
  function copyToClipboard(text) {
    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    showToast('Link copied to clipboard!', 'bg-info');
  }

  // Add Notification Item to Right Panel
  function addNotificationItem(title, body, badgeText, typeClass = '') {
    unreadNotificationsCount++;
    $('#feedBadgeCount').text(`${unreadNotificationsCount} New`);

    const newFeedHtml = `
      <div class="timeline-item-custom ${typeClass}">
        <div class="d-flex justify-content-between align-items-center">
          <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">${title}</span>
          <small class="text-muted"><i class="far fa-clock mr-1"></i>Just now</small>
        </div>
        <p class="mb-1 text-secondary" style="font-size: 0.85rem;">${body}</p>
        <span class="badge badge-light border text-muted" style="font-size: 0.75rem;">${badgeText}</span>
      </div>
    `;
    $('#activityFeedList').prepend(newFeedHtml);
  }

  // Resend Email Notification
  function resendEmail(id) {
    const record = records.find(r => r.id === id);
    if (record) {
      record.status = 'Opened';
      renderTable();
      updateCounters();
      
      addNotificationItem('Notification Resent', `Resent link email notification to <b>${record.recipientEmail}</b>`, 'Action: Resend', 'type-alert');
      showToast(`Resent notification email to ${record.recipientEmail}`, 'bg-success');
    }
  }

  // Delete Record
  function deleteRecord(id) {
    const record = records.find(r => r.id === id);
    if (record) {
      records = records.filter(r => r.id !== id);
      renderTable();
      updateCounters();

      addNotificationItem('Link Record Removed', `Deleted shared link record for <b>${record.recipientEmail}</b>`, 'Action: Delete', '');
      showToast('Record deleted successfully.', 'bg-danger');
    }
  }

  // Form Submission Handler
  $('#submissionForm').on('submit', function (e) {
    e.preventDefault();

    const senderEmail = $('#senderEmail').val().trim();
    const recipientEmail = $('#recipientEmail').val().trim();
    const fileUrl = $('#fileUrl').val().trim();
    const fileTitle = $('#fileTitle').val().trim();

    const now = new Date();
    const dateSent = now.getFullYear() + '-' +
      String(now.getMonth() + 1).padStart(2, '0') + '-' +
      String(now.getDate()).padStart(2, '0') + ' ' +
      String(now.getHours()).padStart(2, '0') + ':' +
      String(now.getMinutes()).padStart(2, '0') + ':' +
      String(now.getSeconds()).padStart(2, '0');

    let name = "ALJAY F. RAMILE";
    if (senderEmail.includes('@')) {
      name = senderEmail.split('@')[0].replace('.', ' ').toUpperCase();
    }

    const shortLink = fileUrl.replace(/^https?:\/\//, '');

    const newEntry = {
      id: Date.now(),
      senderName: name,
      senderEmail: senderEmail,
      recipientEmail: recipientEmail,
      link: shortLink,
      url: fileUrl,
      dateSent: dateSent,
      status: 'Pending'
    };

    records.unshift(newEntry);
    renderTable();
    updateCounters();

    // Push into Right-side Notification Panel
    addNotificationItem(
      'New File Link Submitted',
      `Sent <b>${shortLink}</b> to <b class="text-dark">${recipientEmail}</b> ${fileTitle ? '(' + fileTitle + ')' : ''}`,
      `Sent by ${name}`,
      'type-alert'
    );

    // Reset optional fields
    $('#recipientEmail').val('');
    $('#fileUrl').val('');
    $('#fileTitle').val('');
    $('#expirationDate').val('');

    showToast('File link submitted and notification logged!');
  });

  // Reset Button
  $('#btnReset').on('click', function() {
    $('#recipientEmail').val('');
    $('#fileUrl').val('');
    $('#fileTitle').val('');
    $('#expirationDate').val('');
    showToast('Form reset.', 'bg-secondary');
  });

  // Quick Direct Message Send
  $('#btnSendMessage').on('click', function() {
    const recipient = $('#msgRecipient').val();
    const msg = $('#msgContent').val().trim();

    if (!msg) {
      showToast('Please enter a message or note.', 'bg-warning');
      return;
    }

    addNotificationItem(
      `Message to ${recipient}`,
      `<i>"${msg}"</i>`,
      'Internal Note / Message',
      'type-message'
    );

    $('#msgContent').val('');
    showToast(`Quick message sent to ${recipient}!`, 'bg-info');
  });

  // Clear Activity Feed Badges
  function clearFeed() {
    unreadNotificationsCount = 0;
    $('#feedBadgeCount').text('0 New');
    showToast('Marked all notifications as read.', 'bg-secondary');
  }

  // Live Search Filtering
  $('#tableSearch').on('keyup input', function () {
    const value = $(this).val().toLowerCase();
    const filtered = records.filter(item => {
      return item.senderName.toLowerCase().includes(value) ||
        item.senderEmail.toLowerCase().includes(value) ||
        item.recipientEmail.toLowerCase().includes(value) ||
        item.link.toLowerCase().includes(value) ||
        item.status.toLowerCase().includes(value);
    });
    renderTable(filtered);
  });

  // Clock Update
  function updateLiveClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    $('#liveClockTime').text(`${hours}:${minutes}:${seconds}`);
  }

  // Document Ready Initialization
  $(document).ready(function () {
    renderTable();
    updateCounters();
    setInterval(updateLiveClock, 1000);
    updateLiveClock();
  });
</script>
</body>
</html>