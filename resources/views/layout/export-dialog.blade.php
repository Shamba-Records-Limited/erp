<!-- Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Export Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form group for Export Type -->
                <div class="form-group">
                    <label for="exportType">Export Type</label>
                    <div class="btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-primary active">
                            <input type="radio" name="exportType" id="xlsx" autocomplete="off" value="xlsx" checked>
                            Excel
                        </label>
                        <label class="btn btn-primary">
                            <input type="radio" name="exportType" id="pdf" autocomplete="off" value="pdf"> PDF
                        </label>
                    </div>
                    <!-- Error message for export type -->
                    @if ($errors->has('exportType'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('exportType') }}</strong>
                    </span>
                    @endif
                </div>

                <!-- Form groups arranged as rectangles side by side -->
                <div class="row">
                    <!-- Date Range Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <label for="dateRange">Date Range</label>
                                @php
                                $dateRange = old('dateRange', 'today');
                                @endphp
                                <select name="dateRange" id="dateRange"
                                    class="form-control {{ $errors->has('dateRange') ? 'is-invalid' : '' }}">
                                    <option value="today">Today</option>
                                    <option value="yesterday">Yesterday</option>
                                    <option value="last7days">Last 7 Days</option>
                                    <option value="last30days">Last 30 Days</option>
                                    <option value="last60days">Last 60 Days</option>
                                    <option value="custom">Custom</option>
                                </select>
                                @if ($errors->has('dateRange'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('dateRange') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Start Date Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                @php
                                $startDate = old('startDate', date('Y-m-d'));
                                @endphp
                                <label for="startDate">Start Date</label>
                                <input type="date" name="startDate" id="startDate"
                                    class="form-control {{ $errors->has('startDate') ? 'is-invalid' : '' }}"
                                    value="{{ $startDate }}" readonly required>
                                @if ($errors->has('startDate'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('startDate') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- End Date Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                @php
                                $endDate = old('endDate', date('Y-m-d'));
                                @endphp
                                <label for="endDate">End Date</label>
                                <input type="date" name="endDate" id="endDate"
                                    class="form-control {{ $errors->has('endDate') ? 'is-invalid' : '' }}"
                                    value="{{ $endDate }}" readonly required>
                                @if ($errors->has('endDate'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('endDate') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="doExport">Export</button>
            </div>
        </div>
    </div>
</div>