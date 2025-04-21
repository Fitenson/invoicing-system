<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<body>
    <div class="card shadow-sm">
        <h2 class="mb-4 font-weight-bold">Invoice</h2>
        <h5 class="mb-4">Invoice Number: {{ $invoice['invoice_number'] ?? 'N/A' }}</h5>
        <h5 class="mb-4">Client: {{ $invoice['client_name'] ?? 'N/A' }}</h5>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-bordered" id="projectTable">
                <thead class="table-light">
                    <tr>
                        <th>Project Name</th>
                        <th>Rate/Hour</th>
                        <th>Total Hours</th>
                    </tr>
                </thead>
                <tbody id="projectTableBody">
                    {{-- Show either old values (on validation error) or saved invoice projects --}}
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
                                    <input type="number" name="invoice_has_projects[][rate_per_hour]" class="form-control" value="{{ $item['rate_per_hour'] }}" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" name="invoice_has_projects[][total_hours]" class="form-control" value="{{ $item['total_hours'] }}" step="any" min="0">
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
                                        <input type="number" name="rate_per_hour" class="form-control" value="{{ $invoice_project['project']['rate_per_hour'] }}" step="any" min="0" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="total_hours" class="form-control" value="{{ $invoice_project['project']['total_hours'] }}" step="any" min="0" readonly>
                                    </td>
                                </tr>
                            @else
                                <tr data-id="{{ $invoice_project['id'] }}">
                                    <td>
                                        {{ 'Unknown' }}
                                        <input type="hidden" name="project" value="{{ "" }}">
                                    </td>
                                    <td>
                                        <input type="number" name="rate_per_hour" class="form-control" value="{{ 0.00 }}" step="any" min="0" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="total_hours" class="form-control" value="{{ 0 }}" step="any" min="0" readonly>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>

            <p class="form-control-plaintext mb-3">Total Rate/Hour: {{ $invoice['total_rate_per_hour'] ?? 0 }}</p>
            <p class="form-control-plaintext mb-3">Total Hours: {{ $invoice['total_hours'] ?? 0 }}</p>
            <p class="form-control-plaintext mb-3">Total Income(RM): {{ $invoice['total_income'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</body>
</html>
