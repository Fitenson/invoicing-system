<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
      line-height: 1.6;
      color: #333;
    }

    /* Basic layout */
    .card {
      margin: 20px;
      padding: 20px;
      border: 1px solid #ddd;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      background-color: #fff;
    }

    /* Typography */
    h2 {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 16px;
      color: #333;
    }

    h5 {
      font-size: 16px;
      margin-bottom: 16px;
      font-weight: normal;
    }

    /* Table styles */
    .table-responsive {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
      text-align: left;
      padding: 12px;
      font-weight: bold;
    }

    td {
      padding: 12px;
      vertical-align: top;
    }

    /* Form elements */
    input[type="number"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }

    input[readonly] {
      background-color: #f9f9f9;
      cursor: not-allowed;
    }

    /* Summary info */
    .form-control-plaintext {
      padding: 0.375rem 0;
      margin-bottom: 12px;
      font-size: 16px;
      border: 0;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Invoice</h2>
    <h5>Invoice Number: {{ $invoice['invoice_number'] ?? 'N/A' }}</h5>
    <h5>Client: {{ $invoice['client_name'] ?? 'N/A' }}</h5>
    <div class="card-body">
      <div class="table-responsive">
        <table id="projectTable">
          <thead>
            <tr>
              <th>Project Name</th>
              <th>Rate/Hour</th>
              <th>Total Hours</th>
            </tr>
          </thead>
          <tbody id="projectTableBody">
            @if(old('invoice_has_projects'))
              @foreach(old('invoice_has_projects') as $item)
                @php
                  $project = collect($projects)->firstWhere('id', $item['project']);
                @endphp
                <tr>
                  <td>
                    {{ $project['name'] ?? 'Unknown' }}
                    <input type="hidden" name="invoice_has_projects[][project]" value="{{ $item['project'] }}">
                  </td>
                  <td>
                    <input type="number" name="invoice_has_projects[][rate_per_hour]" value="{{ $item['rate_per_hour'] }}" step="any" min="0">
                  </td>
                  <td>
                    <input type="number" name="invoice_has_projects[][total_hours]" value="{{ $item['total_hours'] }}" step="any" min="0">
                  </td>
                </tr>
              @endforeach
            @else
              @foreach($invoice['projects'] as $invoice_project)
                @if(!empty($invoice_project['project']))
                  <tr data-id="{{ $invoice_project['id'] }}">
                    <td>
                      {{ $invoice_project['project']['name'] ?? 'Unknown' }}
                      <input type="hidden" name="project" value="{{ $invoice_project['project']['id'] }}">
                    </td>
                    <td>
                      <input type="number" name="rate_per_hour" value="{{ $invoice_project['project']['rate_per_hour'] }}" step="any" min="0" readonly>
                    </td>
                    <td>
                      <input type="number" name="total_hours" value="{{ $invoice_project['project']['total_hours'] }}" step="any" min="0" readonly>
                    </td>
                  </tr>
                @else
                  <tr data-id="{{ $invoice_project['id'] }}">
                    <td>
                      {{ 'Unknown' }}
                      <input type="hidden" name="project" value="{{ "" }}">
                    </td>
                    <td>
                      <input type="number" name="rate_per_hour" value="{{ 0.00 }}" step="any" min="0" readonly>
                    </td>
                    <td>
                      <input type="number" name="total_hours" value="{{ 0 }}" step="any" min="0" readonly>
                    </td>
                  </tr>
                @endif
              @endforeach
            @endif
          </tbody>
        </table>

        <p class="form-control-plaintext">Total Rate/Hour: {{ $invoice['total_rate_per_hour'] ?? 0 }}</p>
        <p class="form-control-plaintext">Total Hours: {{ $invoice['total_hours'] ?? 0 }}</p>
        <p class="form-control-plaintext">Total Income(RM): {{ $invoice['total_income'] ?? 0 }}</p>
      </div>
    </div>
  </div>
</body>
</html>
