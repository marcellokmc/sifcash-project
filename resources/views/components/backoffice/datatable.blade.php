@props(['id' => 'dataTable', 'headers' => [], 'searchable' => true, 'exportable' => false])

<div class="card shadow mb-4">
    @if(isset($header))
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        {{ $header }}
    </div>
    @endif
    
    <div class="card-body">
        @if($searchable || $exportable)
        <div class="row mb-3">
            @if($searchable)
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Rechercher..." id="{{ $id }}Search">
                </div>
            </div>
            @endif
            
            @if($exportable)
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <button class="btn btn-outline-success btn-sm" type="button" id="{{ $id }}ExportCSV">
                        <i class="fas fa-file-csv me-1"></i>CSV
                    </button>
                    <button class="btn btn-outline-danger btn-sm" type="button" id="{{ $id }}ExportPDF">
                        <i class="fas fa-file-pdf me-1"></i>PDF
                    </button>
                    <button class="btn btn-outline-primary btn-sm" type="button" id="{{ $id }}ExportExcel">
                        <i class="fas fa-file-excel me-1"></i>Excel
                    </button>
                </div>
            </div>
            @endif
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="{{ $id }}" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        @foreach($headers as $header)
                        <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    {{ $slot }}
                </tbody>
                @if(isset($footer))
                <tfoot>
                    <tr>
                        {{ $footer }}
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        @if(isset($pagination))
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Affichage de <span id="{{ $id }}Start">1</span> à <span id="{{ $id }}End">10</span> 
                sur <span id="{{ $id }}Total">0</span> résultats
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="{{ $id }}Pagination">
                    <!-- La pagination sera générée par JavaScript -->
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableId = '{{ $id }}';
    const searchInput = document.getElementById(tableId + 'Search');
    const table = document.getElementById(tableId);
    
    // Fonction de recherche
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent || cells[j].innerText;
                    if (cellText.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
                
                rows[i].style.display = found ? '' : 'none';
            }
        });
    }
    
    // Fonctions d'export (basiques)
    @if($exportable)
    document.getElementById(tableId + 'ExportCSV')?.addEventListener('click', function() {
        exportTableToCSV(tableId, 'export.csv');
    });
    
    document.getElementById(tableId + 'ExportPDF')?.addEventListener('click', function() {
        alert('Fonction PDF à implémenter');
    });
    
    document.getElementById(tableId + 'ExportExcel')?.addEventListener('click', function() {
        exportTableToExcel(tableId, 'export.xls');
    });
    @endif
});

// Fonction d'export CSV
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    const rows = table.querySelectorAll('tr');
    let csv = [];
    
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            row.push('"' + (cols[j].innerText || '').replace(/"/g, '""') + '"');
        }
        
        csv.push(row.join(','));
    }
    
    // Téléchargement
    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Fonction d'export Excel
function exportTableToExcel(tableId, filename) {
    const table = document.getElementById(tableId);
    const html = table.outerHTML;
    const url = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(html);
    const downloadLink = document.createElement('a');
    
    downloadLink.download = filename;
    downloadLink.href = url;
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>
@endpush